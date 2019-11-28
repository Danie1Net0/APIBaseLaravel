<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\AuthRequest;
use App\Http\Resources\Api\Users\AdministratorResource;
use App\Http\Resources\Api\Users\ClientResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials) && Auth::guard('web')->user()->active) {
            $user = Auth::guard('web')->user();
            $token = $user->createToken(env('AUTH_TOKEN'))->accessToken;

            return response()->json(['data' => ['token' => $token], 'meta' => ['success' => 'true', 'message' => 'Usuário autenticado com sucesso!']]);
        }

        return response()->json(['meta' => ['success' => false, 'message' => 'E-mail ou senha inválidos!']], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['meta' => ['success' => true, 'message' => 'Usuário desconectado com sucesso']]);
    }
}
