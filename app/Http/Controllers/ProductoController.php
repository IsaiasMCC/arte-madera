<?php

namespace App\Http\Controllers;

use App\Http\Requests\productos\ProductoStoreRequest;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria', 'tienda')->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $tiendas = Tienda::all();
        return view('productos.create', compact('categorias', 'tiendas'));
    }

    public function store(ProductoStoreRequest $request)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('productos', 'public');
            }

            Producto::create($data);

            return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear el producto.');
        }
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        $tiendas = Tienda::all();
        return view('productos.edit', compact('producto', 'categorias', 'tiendas'));
    }

    public function update(ProductoStoreRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $producto = Producto::findOrFail($id);

            if ($request->hasFile('foto')) {
                if ($producto->foto) {
                    Storage::disk('public')->delete($producto->foto);
                }
                $data['foto'] = $request->file('foto')->store('productos', 'public');
            }

            $producto->update($data);

            return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar el producto.');
        }
    }

    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            if ($producto->foto) {
                Storage::disk('public')->delete($producto->foto);
            }
            $producto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el producto.'
            ], 500);
        }
    }
}
