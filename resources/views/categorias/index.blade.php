@extends('layouts.app')

@push('title', 'Categorías')

@section('content_header')
    <h2> Categorías </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"> <a href="{{ route('categorias.index') }}"> Inicio</a> </li>
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

            const titleReport = `Reporte de Categorías`;
            const fileName = `reporte-categorias-${today}-${time}`;

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
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ]
            });

            // ELIMINAR CATEGORÍA
            $(document).on('click', '.deleteCategoriaBtn', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: "¿Está seguro de eliminar esta categoría?",
                    text: "Este cambio no se puede revertir",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1AB394",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('categorias') }}/${id}`,
                            type: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Eliminado",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => location.reload());
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

        @can('categorias create')
            <div class="ml-5 mb-2">
                <a href="{{ route('categorias.create') }}" class="btn btn-clock btn-primary">
                    Agregar Categoría
                </a>
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
                                {{-- <th>Estado</th> --}}

                                @canany(['categorias edit', 'categorias delete'])
                                    <th>Acción</th>
                                @endcanany
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categorias as $cat)
                                <tr class="gradeX" data-id="{{ $cat->id }}">
                                    <td>{{ $cat->id }}</td>
                                    <td>{{ $cat->nombre }}</td>
                                    <td>{{ $cat->descripcion }}</td>

                                    {{-- <td>
                                        <p class="border text-center {{ $cat->is_active ? 'bg-primary' : 'bg-warning' }}">
                                            {{ $cat->is_active ? 'Activo' : 'Inactivo' }}
                                        </p>
                                    </td> --}}

                                    @canany(['categorias edit', 'categorias delete'])
                                        <td class="text-center">

                                            @can('categorias edit')
                                                <a class="btn btn-info" href="{{ route('categorias.edit', $cat->id) }}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('categorias delete')
                                                <button class="btn btn-danger deleteCategoriaBtn"
                                                    data-id="{{ $cat->id }}">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
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
