<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register'); // la vista de registro
    }

    public function store(AuthRequest $request)
    {
        $credentials = $request->validated();

        try {
            $userAuth = User::where('estado', true)
                ->where('email', $credentials['email'])
                ->first();

            if (!$userAuth) {
                return redirect()->back()->with('error', 'El usuario no está activo o no existe.')->withInput();
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // Redirigir según rol
                if (Auth::user()->rol_id == 3) {
                    return redirect()->route('tiendas.tienda'); // <-- cambia 'ruta.especifica' por tu ruta
                }

                return redirect('/home'); // para otros roles
            }

            return redirect()->back()->with('error', 'Credenciales incorrectas.')->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Credenciales incorrectas.')->withInput();
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.index');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // Crear usuario con rol_id = 3
        $user = User::create([
            'ci' => $data['ci'],
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol_id' => 3, // cliente por defecto
            'estado' => true,
        ]);

        // Loguear al usuario
        Auth::login($user);

        return redirect()->route('tiendas.tienda')->with('success', 'Registro exitoso. Bienvenido!');
    }
}
