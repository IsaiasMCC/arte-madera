@extends('layouts.app')

@push('title', 'Métodos de Pago')

@section('content_header')
<h2> Métodos de Pago </h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item active"> <a href="{{ route('metodos_pago.index') }}"> Inicio</a> </li>
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
    const titleReport = `Reporte de Métodos de Pago`;
    const fileName = `reporte-metodos-pago-${today}-${time}`;

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

    $(document).on('click', '.deleteMetodoBtn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: "¿Está seguro de eliminar este método de pago?",
            text: "Este cambio no se puede revertir",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#1AB394",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('metodos_pago') }}/${id}`,
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

    @can('metodos_pago create')
        <div class="ml-5 mb-2">
            <a href="{{ route('metodos_pago.create') }}" class="btn btn-clock btn-primary"> Agregar Método de Pago </a>
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
                            @canany(['metodos_pago edit', 'metodos_pago delete'])
                                <th>Acción</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($metodos as $m)
                        <tr data-id="{{ $m->id }}">
                            <td>{{ $m->id }}</td>
                            <td>{{ $m->nombre }}</td>
                            <td>{{ $m->descripcion }}</td>
                            @canany(['metodos_pago edit', 'metodos_pago delete'])
                            <td class="text-center">
                                @can('metodos_pago edit')
                                    <a class="btn btn-info" href="{{ route('metodos_pago.edit', $m->id) }}"><i class="fa fa-pencil"></i></a>
                                @endcan
                                @can('metodos_pago delete')
                                    <button class="btn btn-danger deleteMetodoBtn" data-id="{{ $m->id }}"><i class="fa fa-trash-o"></i></button>
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
