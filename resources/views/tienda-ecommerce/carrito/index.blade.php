<!-- resources/views/tienda-ecommerce/carrito/index.blade.php -->
@extends('layouts.tienda')

@section('title', 'Carrito')

@section('content')
<h1 class="mb-4 text-center" style="color:#8B5E3C;">Tu Carrito</h1>

@if(session('cart') && count(session('cart')) > 0)
<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach(session('cart') as $id => $item)
            @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
            <tr>
                <td>{{ $item['nombre'] }}</td>
                <td>Bs {{ number_format($item['precio'],2) }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td>Bs {{ number_format($subtotal,2) }}</td>
                <td>
                    <form method="POST" action="{{ route('carrito.remover', $id) }}">
                        @csrf
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-end fw-bold">Total:</td>
            <td colspan="2" class="fw-bold">Bs {{ number_format($total,2) }}</td>
        </tr>
    </tbody>
</table>

<a href="{{ route('checkout.index') }}" class="btn btn-wood w-100 mt-3">Proceder al Pedido</a>
@else
<p class="text-center">Tu carrito está vacío.</p>
@endif
@endsection
