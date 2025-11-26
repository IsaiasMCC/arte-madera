@extends('layouts.tienda')

@section('title', 'Estado del Pedido #'.$pedido->id)

@section('content')
<h2 class="text-center mb-4">Estado del Pedido #{{ $pedido->id }}</h2>

@php
    $estado = strtolower($pedido->envio->estado ?? 'pendiente');
    $steps = ['pendiente'=>1,'preparando'=>2,'en_camino'=>3,'entregado'=>4];
    $actual = $steps[$estado];
@endphp

<div class="container">
    <div class="progress" style="height: 25px;">
        <div class="progress-bar 
            {{ $estado == 'pendiente' ? 'bg-warning' : '' }}
            {{ $estado == 'preparando' ? 'bg-info' : '' }}
            {{ $estado == 'en_camino' ? 'bg-primary' : '' }}
            {{ $estado == 'entregado' ? 'bg-success' : '' }}"
            role="progressbar" style="width: {{ ($actual-1)*33 }}%" aria-valuenow="{{ $actual }}" aria-valuemin="1" aria-valuemax="4">
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <span class="{{ $actual >= 1 ? 'fw-bold text-warning' : 'text-muted' }}">Pendiente</span>
        <span class="{{ $actual >= 2 ? 'fw-bold text-info' : 'text-muted' }}">Preparando</span>
        <span class="{{ $actual >= 3 ? 'fw-bold text-primary' : 'text-muted' }}">En camino</span>
        <span class="{{ $actual >= 4 ? 'fw-bold text-success' : 'text-muted' }}">Entregado</span>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Dirección de entrega</h5>
            <p>
                {{ $pedido->envio->direccion ?? '' }}<br>
                {{ $pedido->envio->ciudad ?? '' }} - {{ $pedido->envio->codigo_postal ?? '' }}
            </p>

            <h5 class="mt-4">Estado actual</h5>
            <p class="fw-bold 
                {{ $estado == 'pendiente' ? 'text-warning' : '' }}
                {{ $estado == 'preparando' ? 'text-info' : '' }}
                {{ $estado == 'en_camino' ? 'text-primary' : '' }}
                {{ $estado == 'entregado' ? 'text-success' : '' }}">
                {{ ucfirst(str_replace('_',' ', $estado)) }}
            </p>
        </div>
    </div>
</div>
@endsection
