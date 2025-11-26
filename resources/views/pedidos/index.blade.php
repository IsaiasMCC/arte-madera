@extends('layouts.app')

@push('title', 'Mis Pedidos')

@section('content_header')
    <h2> Mis Pedidos </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Inicio</li>
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
            const time = now.toLocaleTimeString('es-BO', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            const titleReport = `Reporte de Pedidos`;
            const fileName = `reporte-pedidos-${today}-${time}`;

            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [{
                        extend: 'excel',
                        title: titleReport,
                        filename: fileName
                    },
                    {
                        extend: 'pdf',
                        title: titleReport,
                        filename: fileName
                    },
                    {
                        customize: function(win) {
                            $(win.document.body).addClass('white-bg').css('font-size', '10px');
                            $(win.document.body).find('table').addClass('compact').css('font-size',
                                'inherit');
                        }
                    }
                ]
            });
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content style-tema">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Tienda</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $pedido)
                                <tr>
                                    <td>{{ $pedido->id }}</td>
                                    <td>{{ $pedido->fecha }}</td>
                                    <td>{{ $pedido->hora }}</td>
                                    <td>${{ number_format($pedido->total, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                    {{ $pedido->estado == 'CANCELADO' ? 'bg-warning' : '' }}
                                    {{ $pedido->estado == 'PENDIENTE' ? 'bg-info' : '' }}
                                    {{ $pedido->estado == 'FINALIZADO' ? 'bg-success' : '' }}">
                                            {{ ucfirst(strtolower($pedido->estado)) }}
                                        </span>
                                    </td>
                                    <td>{{ $pedido->tienda->nombre }}</td>
                                    <td>
                                        <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i> Actualizar Pedido
                                        </a>
                                        <a href="{{ route('envios.edit', $pedido->envio->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fa fa-truck"></i> Ver estado Envio
                                        </a>
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
