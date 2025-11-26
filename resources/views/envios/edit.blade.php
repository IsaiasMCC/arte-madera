@extends('layouts.app')

@push('title', 'Editar Envío del Pedido #'.$envio->pedido->id)

@section('content_header')
<h2> Editar Envío </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href="{{ route('pedidos.index') }}"> Pedidos </a> </li>
    <li class="breadcrumb-item active"> Editar Envío del Pedido #{{ $envio->pedido->id }} </li>
</ol>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    toastr.options = { positionClass: "toast-top-right", timeOut: 2000, progressBar: true };

    @if ($errors->any())
        toastr.warning("Validaciones Incorrectas", 'Warning');
    @endif
    @if (session('success'))
        toastr.success(@json(session('success')), 'Éxito');
    @endif
});
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="ibox-content style-tema">

            <form method="POST" action="{{ route('envios.update', $envio->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $envio->direccion) }}">
                    @error('direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $envio->ciudad) }}">
                    @error('ciudad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="codigo_postal">Código Postal</label>
                    <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $envio->codigo_postal) }}">
                    @error('codigo_postal') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="estado">Estado de Envío</label>
                    <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado">
                        @foreach($estados as $key => $label)
                            <option value="{{ $key }}" {{ old('estado', $envio->estado) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <a href="{{ route('envios.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Envío</button>

            </form>
        </div>
    </div>
</div>
@endsection
