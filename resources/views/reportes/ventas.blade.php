@extends('layouts.app')

@push('title', 'Reporte de Ventas')

@section('content_header')
<h2> Ventas por Fecha </h2>
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
    const titleReport = `Reporte de Ventas`;
    const fileName = `reporte-ventas-${today}-${time}`;

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
    <form action="{{ route('reportes.ventas') }}" method="GET" class="d-flex gap-2">
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
                            <th>ID Pago</th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Tienda</th>
                            <th>Fecha Pago</th>
                            <th>Hora</th>
                            <th>Monto ($)</th>
                            <th>Saldo ($)</th>
                            <th>Tipo de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detallePagos as $dp)
                        <tr>
                            <td>{{ $dp->pago->id }}</td>
                            <td>{{ $dp->pago->pedido->id }}</td>
                            <td>{{ $dp->pago->pedido->user->nombres ?? $dp->pago->pedido->user->name }}</td>
                            <td>{{ $dp->pago->pedido->tienda->nombre }}</td>
                            <td>{{ $dp->fecha }}</td>
                            <td>{{ $dp->hora }}</td>
                            <td>${{ number_format($dp->monto,2) }}</td>
                            <td>${{ number_format($dp->saldo,2) }}</td>
                            <td>{{ $dp->pago->tipo_pago }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
