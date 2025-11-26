@extends('layouts.app')

@push('title', 'Reportes')

@section('content_header')
<h2>Reportes</h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item active"> Reportes </li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <a href="{{ route('reportes.pedidos') }}" class="btn btn-primary btn-block">Pedidos por Fecha</a>
    </div>
    <div class="col-lg-4">
        <a href="{{ route('reportes.envios') }}" class="btn btn-success btn-block">Envíos por Estado</a>
    </div>
    <div class="col-lg-4">
        <a href="{{ route('reportes.productos') }}" class="btn btn-warning btn-block">Productos más Vendidos</a>
    </div>
</div>
@endsection
