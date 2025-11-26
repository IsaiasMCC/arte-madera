@extends('layouts.app')

@push('title', 'Usuarios')

@section('content_header')
    <h2> Editar Usuario </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href={{ route('usuarios.index') }}> Inicio </a> </li>
        <li class="breadcrumb-item active"> <a href='#'> Editar </a> </li>
    </ol>
@endsection

@push('styles')
    <link href="{{ asset('css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.single-select').select2();
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
            <form method="POST" action="{{ route('usuarios.update', $user->id) }}">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="ci" class="input-label">Ci </label>
                    <input type="text" class="form-control @error('ci') is-invalid @enderror" id="ci"
                        aria-describedby="ci" name="ci" value="{{ $user->ci }}">
                    @error('ci')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name" class="input-label">Nombre </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        aria-describedby="name" name="name" value="{{ $user->nombres }}">
                    @error('name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="lastname" class="input-label">Apellido </label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname"
                        aria-describedby="lastname" name="lastname" value="{{ $user->apellidos }}">
                    @error('lastname')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="input-label">Correo </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        aria-describedby="email" name="email" value="{{ $user->email }}">
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password" class="input-label">Contraseña </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        aria-describedby="password" name="password">
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="custom-select single-select">
                        <option value="1" {{ $user->estado == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ $user->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" id="role"
                        class="custom-select single-select @error('role') is-invalid @enderror">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role', $user->rol_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('rol_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <a href="{{ route('usuarios.index') }}" type="button" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@endsection
