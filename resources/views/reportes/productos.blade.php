@extends('layouts.app')

@push('title', 'Productos más Vendidos')

@section('content_header')
<h2> Productos más Vendidos </h2>
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
    const titleReport = `Productos más Vendidos`;
    const fileName = `reporte-productos-${today}-${time}`;

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
    <form action="{{ route('reportes.productos') }}" method="GET" class="d-flex gap-2">
        <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
        <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
        <button type="submit" class="btn btn-warning">Filtrar</button>
    </form>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox-content style-tema">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Total Vendida</th>
                            <th>Total Ventas ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $p)
                        <tr>
                            <td>{{ $p->producto->nombre }}</td>
                            <td>{{ $p->total_cantidad }}</td>
                            <td>${{ number_format($p->total_ventas,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
