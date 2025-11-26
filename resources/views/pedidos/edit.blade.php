@extends('layouts.app')

@push('title', 'Editar Pedido #'.$pedido->id)

@section('content_header')
<h2> Editar Pedido </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href="{{ route('pedidos.index') }}"> Inicio </a> </li>
    <li class="breadcrumb-item active"> Editar Pedido #{{ $pedido->id }} </li>
</ol>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    toastr.options = { positionClass: "toast-top-right", timeOut: 2000, progressBar: true };

    @if ($errors->any())
        toastr.warning("Validaciones Incorrectas", 'Warning');
    @endif
    @if (session('error'))
        toastr.error(@json(session('error')), 'Error');
    @endif
});
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox-content style-tema">

            <form method="POST" action="{{ route('pedidos.update', $pedido->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $pedido->fecha) }}">
                    @error('fecha') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="hora">Hora</label>
                    <input type="time" class="form-control @error('hora') is-invalid @enderror" id="hora" name="hora" value="{{ old('hora', $pedido->hora) }}">
                    @error('hora') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="number" step="0.01" class="form-control @error('total') is-invalid @enderror" id="total" name="total" value="{{ old('total', $pedido->total) }}">
                    @error('total') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado">
                        <option value="PENDIENTE" {{ old('estado', $pedido->estado) == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                        <option value="FINALIZADO" {{ old('estado', $pedido->estado) == 'FINALIZADO' ? 'selected' : '' }}>Finalizado</option>
                        <option value="CANCELADO" {{ old('estado', $pedido->estado) == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('estado') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="user_id">Cliente</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                        @foreach($usuarios as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $pedido->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->nombres }} {{ $user->apellidos }}  - ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="tienda_id">Tienda</label>
                    <select class="form-control @error('tienda_id') is-invalid @enderror" id="tienda_id" name="tienda_id">
                        @foreach($tiendas as $t)
                            <option value="{{ $t->id }}" {{ old('tienda_id', $pedido->tienda_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tienda_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <!-- Detalles del pedido -->
                <h5 class="mt-4">Productos del pedido</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedido->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>${{ number_format($detalle->subtotal * $detalle->cantidad,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <a href="{{ route('pedidos.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Pedido</button>
            </form>

        </div>
    </div>
</div>
@endsection
