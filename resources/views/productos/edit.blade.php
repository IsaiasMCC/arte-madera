@extends('layouts.app')

@push('title', 'Productos')

@section('content_header')
<h2> Editar Producto </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"> <a href="{{ route('productos.index') }}"> Inicio </a> </li>
    <li class="breadcrumb-item active"> <a href="{{ route('productos.edit', $producto->id) }}"> Editar </a> </li>
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

            <form method="POST" action="{{ route('productos.update', $producto->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                        name="nombre" value="{{ old('nombre', $producto->nombre) }}">
                    @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                        name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    @error('descripcion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio"
                        name="precio" value="{{ old('precio', $producto->precio) }}">
                    @error('precio') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock"
                        name="stock" value="{{ old('stock', $producto->stock) }}">
                    @error('stock') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="foto">Foto</label>
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto"
                        name="foto">
                    @if($producto->foto)
                        <img src="{{ asset('storage/'.$producto->foto) }}" alt="Foto Producto" class="img-thumbnail mt-2" width="150">
                    @endif
                    @error('foto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoría</label>
                    <select class="form-control @error('categoria_id') is-invalid @enderror" name="categoria_id">
                        <option value="">Seleccionar</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}" {{ old('categoria_id', $producto->categoria_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="tienda_id">Tienda</label>
                    <select class="form-control @error('tienda_id') is-invalid @enderror" name="tienda_id">
                        <option value="">Seleccionar</option>
                        @foreach($tiendas as $t)
                            <option value="{{ $t->id }}" {{ old('tienda_id', $producto->tienda_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tienda_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="estado" name="estado" value="1" {{ old('estado', $producto->estado) ? 'checked' : '' }}>
                    <label class="form-check-label" for="estado">Activo</label>
                </div> --}}

                <a href="{{ route('productos.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>

        </div>
    </div>
</div>
@endsection
