<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // LOGIN API
    // POST /api/login
    // Body JSON : { "email": "...", "password": "..." }
    public function login(Request $request)
    {
        // Validation des données reçues
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Chercher l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        // Vérifier le mot de passe
        // Hash::check() compare le mot de passe avec le hash en base
        if (!$user || !Hash::check($request->password, $user->password)) {
            // ValidationException retourne automatiquement une réponse JSON 422
            throw ValidationException::withMessages([
                'email' => ['Email ou mot de passe incorrect.'],
            ]);
        }

        // Créer un token Sanctum pour cet utilisateur
        // 'api-token' = nom du token (on peut mettre ce qu'on veut)
        $token = $user->createToken('api-token')->plainTextToken;

        // Retourner la réponse JSON
        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
        ]);
    }

    // LOGOUT API
    // POST /api/logout
    // Header : Authorization: Bearer TON_TOKEN
    public function logout(Request $request)
    {
        // Révoquer le token actuel
        // currentAccessToken() = le token utilisé pour cette requête
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
        ]);
    }

    // ME — Infos utilisateur connecté
    // GET /api/me
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('roles');

        return response()->json([
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'roles'       => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}