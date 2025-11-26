@extends('layouts.tienda')

@section('title', 'Realizar Pago')

@section('content')
<h1 class="mb-4 text-center" style="color:#8B5E3C;">Resumen de tu Pedido</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Productos</h5>
        <ul class="list-group list-group-flush">
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
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Dirección de envío</h5>
        <p>{{ $pedido->envio->direccion }}, {{ $pedido->envio->ciudad }} {{ $pedido->envio->codigo_postal ?? '' }}</p>
    </div>
</div>

<form method="POST" action="{{ route('checkout.procesar', $pedido->id) }}">
    @csrf
    {{-- <div class="mb-3">
        <label for="metodo_pago" class="form-label">Método de Pago</label>
        <select class="form-select" name="metodo_pago" id="metodo_pago" required>
            <option value="">Seleccione un método de pago</option>
            @foreach($metodos as $metodo)
                <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
            @endforeach
        </select>
    </div> --}}
    <div class="mb-3">
        <label for="forma_pago" class="form-label">Seleccione un Plan de Pago</label>
        <select class="form-select" name="forma_pago" id="forma_pago" required>
            <option value="" selected disabled>Seleccione un plan de pago</option>
            <option value="CONTADO">Contado</option>
            <option value="CREDITO">Credito</option>
        </select>
    </div>

    <button type="submit" class="btn btn-wood w-100">Realizar Pedido</button>
</form>
@endsection
