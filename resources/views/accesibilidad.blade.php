@extends('layouts.app')

@push('title', 'Inicio')

@section('content_header', 'Accesibilidad')

@section('breadcrum')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Home</a>
    </li>
    <li class="breadcrumb-item active">
        <strong>Accesibilidad</strong>
    </li>
@endsection
@push('styles')

@endpush

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('fontSizeSelector');
            if (!selector) return;

            const saved = localStorage.getItem('fontSize');
            if (saved) {
                document.documentElement.style.fontSize = saved;
                selector.value = saved;
            }

            selector.addEventListener('change', function() {
                document.documentElement.style.fontSize = this.value;
                localStorage.setItem('fontSize', this.value);
            });
        });
    </script>
@endpush


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content style-tema">
                <div class="row">
                    <div class="col-md-4">
                        <select id="fontSizeSelector" class="form-control w-auto">
                            <option value="12px">Pequeño</option>
                            <option value="16px" selected>Normal</option>
                            <option value="20px">Grande</option>
                            <option value="25px">Muy grande</option>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
