@extends('layouts.app')

@push('title', 'Envíos por Estado')

@section('content_header')
<h2> Envíos por Estado </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item active"> <a href="{{ route('reportes.index') }}"> Inicio</a> </li>
</ol>
@endsection

@push('styles')
<link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    const now = new Date();
    const today = now.toLocaleDateString('es-BO').replace(/\//g, '-');
    const time = now.toLocaleTimeString('es-BO', { hour: '2-digit', minute: '2-digit', hour12: false });
    const titleReport = `Reporte de Envíos`;
    const fileName = `reporte-envios-${today}-${time}`;

    $('.dataTables-example').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'excel', title: titleReport, filename: fileName },
            { extend: 'pdf', title: titleReport, filename: fileName }
        ]
    });
});
</script>
@endpush

@section('content')
<div class="row mb-3">
    <form action="{{ route('reportes.envios') }}" method="GET" class="d-flex gap-2">
        <select name="estado" class="form-control">
            <option value="">Todos</option>
            <option value="PENDIENTE" {{ request('estado')=='PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
            <option value="PREPARANDO" {{ request('estado')=='PREPARANDO' ? 'selected' : '' }}>Preparando</option>
            <option value="EN_CAMINO" {{ request('estado')=='EN_CAMINO' ? 'selected' : '' }}>En Camino</option>
            <option value="ENTREGADO" {{ request('estado')=='ENTREGADO' ? 'selected' : '' }}>Entregado</option>
        </select>
        <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
        <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
        <button type="submit" class="btn btn-success">Filtrar</button>
    </form>
</div>

<div class="row">
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
                            <th>Fecha Pedido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($envios as $e)
                        <tr>
                            <td>{{ $e->id }}</td>
                            <td>{{ $e->pedido->id }}</td>
                            <td>{{ $e->pedido->user->nombres ?? $e->pedido->user->name }}</td>
                            <td>{{ $e->direccion }}</td>
                            <td>{{ $e->ciudad }}</td>
                            <td>{{ $e->estado }}</td>
                            <td>{{ $e->pedido->fecha }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
