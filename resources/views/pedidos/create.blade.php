@extends('layouts.app')

@push('title', 'Agregar Envío')

@section('content_header')
<h2> Agregar Envío </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href="{{ route('envios.index') }}"> Inicio </a> </li>
    <li class="breadcrumb-item active"> <a href="{{ route('envios.create') }}"> Agregar </a> </li>
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

            <form method="POST" action="{{ route('envios.store') }}">
                @csrf

                <div class="form-group">
                    <label for="pedido_id">Pedido</label>
                    <select class="form-control @error('pedido_id') is-invalid @enderror" name="pedido_id" id="pedido_id" required>
                        <option value="">Seleccionar Pedido</option>
                        @foreach($pedidos as $p)
                            <option value="{{ $p->id }}" {{ old('pedido_id') == $p->id ? 'selected' : '' }}>
                                Pedido #{{ $p->id }} - {{ $p->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pedido_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" 
                           name="direccion" value="{{ old('direccion') }}" required>
                    @error('direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" 
                           name="ciudad" value="{{ old('ciudad') }}" required>
                    @error('ciudad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="codigo_postal">Código Postal</label>
                    <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror" id="codigo_postal" 
                           name="codigo_postal" value="{{ old('codigo_postal') }}" required>
                    @error('codigo_postal') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="estado">Estado del Envío</label>
                    <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                        @php
                            $estados = ['pendiente' => 'Pendiente', 'preparando' => 'Preparando', 'en_camino' => 'En camino', 'entregado' => 'Entregado'];
                        @endphp
                        @foreach($estados as $key => $value)
                            <option value="{{ $key }}" {{ old('estado') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <a href="{{ route('envios.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>

        </div>
    </div>
</div>
@endsection
