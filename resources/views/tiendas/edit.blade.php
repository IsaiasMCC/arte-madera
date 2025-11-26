@extends('layouts.app')

@push('title', 'Tiendas')

@section('content_header')
<h2> Editar Tienda </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href="{{ route('tiendas.index') }}"> Inicio </a> </li>
    <li class="breadcrumb-item active"> <a href="{{ route('tiendas.edit', $tienda->id) }}"> Editar </a> </li>
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

            <form method="POST" action="{{ route('tiendas.update', $tienda->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre" class="input-label">Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                        name="nombre" value="{{ old('nombre', $tienda->nombre) }}">
                    @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="nit" class="input-label">NIT</label>
                    <input type="text" class="form-control @error('nit') is-invalid @enderror" id="nit"
                        name="nit" value="{{ old('nit', $tienda->nit) }}">
                    @error('nit') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="telefono" class="input-label">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono"
                        name="telefono" value="{{ old('telefono', $tienda->telefono) }}">
                    @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="ubicacion" class="input-label">Ubicación</label>
                    <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" id="ubicacion"
                        name="ubicacion" value="{{ old('ubicacion', $tienda->ubicacion) }}">
                    @error('ubicacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <a href="{{ route('tiendas.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>

        </div>
    </div>
</div>
@endsection
