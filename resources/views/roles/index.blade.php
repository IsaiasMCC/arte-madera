@extends('layouts.app')

@push('title', 'Roles')

@section('content_header')
    <h2> Roles </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"> <a href={{ route('roles.index') }}> Inicio</a> </li>
    </ol>
@endsection

@push('styles')
    {{-- TABLA --}}
    <link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    {{-- TABLA --}}
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>
    {{-- MODAL DE PREGUNTA --}}
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                positionClass: "toast-top-right",
                timeOut: 2000,
                progressBar: true,
            };

            @if (session('success'))
                toastr.success(@json(session('success')), 'Éxito');
            @endif

            @if (session('update'))
                toastr.success(@json(session('update')), 'Éxito');
            @endif

            @if (session('error'))
                toastr.error(@json(session('error')), 'Error');
            @endif

            const now = new Date();
            const today = now.toLocaleDateString('es-BO').replace(/\//g, '-');
            const time = now.toLocaleTimeString('es-BO', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            const titleReport = `Reporte de Roles`;
            const fileName = `reporte-roles-${today}-${time}`;
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
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ]

            });

            $(document).on('click', '.deleteRoleBtn', function() {
                let roleId = $(this).data('id');
                Swal.fire({
                    title: "Esta seguro de borrar este rol?",
                    text: "Este cambio no se puede revertir",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1AB394",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('roles') }}/${roleId}`,
                            type: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Eliminado!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
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

        @can('roles create')
            <div class="ml-5 mb-2">
                <a href="{{ route('roles.create') }}" type="button" class="btn btn-clock btn-primary"> Agregar Rol </a>
            </div>
        @endcan
        <div class="col-lg-12">
            <div class="ibox-content style-tema">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead class="">
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                @canany(['roles edit', 'roles delete'])
                                    <th>Acción</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($roles as $role)
                                <tr class="gradeX" data-id="{{ $role->id }}">
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <p class="border text-center {{ $role->is_active ? 'bg-primary' : 'bg-warning' }}">
                                            {{ $role->is_active ? 'Activo' : 'Inactivo' }}
                                        </p>
                                    </td>
                                    @canany(['roles permisos', 'roles edit', 'roles delete'])
                                        <td class="text-center">
                                            @can('roles permisos')
                                                <a class="btn btn-success" href="{{ route('roles.show', $role->id) }}"> <i
                                                        class="fa fa-unlock-alt" aria-hidden="true"></i> </a>
                                            @endcan

                                            @can('roles edit')
                                                <a class="btn btn-info" href="{{ route('roles.edit', $role->id) }}"> <i
                                                        class="fa fa-pencil" aria-hidden="true"></i> </a>
                                            @endcan

                                            @can('roles delete')
                                                <button class="btn btn-danger deleteRoleBtn" data-id="{{ $role->id }}"> <i
                                                        class="fa fa-trash-o" aria-hidden="true"></i> </button>
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
