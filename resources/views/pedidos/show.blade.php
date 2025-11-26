@extends('layouts.tienda')

@section('title', 'Detalle Pedido #'.$pedido->id)

@section('content')
<h1 class="mb-4 text-center" style="color:#8B5E3C;">Detalle Pedido #{{ $pedido->id }}</h1>

<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Pedido #{{ $pedido->id }} - {{ $pedido->fecha }} {{ $pedido->hora }}</span>
        <span class="badge bg-{{ $pedido->saldoPendiente() == 0 ? 'success' : 'warning' }}">
            {{ $pedido->saldoPendiente() == 0 ? 'Pagado' : 'Pendiente' }}
        </span>
    </div>
    <div class="card-body">
        <!-- Productos -->
        <h5>Productos</h5>
        <ul class="list-group list-group-flush mb-3">
            @foreach($pedido->detalles as $detalle)
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
            {{ $pedido->envio?->ciudad ?? '' }} {{ $pedido->envio?->codigo_postal ?? '' }}
        </p>

        <!-- Pagos -->
        <h6>Pagos</h6>
        @if($pedido->pago && $pedido->pago->detallePagos->count() > 0)
            <ul class="list-group mb-3">
                @foreach($pedido->pago->detallePagos as $dp)
                    <li class="list-group-item d-flex justify-content-between">
                        {{ $dp->fecha }} {{ $dp->hora }}: $ {{ number_format($dp->monto,2) }}
                        <span>Saldo restante: $ {{ number_format($dp->saldo,2) }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No se han realizado pagos aún.</p>
        @endif
    </div>
</div>
@endsection
