<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // ⚡ Importa el modelo
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validamos los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6'
        ]);

        // Creamos el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 🔥 Aquí creamos el token automáticamente
        $token = $user->createToken('auth_token')->plainTextToken;

        // 🔥 Retornamos el usuario + token
        return response()->json([
            'message' => 'Usuario creado correctamente',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar el usuario por email
        $user = User::where('email', $request->email)->first();

        // Validar que exista y que la password coincida
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Correo o contraseña incorrectos'
            ], 401);
        }

        // ⚡ Aquí el $user es seguro que es instancia de App\Models\User
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
