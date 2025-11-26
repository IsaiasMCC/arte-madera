@extends('layouts.tienda')

@section('title', 'Estado del Pedido')

@section('content')

    <h2 class="text-center mb-4">Estado del Pedido #{{ $pedido->id }}</h2>

    @php
        $estado = $pedido->envio->estado ?? 'PENDIENTE';

        $steps = [
            'PENDIENTE' => 1,
            'PREPARANDO' => 2,
            'EN_CAMINO' => 3,
            'ENTREGADO' => 4,
        ];

        $actual = $steps[$estado];
    @endphp

    <div class="container">
        <div class="progress" style="height: 25px;">
            <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($actual - 1) * 33 }}%"
                aria-valuenow="{{ $actual }}" aria-valuemin="1" aria-valuemax="4">
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">

            <span
                class="badge rounded-pill px-3 py-2 
        {{ $actual >= 1 ? 'bg-secondary text-white' : 'bg-light text-muted border' }}">
                Pendiente
            </span>

            <span
                class="badge rounded-pill px-3 py-2 
        {{ $actual >= 2 ? 'bg-warning text-dark' : 'bg-light text-muted border' }}">
                Preparando
            </span>

            <span
                class="badge rounded-pill px-3 py-2 
        {{ $actual >= 3 ? 'bg-info text-dark' : 'bg-light text-muted border' }}">
                En camino
            </span>

            <span
                class="badge rounded-pill px-3 py-2 
        {{ $actual >= 4 ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                Entregado
            </span>

        </div>


        <div class="card mt-4">
            <div class="card-body">
                <h5>Dirección de entrega</h5>
                <p>
                    {{ $pedido->envio->direccion }}<br>
                    {{ $pedido->envio->ciudad }} - {{ $pedido->envio->codigo_postal }}
                </p>

                <h5 class="mt-4">Estado actual</h5>

                @php
                    $colores = [
                        'PENDIENTE' => 'secondary',
                        'PREPARANDO' => 'warning',
                        'EN_CAMINO' => 'info',
                        'ENTREGADO' => 'success',
                    ];
                @endphp

                <p class="fw-bold text-uppercase">
                    <span class="badge bg-{{ $colores[$estado] }} px-3 py-2">
                        {{ str_replace('_', ' ', $estado) }}
                    </span>
                </p>

            </div>
        </div>

        <!-- Detalles del Pedido -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Detalles del Pedido</h5>
            </div>

            <div class="card-body">
                <!-- Lista de productos -->
                <ul class="list-group list-group-flush mb-3">
                    @foreach ($pedido->detalles as $detalle)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $detalle->producto->nombre }}</strong>
                                <br>
                                <small>Cantidad: {{ $detalle->cantidad }}</small>
                            </div>
                            <span>$ {{ number_format($detalle->subtotal * $detalle->cantidad, 2) }}</span>
                        </li>
                    @endforeach

                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        Total del Pedido:
                        <span>$ {{ number_format($pedido->total, 2) }}</span>
                    </li>
                </ul>

                <!-- Información de envío si te sirve -->
                <h6>Envío</h6>
                <p class="mb-0">
                    {{ $pedido->envio->direccion }},
                    {{ $pedido->envio->ciudad }},
                    {{ $pedido->envio->codigo_postal }}
                </p>
            </div>
        </div>

    </div>

@endsection
