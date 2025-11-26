@extends('layouts.app')

@push('title', 'Pedidos por Fecha')

@section('content_header')
<h2> Pedidos por Fecha </h2>
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
<script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(document).ready(function() {
    const now = new Date();
    const today = now.toLocaleDateString('es-BO').replace(/\//g, '-');
    const time = now.toLocaleTimeString('es-BO', { hour: '2-digit', minute: '2-digit', hour12: false });
    const titleReport = `Reporte de Pedidos`;
    const fileName = `reporte-pedidos-${today}-${time}`;

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
    <form action="{{ route('reportes.pedidos') }}" method="GET" class="d-flex gap-2">
        <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
        <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
        <button type="submit" class="btn btn-primary">Filtrar</button>
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
                            <th>Cliente</th>
                            <th>Tienda</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->user->nombres ?? $p->user->name }}</td>
                            <td>{{ $p->tienda->nombre }}</td>
                            <td>{{ $p->fecha }}</td>
                            <td>{{ $p->hora }}</td>
                            <td>${{ number_format($p->total,2) }}</td>
                            <td>{{ $p->estado }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
