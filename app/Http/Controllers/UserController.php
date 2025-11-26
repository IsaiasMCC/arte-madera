<?php

namespace App\Http\Controllers;

use App\Http\Requests\users\PostUserRequest;
use App\Http\Requests\users\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('usuarios.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostUserRequest $request)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($data['role']);
            if (!$role) {
                throw new Exception("Role no encontrado.");
            }
            $user = new User();
            $user->ci = $data['ci'];
            $user->nombres = $data['name'];
            $user->apellidos = $data['lastname'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->rol_id = $data['role'];
            $user->save();
            $user->assignRole($role->id);
            DB::commit();
            return redirect()->route('usuarios.index')->with('success', 'Usuario Agregado Correctamente!.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }
        $roles = Role::all();
        return view('usuarios.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            if (!$user) {
                return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
            }
            $user->nombres = $data['name'];
            $user->apellidos = $data['lastname'];
            $user->email = $data['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($data['password']);
            }
            $user->rol_id = $data['role'];
            $user->estado = $data['estado'];
            $user->syncRoles(Role::findOrFail($data['role']));
            $user->save();
            DB::commit();
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado Correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al editar el usuario: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            if (!$user) {
                return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
            }
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => false,
                'message' => 'Ocurrió un error al eliminar el usuario: ' . $th->getMessage(),
            ], 500);
        }
    }
}
