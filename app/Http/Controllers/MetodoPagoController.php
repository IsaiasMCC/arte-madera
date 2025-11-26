<?php

namespace App\Http\Controllers;

use App\Http\Requests\metodo_pagos\MetodoPagoStoreRequest;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    public function index()
    {
        $metodos = MetodoPago::all();
        return view('metodos_pago.index', compact('metodos'));
    }

    public function create()
    {
        return view('metodos_pago.create');
    }

    public function store(MetodoPagoStoreRequest $request)
    {
        $data = $request->validated();

        try {
            MetodoPago::create($data);
            return redirect()->route('metodos_pago.index')->with('success', 'Método de pago creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear el método de pago.');
        }
    }

    public function edit($id)
    {
        $metodo = MetodoPago::findOrFail($id);
        return view('metodos_pago.edit', compact('metodo'));
    }

    public function update(MetodoPagoStoreRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $metodo = MetodoPago::findOrFail($id);
            $metodo->update($data);
            return redirect()->route('metodos_pago.index')->with('success', 'Método de pago actualizado correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar el método de pago.');
        }
    }

    public function destroy($id)
    {
        try {
            $metodo = MetodoPago::findOrFail($id);
            $metodo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Método de pago eliminado correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el método de pago.'
            ], 500);
        }
    }
}
