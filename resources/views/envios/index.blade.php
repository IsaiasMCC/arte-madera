@extends('layouts.app')

@push('title', 'Envíos')

@section('content_header')
<h2> Envíos </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item active"> <a href="{{ route('pedidos.index') }}">Inicio</a> </li>
</ol>
@endsection

@push('styles')
<link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    toastr.options = { positionClass: "toast-top-right", timeOut: 2000, progressBar: true };

    @if (session('success'))
        toastr.success(@json(session('success')), 'Éxito');
    @endif

    $('.dataTables-example').DataTable({
        pageLength: 25,
        responsive: true,
    });
});
</script>
@endpush

@section('content')
<div class="row">
    {{-- <div class="col-lg-12 mb-3">
        <a href="{{ route('envios.create') }}" class="btn btn-primary">Agregar Envío</a>
    </div> --}}

    <div class="col-lg-12">
        <div class="ibox-content style-tema">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($envios as $envio)
                        <tr>
                            <td>{{ $envio->id }}</td>
                            <td>#{{ $envio->pedido->id }}</td>
                            <td>{{ $envio->pedido->user->nombres }} {{ $envio->pedido->user->apellidos }}</td>
                            <td>{{ $envio->direccion }}</td>
                            <td>{{ $envio->ciudad }}</td>
                            <td>
                                @php
                                    $colors = [
                                        'PENDIENTE' => 'warning',
                                        'PREPARANDO' => 'info',
                                        'EN_CAMINO' => 'primary',
                                        'ENTREGADO' => 'success',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colors[$envio->estado] ?? 'secondary' }}">{{ $envio->estado }}</span>
                            </td>
                            <td>
                                <a href="{{ route('envios.edit', $envio->id) }}" class="btn btn-warning btn-sm">Editar Estado</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
