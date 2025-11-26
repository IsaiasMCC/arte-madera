@extends('layouts.app')

@push('title', 'Roles')

@section('content_header')
    <h2> Permisos del Rol: {{ $role->name }} </h2>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('roles.index') }}"> Inicio </a> </li>
        <li class="breadcrumb-item active"> Asignar Permisos </li>
    </ol>
@endsection

@push('styles')
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">{{-- no borrar --}}
@endpush

@push('scripts')

    <script>
        $(document).ready(function() {
            toastr.options = {
                positionClass: "toast-top-right",
                timeOut: 2000,
                progressBar: true,
            };

            $('#checkAll').on('change', function() {
                $('input[name="permissions[]"]').prop('checked', this.checked);
            });

            @if ($errors->any())
                toastr.warning("Validaciones Incorrectas", 'Warning');
            @endif

            @if (session('error'))
                toastr.error(@json(session('error')), 'Error');
            @endif
            @if (session('success'))
                toastr.success(@json(session('success')), 'Exito');
            @endif
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content style-tema">
                <form method="POST" action="{{ route('roles.update.permissions', $role->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-check mb-3">
                        <input type="checkbox" id="checkAll" class="form-check-input">
                        <label for="checkAll" class="form-check-label">Seleccionar todos</label>
                    </div>
                    <div class="row">

                        @foreach ($permissions->chunk(ceil($permissions->count() / 4)) as $permissionChunk)
                            <div class="col-md-3 mb-3">
                                @foreach ($permissionChunk as $permission)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="{{ $permission->name }}"
                                            @if ($role->hasPermissionTo($permission->name)) checked @endif>
                                        <label class="form-check-label">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-danger">Volver</a>
                        <button type="submit" class="btn btn-primary">Asignar Permisos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
