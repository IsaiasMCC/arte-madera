@extends('layouts.app')

@push('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Tarjetas de estadísticas -->
    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Usuarios</h5>
                        <h2>{{ $countUsers }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-light" role="progressbar" style="width: 75%"></div>
                </div>
                <small>Hoy</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Pedidos</h5>
                        <h2>{{ $countPedidos }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart fa-3x"></i>
                    </div>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-light" role="progressbar" style="width: 60%"></div>
                </div>
                <small>Mes</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Envíos</h5>
                        <h2>{{ $countEnvios }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa fa-truck fa-3x"></i>
                    </div>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-light" role="progressbar" style="width: 50%"></div>
                </div>
                <small>Estado de envíos</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Pagos</h5>
                        <h2>${{ number_format($totalPagos, 2) }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar-sign fa-3x"></i>
                    </div>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-light" role="progressbar" style="width: 80%"></div>
                </div>
                <small>Hoy</small>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header">
                Ventas por Fecha
            </div>
            <div class="card-body">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header">
                Pedidos por Estado
            </div>
            <div class="card-body">
                <canvas id="pedidosChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-header">
                Productos más Vendidos
            </div>
            <div class="card-body">
                <canvas id="productosChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ventasCtx = document.getElementById('ventasChart').getContext('2d');
const ventasChart = new Chart(ventasCtx, {
    type: 'line',
    data: {
        labels: @json($ventasLabels),
        datasets: [{
            label: 'Ventas',
            data: @json($ventasData),
            backgroundColor: 'rgba(26,179,148,0.2)',
            borderColor: 'rgba(26,179,148,1)',
            borderWidth: 2,
            fill: true,
        }]
    }
});

const pedidosCtx = document.getElementById('pedidosChart').getContext('2d');
const pedidosChart = new Chart(pedidosCtx, {
    type: 'doughnut',
    data: {
        labels: @json($pedidosEstados),
        datasets: [{
            data: @json($pedidosData),
            backgroundColor: ['#f39c12','#00c0ef','#00a65a','#dd4b39'],
        }]
    }
});

const productosCtx = document.getElementById('productosChart').getContext('2d');
const productosChart = new Chart(productosCtx, {
    type: 'bar',
    data: {
        labels: @json($productosLabels),
        datasets: [{
            label: 'Cantidad Vendida',
            data: @json($productosData),
            backgroundColor: '#3c8dbc'
        }]
    }
});
</script>
@endpush
