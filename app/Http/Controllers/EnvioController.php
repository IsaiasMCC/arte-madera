<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::with('pedido.user')->get();
        return view('envios.index', compact('envios'));
    }

    public function edit($id)
    {
        $envio = Envio::with('pedido')->findOrFail($id);

        $estados = [
            'PENDIENTE' => 'Pendiente',
            'PREPARANDO' => 'Preparando',
            'EN_CAMINO' => 'En camino',
            'ENTREGADO' => 'Entregado',
        ];

        return view('envios.edit', compact('envio', 'estados'));
    }

    /**
     * Actualizar envío
     */
    public function update(Request $request, $id)
    {
        $envio = Envio::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:PENDIENTE,PREPARANDO,EN_CAMINO,ENTREGADO',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ]);

        $envio->update([
            'estado' => $request->estado,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'codigo_postal' => $request->codigo_postal,
        ]);

        return redirect()->route('envios.edit', $envio->id)
            ->with('success', 'Envío actualizado correctamente.');
    }
}
