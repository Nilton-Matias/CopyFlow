<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    /**
     * Retorna o usuário autenticado
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Usuário não autenticado.',
                ], 401);
            }

            return response()->json([
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro de servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cria um novo usuário
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'email.required' => 'O campo email é obrigatório.',
                'email.email' => 'O campo email deve ser um endereço de email válido.',
                'email.unique' => 'O endereço de email já está em uso.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.confirmed' => 'As senhas não conferem.',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'])
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Usuário registrado com sucesso.',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação de dados.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro de servidor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Realiza o login do usuário
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email' => 'O campo email é obrigatório.',
                'email.email' => 'O campo email deve ser um endereço de email válido.',
                'password' => 'O campo senha é obrigatório.',
            ]);

            if (Auth::attempt($validated)) {
                $user = User::where('email', $validated['email'])->firstOrFail();

                $token = $user->createToken('auth_token', ['post:read', 'post:create'])->plainTextToken;

                return response()->json([
                    'message' => 'Usuário logado com sucesso.',
                    'user' => $user,
                    'token' => $token
                ], 200);
            }
        } catch (Exception $e) {
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'message' => 'Credenciais inválidas.',
                    'error' => $e->getMessage(),
                ], 401);
            }
        }
    }

    /**
     * Realiza o logout do usuário
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token de autenticação não fornecido.',
            ], 400);
        }

        $acess_token = PersonalAccessToken::findToken($token);

        if (!$acess_token) {
            return response()->json([
                'message' => 'Token de autenticação inválido.',
            ], 400);
        } else {
            $acess_token->delete();
            return response()->json([
                'message' => 'Logout realizado com sucesso.',
            ]);
        }
    }
}
