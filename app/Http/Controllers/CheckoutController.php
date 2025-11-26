<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\Envio;
use App\Models\MetodoPago;
use App\Models\Pago;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $metodos = MetodoPago::all();
        return view('tienda-ecommerce.pago.index', compact('metodos'));
    }

    public function pagar(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('tienda.index')->with('error', 'El carrito está vacío.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        $fecha = Carbon::now()->format('Y-m-d');
        $hora = Carbon::now()->format('H:i:s');

        $pedido = Pedido::create([
            'fecha' => $fecha,
            'hora' => $hora,
            'total' => $total,
            'user_id' => Auth::id(),
            'tienda_id' => $cart[array_key_first($cart)]['producto_id'], // O el id de la tienda correspondiente
        ]);

        foreach ($cart as $id => $item) {
            $pedido->productos()->attach($id, ['cantidad' => $item['cantidad']]);
        }

        session()->forget('cart');

        return redirect()->route('pedido.seguimiento', $pedido->id)
            ->with('success', 'Pedido realizado exitosamente.');
    }

    public function guardarEnvio(Request $request)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ]);


        $pedido = Pedido::create([
            'fecha' => Carbon::now()->format('Y-m-d'),
            'hora' => Carbon::now()->format('H:i:s'),
            'total' => 0, // Se actualizará después
            'user_id' => Auth::id(),
            'tienda_id' => 1,
        ]);

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $id => $item) {
            // crear un detalle por cada producto
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $id,
                'cantidad' => $item['cantidad'],
                'subtotal' => $item['precio'],
            ]);
            $total += $item['precio'] * $item['cantidad'];
        }
        $pedido->total = $total;
        $pedido->save();

        Envio::create([
            'direccion' => $request->input('direccion'),
            'ciudad' => $request->input('ciudad'),
            'codigo_postal' => $request->input('codigo_postal'),
            'pedido_id' => $pedido->id,
        ]);
        // Redirigir a la página de pago real (por ejemplo, PayPal, Stripe o Cash)
        return redirect()->route('checkout.pagar', ['pedido' => $pedido->id]);
    }

    public function mostrarPago(Pedido $pedido)
    {
        // Traemos los métodos de pago disponibles
        $metodos = MetodoPago::all();

        // Cargar los productos del pedido
        $pedido->load('detalles.producto', 'envio');

        return view('tienda-ecommerce.pago.realizar', compact('pedido', 'metodos'));
    }

    public function procesarPago(Request $request, Pedido $pedido)
    {
        $request->validate([
            // 'metodo_pago' => 'required|exists:metodo_pagos,id',
            'forma_pago' => 'required|string',
        ]);

        $pago = Pago::create([
            'fecha' => Carbon::now()->format('Y-m-d'),
            'hora' => Carbon::now()->format('H:i:s'),
            'monto' => $pedido->total,
            'pedido_id' => $pedido->id,
            'tipo_pago' => $request->forma_pago,
        ]);

        return redirect()->route('pedidos.mios')
            ->with('success', 'Pago registrado correctamente. Tu pedido está confirmado.');
    }

    public function procesarPagoDetalle(Request $request, Pago $pago)
    {
        try {
            $pedido = $pago->pedido;

            $request->validate([
                'monto' => 'required|numeric|min:0.01|max:' . $pedido->saldoPendiente(),
            ]);

            $detalle = DetallePago::create([
                'pago_id' => $pago->id,
                'fecha' => Carbon::now()->format('Y-m-d'),
                'hora' => Carbon::now()->format('H:i:s'),
                'monto' => $request->monto,
                'saldo' => $pedido->saldoPendiente() - $request->monto
            ]);

            $pago->monto = $pago->detallePagos()->sum('monto');
            $pago->save();

            // Siempre devuelve JSON
            return response()->json([
                'success' => true,
                'monto' => $detalle->monto,
                'saldo' => $detalle->saldo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
