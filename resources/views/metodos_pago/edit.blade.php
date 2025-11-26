@extends('layouts.app')

@push('title', 'Métodos de Pago')

@section('content_header')
<h2> Editar Método de Pago </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href="{{ route('metodos_pago.index') }}"> Inicio </a> </li>
    <li class="breadcrumb-item active"> <a href="{{ route('metodos_pago.edit', $metodo->id) }}"> Editar </a> </li>
</ol>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    toastr.options = { positionClass: "toast-top-right", timeOut: 2000, progressBar: true };
    @if ($errors->any()) toastr.warning("Validaciones Incorrectas", 'Warning'); @endif
    @if (session('error')) toastr.error(@json(session('error')), 'Error'); @endif
});
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox-content style-tema">

            <form method="POST" action="{{ route('metodos_pago.update', $metodo->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                        name="nombre" value="{{ old('nombre', $metodo->nombre) }}">
                    @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                        name="descripcion" rows="3">{{ old('descripcion', $metodo->descripcion) }}</textarea>
                    @error('descripcion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <a href="{{ route('metodos_pago.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>

        </div>
    </div>
</div>
@endsection
