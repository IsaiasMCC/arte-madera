<!-- resources/views/tienda-ecommerce/envio/index.blade.php -->
@extends('layouts.tienda')

@section('title', 'Seguimiento de Pedido')

@section('content')
<h1 class="mb-4 text-center" style="color:#8B5E3C;">Seguimiento de tu Pedido</h1>

@if($pedido)
<div class="card p-3">
    <h5>Pedido #{{ $pedido->id }}</h5>
    <p><strong>Nombre:</strong> {{ $pedido->nombre }}</p>
    <p><strong>Estado:</strong> {{ $pedido->estado }}</p>
    <p><strong>Total:</strong> $ {{ number_format($pedido->total,2) }}</p>
    <p><strong>Enviado a:</strong> {{ $pedido->direccion }}</p>
</div>
@else
<p class="text-center">No se encontró el pedido.</p>
@endif
@endsection
