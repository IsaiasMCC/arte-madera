<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\Pedido;
use App\Models\Envio;
use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    // Reporte 1: Pedidos por fecha
    public function pedidosPorFecha(Request $request)
    {
        $query = Pedido::query();

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha', [$request->desde, $request->hasta]);
        }

        $pedidos = $query->orderBy('fecha', 'desc')->get();

        return view('reportes.pedidos', compact('pedidos'));
    }

    // Reporte 2: Envíos por estado y fecha
    public function enviosPorEstado(Request $request)
    {
        $query = Envio::with('pedido');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereHas('pedido', function ($q) use ($request) {
                $q->whereBetween('fecha', [$request->desde, $request->hasta]);
            });
        }

        $envios = $query->orderBy('id', 'desc')->get();

        return view('reportes.envios', compact('envios'));
    }

    // Reporte 3: Productos más vendidos
    public function productosMasVendidos(Request $request)
    {
        $query = DetallePedido::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_cantidad'),
            DB::raw('SUM(subtotal) as total_ventas')
        )->groupBy('producto_id');

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereHas('pedido', function ($q) use ($request) {
                $q->whereBetween('fecha', [$request->desde, $request->hasta]);
            });
        }

        $productos = $query->with('producto')->orderByDesc('total_cantidad')->get();

        return view('reportes.productos', compact('productos'));
    }

    // app/Http/Controllers/ReporteController.php
    public function ventas(Request $request)
    {
        $query = DetallePago::with('pago.pedido.user', 'pago.pedido.tienda');

        if ($request->filled('desde')) {
            $query->where('fecha', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->where('fecha', '<=', $request->hasta);
        }

        $detallePagos = $query->get();

        return view('reportes.ventas', compact('detallePagos'));
    }
}
