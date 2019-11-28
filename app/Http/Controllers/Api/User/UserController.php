<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Resources\Api\Users\AdministratorResource;
use App\Http\Resources\Api\Users\ClientResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('client'))
            return (new ClientResource($user))->additional(['meta' => ['success' => true, 'message' => 'Usuário recuperado com sucesso!']]);
        else if ($user->hasRole('administrator|super-admin'))
            return (new AdministratorResource($user))->additional(['meta' => ['success' => true, 'message' => 'Usuário recuperado com sucesso!']]);

        return response()->json(['meta' => ['success' => false, 'message' => 'Usuário não encontrado']], 401);
    }
}
