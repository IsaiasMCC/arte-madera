<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Envio;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {

  $countUsers = User::count();
    $countPedidos = Pedido::count();
    $countEnvios = Envio::count();
    $totalPagos = Pago::sum('monto');

    // Ventas por fecha
    $ventas = Pago::selectRaw('fecha, SUM(monto) as total')
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();
    $ventasLabels = $ventas->pluck('fecha');
    $ventasData = $ventas->pluck('total');

    // Pedidos por estado
    $pedidos = Pedido::selectRaw('estado, COUNT(*) as total')
        ->groupBy('estado')
        ->get();
    $pedidosEstados = $pedidos->pluck('estado');
    $pedidosData = $pedidos->pluck('total');

    // Productos más vendidos
    $productos = DetallePedido::selectRaw('producto_id, SUM(cantidad) as total')
        ->with('producto')
        ->groupBy('producto_id')
        ->orderByDesc('total')
        ->get();
    $productosLabels = $productos->map(fn($p) => $p->producto->nombre);
    $productosData = $productos->pluck('total');

    return view('home', compact(
        'countUsers','countPedidos','countEnvios','totalPagos',
        'ventasLabels','ventasData',
        'pedidosEstados','pedidosData',
        'productosLabels','productosData'
    ));
    }
}
