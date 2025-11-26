<?php

namespace App\Http\Controllers;

use App\Http\Requests\categorias\CategoriaStoreRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Mostrar todas las categorías.
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Mostrar el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Guardar una nueva categoría en la base de datos.
     */
    public function store(CategoriaStoreRequest $request)
    {
        $data = $request->validated();

        try {
            Categoria::create([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'is_active' => $data['is_active'] ?? true, // opcional, por defecto activo
            ]);

            return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear la categoría.');
        }
    }

    /**
     * Mostrar el formulario para editar una categoría existente.
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Actualizar la categoría en la base de datos.
     */
    public function update(CategoriaStoreRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->update([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al editar la categoría.');
        }
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la categoría.'
            ], 500);
        }
    }
}
