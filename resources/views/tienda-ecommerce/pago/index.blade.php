@extends('layouts.tienda')

@section('title', 'Checkout')

@section('content')
<h1 class="mb-4 text-center" style="color:#8B5E3C;">Dirección de Envio</h1>

<form method="POST" action="{{ route('checkout.guardarEnvio') }}">
    @csrf

    <!-- Dirección -->
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
        @error('direccion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Ciudad -->
    <div class="mb-3">
        <label for="ciudad" class="form-label">Ciudad</label>
        <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required>
        @error('ciudad')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Código Postal -->
    <div class="mb-3">
        <label for="codigo_postal" class="form-label">Código Postal</label>
        <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}">
        @error('codigo_postal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <button type="submit" class="btn btn-wood w-100">Realizar Pedido</button>
</form>
@endsection
