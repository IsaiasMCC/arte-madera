@extends('layouts.tienda')

@section('title', 'Mis Pedidos')


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    // Mostrar/ocultar formulario según método de pago
    document.querySelectorAll('.selectorMetodoPago').forEach(select => {
        select.addEventListener('change', function() {
            const pedidoId = this.dataset.pedidoId;
            const metodo = this.value;
            document.getElementById("pagoManual" + pedidoId).style.display = (metodo === "manual") ? "block" : "none";
            document.getElementById("pagoQR" + pedidoId).style.display = (metodo === "pagofacil") ? "block" : "none";
        });
    });

    // PAGO MANUAL AJAX
    document.querySelectorAll('.btnPagarManual').forEach(btn => {
        btn.addEventListener('click', function() {
            const pagoId = this.dataset.pagoId;
            const montoInput = document.querySelector('.montoManualInput[data-pago-id="'+pagoId+'"]');
            const monto = parseFloat(montoInput.value);

            if(!monto || monto <= 0) { alert("Ingresa un monto válido"); return; }

            fetch("{{ url('/checkout/procesar_detalle') }}/" + pagoId, {  // ← CAMBIO
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
                if(data.success) {
                    alert(`Pago registrado: $${data.monto}. Saldo pendiente: $${data.saldo}`);
                    location.reload();
                } else {
                    alert("Error: " + (data.error || "No se pudo procesar"));
                }
            })
            .catch(err => { console.error(err); alert("Error de comunicación"); });
        });
    });

    // PAGO QR AJAX
    document.querySelectorAll('.btnGenerarQR').forEach(btn => {
        btn.addEventListener('click', function() {
            const pagoId = this.dataset.pagoId;
            const pedidoId = this.dataset.pedidoId;
            const qrContainer = document.getElementById("qrContainer" + pedidoId);
            const estadoPago = document.getElementById("estadoPago" + pedidoId);

            qrContainer.innerHTML = "Generando QR...";
            estadoPago.innerHTML = "";

            fetch("{{ url('/pagofacil/generar-qr') }}", {  // ← CAMBIO
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ pago_id: pagoId })
            })
            .then(res => {
                console.log("Status QR:", res.status);
                return res.json();
            })
            .then(data => {
                console.log("Respuesta QR COMPLETA:", JSON.stringify(data, null, 2));
                if (data.qr) {
                    qrContainer.innerHTML =
                        `<img src="${data.qr}" style="width:250px;">
                         <p class="mt-2">Transacción: ${data.transaccion}</p>
                         <button class="btn btn-success mt-3 btnVerificarQR" data-transaccion="${data.transaccion}" data-pedido-id="${pedidoId}">
                             Verificar Pago
                         </button>`;
                } else {
                    qrContainer.innerHTML = "Error: " + (data.error || "No se pudo generar el QR");
                }
            }).catch(err => {
                console.error("Error QR:", err);
                qrContainer.innerHTML = "Error de comunicación con el servidor";
            });
        });
    });

    // Verificar estado QR
    document.addEventListener('click', function(e){
        if(e.target && e.target.classList.contains('btnVerificarQR')) {
            const transaccion = e.target.dataset.transaccion;
            const pedidoId = e.target.dataset.pedidoId;
            const estadoPago = document.getElementById("estadoPago" + pedidoId);
            estadoPago.innerHTML = "Consultando...";

            fetch("{{ url('/pagofacil/consultar-estado') }}", {  // ← CAMBIO
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
                estadoPago.innerHTML = "Estado: " + data.estado;
                if(data.estado === "COMPLETADO" || data.estado === "PAGADO") {
                    location.reload();
                }
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
                <!-- Productos -->
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

                <!-- Pagos realizados -->
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
                    <!-- Selector método de pago -->
                    <div class="mt-3">
                        <label class="form-label fw-bold">Método de pago</label>
                        <select class="form-select selectorMetodoPago" data-pedido-id="{{ $pedido->id }}">
                            <option value="manual">Pago manual</option>
                            <option value="pagofacil">Pago QR - PagoFácil</option>
                        </select>
                    </div>

                    <!-- Pago manual -->
                    <div class="mt-3 pagoManual" id="pagoManual{{ $pedido->id }}">
                        <input type="number" step="0.01" min="0.01" max="{{ $pedido->saldoPendiente() }}"
                            class="form-control montoManualInput mb-2" data-pago-id="{{ $pedido->pago->id }}"
                            placeholder="Monto a pagar">
                        <button class="btn btn-wood w-100 btnPagarManual" data-pago-id="{{ $pedido->pago->id }}">
                            Pagar Manualmente
                        </button>
                    </div>

                    <!-- Pago QR -->
                    <div class="mt-3 pagoQR" id="pagoQR{{ $pedido->id }}" style="display:none;">
                        <button class="btn btn-primary w-100 btnGenerarQR" data-pago-id="{{ $pedido->pago->id }}"
                            data-pedido-id="{{ $pedido->id }}">
                            Pagar con QR PagoFácil
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
@endsection
