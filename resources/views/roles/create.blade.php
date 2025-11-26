@extends('layouts.app')

@push('title', 'Roles')

@section('content_header')
<h2> Agregar Rol </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href={{ route('roles.index') }}> Inicio </a> </li>
    <li class="breadcrumb-item active"> <a href={{ route('roles.create') }}> Agregar </a> </li>
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
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name" class="input-label">Nombre </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        aria-describedby="rol" name="name" value="{{ old('name') }}">
                    @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="input-label">Descripción</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="3" value="{{ old('description') }}"></textarea>
                    @error('description')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <a href="{{ route('roles.index') }}" type="button" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>
    </div>
</div>

@endsection