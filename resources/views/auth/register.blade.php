@extends('layouts.tienda')

@section('title', 'Registro')

@section('content')
    <div class="container" style="max-width:500px;">
        <h2 class="text-center mb-4">Registro de Usuario</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="ci" class="form-label">Ci</label>
                <input type="text" name="ci" class="form-control" value="{{ old('ci') }}" required>
                @error('ci')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nombres" class="form-label">Nombres</label>
                <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" required>
                @error('nombres')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
                @error('apellidos')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-wood w-100">Registrarse</button>
        </form>
        <div class="text-center mt-2">
            <a href="{{ route('auth.index') }}">
                <p> Iniciar Sesión </p>
            </a>
        </div>
    </div>
@endsection
