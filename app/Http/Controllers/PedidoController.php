<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Tienda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function seguimiento($id)
    {
        $pedido = Pedido::with('productos')->find($id);

        return view('pedidos.seguimiento', compact('pedido'));
    }

    public function misPedidos()
    {
        // Traer todos los pedidos del usuario autenticado
        $pedidos = Pedido::where('user_id', Auth::id())
            ->with('detalles.producto', 'envio', 'pago')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tienda-ecommerce.pedidos.index', compact('pedidos'));
    }

    public function estado($id)
    {
        $pedido = Pedido::with('envio')->findOrFail($id);

        return view('tienda-ecommerce.pedidos.estado', compact('pedido'));
    }

    // Lista de pedidos del usuario
    public function index()
    {
        $pedidos = Pedido::with('user', 'tienda', 'detalles.producto')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function edit($id)
    {
        $pedido = Pedido::with('detalles.producto')->findOrFail($id);
        $usuarios = User::orderBy('nombres')->get();
        $tiendas = Tienda::orderBy('nombre')->get();

        return view('pedidos.edit', compact('pedido', 'usuarios', 'tiendas'));
    }

    // Detalle del pedido
    public function show($id)
    {
        $pedido = Pedido::with('detalles.producto', 'envio', 'pago.detallePagos')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pedidos.show', compact('pedido'));
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);
        
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'total' => 'required|numeric|min:0',
            'estado' => 'required|in:PENDIENTE,FINALIZADO,CANCELADO',
            'user_id' => 'required|exists:users,id',
            'tienda_id' => 'required|exists:tiendas,id',
        ]);

        $pedido->update([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'total' => $request->total,
            'estado' => $request->estado,
            'user_id' => $request->user_id,
            'tienda_id' => $request->tienda_id,
        ]);

        return redirect()->route('pedidos.edit', $pedido->id)
            ->with('success', 'Pedido actualizado correctamente.');
    }

    /**
     * Eliminar pedido
     */
    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado correctamente.']);
    }
}
