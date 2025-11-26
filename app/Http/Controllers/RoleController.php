<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStore;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStore $request)
    {

        $data = $request->validated();
        try {
            Role::create([
                'name' => $data['name'],
                'descripcion' => $data['description'],
            ]);
            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear el rol.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('roles.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return  view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleStore $request, string $id)
    {
        $data = $request->validated();
        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $data['name'],
                'descripcion' => $data['description'],
                'is_active' => $data['is_active'] ?? true,
            ]);
            return redirect()->route('roles.index')->with('success', 'Rol actualizado');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al editar el rol.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => false,
                'message' => 'Ocurrió un error al eliminar el rol.'
            ], 500);
        }
    }

    public function updatePermissions(Request $request, string $id)
    {
        try {
            $role = Role::findOrFail($id);
            $permissions = $request->input('permissions', []);
            $role->syncPermissions($permissions);
            return redirect()->back()->with('success', '¡Permisos actualizado correctamente!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al editar el rol.');
        }
    }
}
