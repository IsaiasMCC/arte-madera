@extends('layouts.app')

@push('title', 'Productos')

@section('content_header')
    <h2> Productos </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"> <a href="{{ route('productos.index') }}"> Inicio</a> </li>
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

    @if (session('error'))
        toastr.error(@json(session('error')), 'Error');
    @endif

    const now = new Date();
    const today = now.toLocaleDateString('es-BO').replace(/\//g, '-');
    const time = now.toLocaleTimeString('es-BO', { hour: '2-digit', minute: '2-digit', hour12: false });
    const titleReport = `Reporte de Productos`;
    const fileName = `reporte-productos-${today}-${time}`;

    $('.dataTables-example').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'excel', title: titleReport, filename: fileName },
            { extend: 'pdf', title: titleReport, filename: fileName },
            {
                customize: function(win) {
                    $(win.document.body).addClass('white-bg').css('font-size', '10px');
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            }
        ]
    });

    $(document).on('click', '.deleteProductoBtn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: "¿Está seguro de eliminar este producto?",
            text: "Este cambio no se puede revertir",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#1AB394",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('productos') }}/${id}`,
                    type: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    success: function(response) {
                        Swal.fire({ title: "Eliminado", text: response.message, icon: "success" })
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        let res = JSON.parse(xhr.responseText);
                        toastr.error(res.message, 'Error');
                    }
                });
            }
        });
    });
});
</script>
@endpush

@section('content')
<div class="row">

    @can('productos create')
        <div class="ml-5 mb-2">
            <a href="{{ route('productos.create') }}" class="btn btn-clock btn-primary"> Agregar Producto </a>
        </div>
    @endcan

    <div class="col-lg-12">
        <div class="ibox-content style-tema">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            {{-- <th>Estado</th> --}}
                            <th>Categoría</th>
                            <th>Tienda</th>
                            @canany(['productos edit', 'productos delete'])
                                <th>Acción</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $p)
                        <tr data-id="{{ $p->id }}">
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->descripcion }}</td>
                            <td>{{ number_format($p->precio,2) }}</td>
                            <td>{{ $p->stock }}</td>
                            {{-- <td>
                                <span class="border text-center {{ $p->estado ? 'bg-primary' : 'bg-warning' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td> --}}
                            <td>{{ $p->categoria->nombre }}</td>
                            <td>{{ $p->tienda->nombre }}</td>

                            @canany(['productos edit', 'productos delete'])
                            <td class="text-center">
                                @can('productos edit')
                                    <a class="btn btn-info" href="{{ route('productos.edit', $p->id) }}"><i class="fa fa-pencil"></i></a>
                                @endcan
                                @can('productos delete')
                                    <button class="btn btn-danger deleteProductoBtn" data-id="{{ $p->id }}"><i class="fa fa-trash-o"></i></button>
                                @endcan
                            </td>
                            @endcanany
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
