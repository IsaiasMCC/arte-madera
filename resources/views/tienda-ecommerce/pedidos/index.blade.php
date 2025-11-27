@extends('layouts.tienda')

@section('title', 'Mis Pedidos')


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    function mostrarModalPago(monto, saldo) {
        const texto = `Pago registrado: $${monto}.<br>Saldo pendiente: $${saldo}`;
        document.getElementById("textoPagoRealizado").innerHTML = texto;

        const modal = new bootstrap.Modal(document.getElementById("modalPagoRealizado"));
        modal.show();
    }

    // Inicializar estado de los formularios según el método seleccionado
    document.querySelectorAll('.selectorMetodoPago').forEach(select => {
        const pedidoId = select.dataset.pedidoId;
        const metodo = select.value;

        document.getElementById("pagoManual" + pedidoId).style.display =
            (metodo === "manual") ? "block" : "none";

        document.getElementById("pagoQR" + pedidoId).style.display =
            (metodo === "pagofacil") ? "block" : "none";
    });

    // Evento de cambio en método de pago
    document.querySelectorAll('.selectorMetodoPago').forEach(select => {
        select.addEventListener('change', function() {
            const pedidoId = this.dataset.pedidoId;
            const metodo = this.value;

            document.getElementById("pagoManual" + pedidoId).style.display =
                (metodo === "manual") ? "block" : "none";

            document.getElementById("pagoQR" + pedidoId).style.display =
                (metodo === "pagofacil") ? "block" : "none";
        });
    });

    // PAGO MANUAL
    document.querySelectorAll('.btnPagarManual').forEach(btn => {
        btn.addEventListener('click', function() {
            const pagoId = this.dataset.pagoId;
            const input = document.querySelector('.montoManualInput[data-pago-id="' + pagoId + '"]');
            const monto = parseFloat(input.value);

            if (!monto || monto <= 0) {
                alert("Ingresa un monto válido");
                return;
            }

            fetch("{{ url('/checkout/procesar/detalle') }}/" + pagoId, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ monto })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mostrarModalPago(data.monto, data.saldo);
                    setTimeout(() => location.reload(), 3000);
                } else {
                    alert("Error: " + (data.error || "No se pudo procesar"));
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error de comunicación");
            });
        });
    });

    // GENERAR QR
    document.querySelectorAll('.btnGenerarQR').forEach(btn => {
        btn.addEventListener('click', function() {

            const pagoId = this.dataset.pagoId;
            const pedidoId = this.dataset.pedidoId;

            // CORRECCIÓN PRINCIPAL: selector 100% seguro
            const montoInputQR = document.querySelector(`#pagoQR${pedidoId} .montoQRInput`);
            const montoQR = parseFloat(montoInputQR.value);

            if (!montoQR || montoQR <= 0) {
                alert("Ingresa un monto válido para generar el QR");
                return;
            }

            const qrContainer = document.getElementById("qrContainer" + pedidoId);
            const estadoPago = document.getElementById("estadoPago" + pedidoId);

            qrContainer.innerHTML = "Generando QR...";
            estadoPago.innerHTML = "";

            fetch("{{ url('/pagofacil/generar-qr') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    pago_id: pagoId,
                    monto: montoQR
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.qr) {
                    qrContainer.innerHTML = `
                        <img src="${data.qr}" style="width:250px;">
                        <p class="mt-2">Transacción: ${data.transaccion}</p>

                        <button class="btn btn-success mt-3 btnVerificarQR"
                            data-transaccion="${data.transaccion}"
                            data-pedido-id="${pedidoId}">
                            Verificar Pago
                        </button>
                    `;
                } else {
                    qrContainer.innerHTML = "Error: " + (data.error ?? "No se pudo generar el QR");
                }
            })
            .catch(err => {
                console.error("Error QR:", err);
                qrContainer.innerHTML = "Error de comunicación";
            });
        });
    });

    // VERIFICAR PAGO QR
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btnVerificarQR')) {

            const transaccion = e.target.dataset.transaccion;
            const pedidoId = e.target.dataset.pedidoId;
            const estadoPago = document.getElementById("estadoPago" + pedidoId);

            estadoPago.innerHTML = "Consultando...";

            fetch("{{ url('/pagofacil/consultar-estado') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ tnTransaccion: transaccion })
            })
            .then(res => res.json())
            .then(data => {

                if (data.error) {
                    estadoPago.innerHTML = "❌ Error: " + data.error;
                    return;
                }

                estadoPago.innerHTML = "Estado: " + (data.estado || "DESCONOCIDO");

                if (data.estado === "COMPLETADO") {
                    mostrarModalPago(data.monto, data.saldo);
                    setTimeout(() => location.reload(), 4000);

                } else if (data.estado === "PENDIENTE") {
                    estadoPago.innerHTML += " ⏳ (Pago pendiente)";
                } else if (data.estado === "RECHAZADO") {
                    estadoPago.innerHTML += " ❌ (Pago rechazado)";
                }
            })
            .catch(err => {
                console.error(err);
                estadoPago.innerHTML = "Error de comunicación";
            });
        }
    });

});
</script>
@endpush


@section('content')
    <h1 class="mb-4 text-center" style="color:#8B5E3C;">Mis Pedidos</h1>

    @forelse($pedidos as $pedido)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pedido #{{ $pedido->id }} - {{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                <span class="badge bg-{{ $pedido->saldoPendiente() == 0 ? 'success' : 'warning' }}">
                    {{ $pedido->saldoPendiente() == 0 ? 'Pagado' : 'Pendiente' }}
                </span>
            </div>

            <div class="card-body">
                <h5 class="card-title">Productos</h5>
                <ul class="list-group list-group-flush mb-3">
                    @foreach ($pedido->detalles as $detalle)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $detalle->producto->nombre }} (x{{ $detalle->cantidad }})
                            <span>$ {{ number_format($detalle->subtotal * $detalle->cantidad, 2) }}</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        Total:
                        <span>$ {{ number_format($pedido->total, 2) }}</span>
                    </li>
                </ul>

                <h6>Pagos realizados</h6>
                @if ($pedido->pago && $pedido->pago->detallePagos->count() > 0)
                    <ul class="list-group mb-3">
                        @foreach ($pedido->pago->detallePagos as $dp)
                            <li class="list-group-item d-flex justify-content-between">
                                {{ $dp->fecha }} {{ $dp->hora }}: $ {{ number_format($dp->monto, 2) }}
                                <span>Saldo restante: $ {{ number_format($dp->saldo, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No se han realizado pagos aún.</p>
                @endif

                @if ($pedido->saldoPendiente() > 0)

                    <div class="mt-3">
                        <label class="form-label fw-bold">Método de pago</label>
                        <select class="form-select selectorMetodoPago" data-pedido-id="{{ $pedido->id }}">
                            <option value="manual">Pago manual</option>
                            <option value="pagofacil">Pago QR - PagoFácil</option>
                        </select>
                    </div>

                    <div class="mt-3 pagoManual" id="pagoManual{{ $pedido->id }}">
                        <input type="number" step="0.01" min="0.01" max="{{ $pedido->saldoPendiente() }}"
                            class="form-control montoManualInput mb-2"
                            data-pago-id="{{ $pedido->pago->id ?? 0 }}"
                            placeholder="Monto a pagar">

                        <button class="btn btn-wood w-100 btnPagarManual"
                            data-pago-id="{{ $pedido->pago->id ?? 0 }}">
                            Pagar Manualmente
                        </button>
                    </div>

                    <div class="mt-3 pagoQR" id="pagoQR{{ $pedido->id }}" style="display:none;">
                        <input type="number" step="0.01" min="0.01" max="{{ $pedido->saldoPendiente() }}"
                            class="form-control montoQRInput mb-2"
                            data-pago-id="{{ $pedido->pago->id ?? 0 }}"
                            data-pedido-id="{{ $pedido->id }}"
                            placeholder="Monto a pagar con QR">

                        <button class="btn btn-primary w-100 btnGenerarQR"
                            data-pago-id="{{ $pedido->pago->id ?? 0 }}"
                            data-pedido-id="{{ $pedido->id }}">
                            Generar QR con monto ingresado
                        </button>

                        <div id="qrContainer{{ $pedido->id }}" class="text-center mt-3"></div>
                        <div id="estadoPago{{ $pedido->id }}" class="text-center mt-2 fw-bold"></div>
                    </div>

                @endif

                <a href="{{ route('pedidos.estado', $pedido->id) }}" class="btn btn-outline-primary w-100 mt-3">
                    Ver estado del envío
                </a>

            </div>
        </div>
    @empty
        <p class="text-center text-muted">No tienes pedidos todavía.</p>
    @endforelse

    <div class="modal fade" id="modalPagoRealizado" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Pago realizado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="fs-5 text-center">
                        <strong id="textoPagoRealizado"></strong>
                    </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success w-100" data-bs-dismiss="modal">Aceptar</button>
                </div>

            </div>
        </div>
    </div>

@endsection
