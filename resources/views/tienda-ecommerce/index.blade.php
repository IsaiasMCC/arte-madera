@extends('layouts.tienda')

@section('title', 'Tienda de Arte en Madera')

@section('content')
<h1 class="mb-4 text-center" style="color:#8B5E3C;">Nuestros Productos</h1>

<!-- FILTROS -->
<div class="d-flex justify-content-center mb-4 flex-wrap">
    <a href="{{ route('tiendas.tienda') }}" class="btn btn-outline-wood mx-1 {{ !$categoria_id ? 'active' : '' }}">Todas</a>
    @foreach($categorias as $cat)
        <a href="{{ route('tiendas.tienda', ['categoria' => $cat->id]) }}" class="btn btn-outline-wood mx-1 {{ $categoria_id == $cat->id ? 'active' : '' }}">
            {{ $cat->nombre }}
        </a>
    @endforeach
</div>

<!-- BUSCADOR -->
<div class="row mb-4">
    <div class="col-md-6 offset-md-3">
        <form method="GET" action="{{ route('tiendas.tienda') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar producto..." value="{{ request('search') }}">
                <button class="btn btn-wood">Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- PRODUCTOS -->
<div class="row g-4">
    @forelse($productos as $producto)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <img src="{{ asset('storage/' . $producto->foto) }}" class="card-img-top" alt="{{ $producto->nombre }}" style="height: 250px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($producto->descripcion, 80) }}</p>
                    <h6 class="fw-bold mb-3">$ {{ number_format($producto->precio, 2) }}</h6>
                    <form method="POST" action="{{ route('carrito.agregar', $producto->id) }}">
                        @csrf
                        <button class="btn btn-wood w-100">Agregar al carrito</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <p class="text-center text-muted">No hay productos disponibles.</p>
        </div>
    @endforelse
</div>

<style>
.btn-outline-wood {
    border: 2px solid #8B5E3C;
    color: #8B5E3C;
}
.btn-outline-wood.active,
.btn-outline-wood:hover {
    background-color: #8B5E3C;
    color: #fff;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    transition: 0.3s;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
</style>
@endsection
