<?php

namespace App\Http\Controllers;

use App\Http\Requests\tiendas\TiendaStoreRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index()
    {
        $tiendas = Tienda::all();
        return view('tiendas.index', compact('tiendas'));
    }

    public function create()
    {
        return view('tiendas.create');
    }

    public function store(TiendaStoreRequest $request)
    {
        $data = $request->validated();

        try {
            Tienda::create($data);
            return redirect()->route('tiendas.index')->with('success', 'Tienda creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear la tienda.');
        }
    }

    public function edit($id)
    {
        $tienda = Tienda::findOrFail($id);
        return view('tiendas.edit', compact('tienda'));
    }

    public function update(TiendaStoreRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $tienda = Tienda::findOrFail($id);
            $tienda->update($data);
            return redirect()->route('tiendas.index')->with('success', 'Tienda actualizada correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar la tienda.');
        }
    }

    public function destroy($id)
    {
        try {
            $tienda = Tienda::findOrFail($id);
            $tienda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tienda eliminada correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la tienda.'
            ], 500);
        }
    }

    public function tienda(Request $request)
    {
        $categorias = Categoria::with('productos')->get();

        $categoria_id = $request->get('categoria');

        if ($categoria_id) {
            $productos = Producto::where('categoria_id', $categoria_id)
                ->where('estado', true)
                ->get();
        } else {
            $productos = Producto::where('estado', true)->get();
        }

        return view('tienda-ecommerce.index', compact('categorias', 'productos', 'categoria_id'));
    }
}
