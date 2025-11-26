@extends('layouts.tienda')

@section('title', 'Mis Pedidos')

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

                <!-- Envío -->
                <h6>Envío</h6>
                <p>
                    {{ $pedido->envio?->direccion ?? 'Sin dirección' }},
                    {{ $pedido->envio?->ciudad ?? '' }}
                    {{ $pedido->envio?->codigo_postal ?? '' }}
                </p>

                <!-- Pagos realizados -->
                <h6>Pagos</h6>
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

                <!-- Formulario para pago parcial (solo si es CREDITO o CONTADO con saldo pendiente) -->
                @if ($pedido->saldoPendiente() > 0)
                    @if ($pedido->pago && $pedido->pago->tipo_pago == 'CREDITO')
                        <form method="POST" action="{{ route('checkout.procesar_detalle', $pedido->pago->id) }}"
                            class="mt-3">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label for="monto" class="form-label">Monto a pagar</label>
                                    <input type="number" step="0.01" min="0.01"
                                        max="{{ $pedido->saldoPendiente() }}" class="form-control" id="monto"
                                        name="monto"
                                        placeholder="Hasta ${{ number_format($pedido->saldoPendiente(), 2) }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-wood w-100 mt-3">Realizar Pago</button>
                        </form>
                    @elseif($pedido->pago && $pedido->pago->tipo_pago == 'CONTADO')
                        <p class="text-warning mt-2 fw-bold">Este pedido es al contado. Realiza un solo pago por el total
                            pendiente: ${{ number_format($pedido->saldoPendiente(), 2) }}</p>
                        <form method="POST" action="{{ route('checkout.procesar_detalle', $pedido->pago->id) }}">
                            @csrf
                            <input type="hidden" name="monto" value="{{ $pedido->saldoPendiente() }}">
                            <button type="submit" class="btn btn-wood w-100 mt-2">Pagar Pedido Completo</button>
                        </form>
                    @else
                        <p class="text-warning mt-2 fw-bold">No hay registro de pago para este pedido. Contacta con soporte
                            para proceder con el pago: ${{ number_format($pedido->saldoPendiente(), 2) }}</p>
                    @endif
                @else
                    <p class="text-success mt-2 fw-bold">¡Pedido pagado en su totalidad!</p>
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
