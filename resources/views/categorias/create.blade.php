@extends('layouts.app')

@push('title', 'Categorías')

@section('content_header')
<h2> Agregar Categoría </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href={{ route('categorias.index') }}> Inicio </a> </li>
    <li class="breadcrumb-item active"> <a href={{ route('categorias.create') }}> Agregar </a> </li>
</ol>
@endsection

@push('styles')
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        toastr.options = {
            positionClass: "toast-top-right",
            timeOut: 2000,
            progressBar: true,
        };

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
            <form method="POST" action="{{ route('categorias.store') }}">
                @csrf
                <div class="form-group">
                    <label for="nombre" class="input-label">Nombre </label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                        aria-describedby="categoria" name="nombre" value="{{ old('nombre') }}">
                    @error('nombre')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion" class="input-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                        name="descripcion" rows="3" value="{{ old('descripcion') }}"></textarea>
                    @error('descripcion')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <a href="{{ route('categorias.index') }}" type="button" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>
    </div>
</div>

@endsection
