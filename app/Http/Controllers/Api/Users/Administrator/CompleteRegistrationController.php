<?php

namespace App\Http\Controllers\Api\Users\Administrator;

use App\Http\Requests\Api\Users\Administrator\CompleteRegistrationRequest;
use App\Http\Resources\Api\Users\AdministratorResource;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompleteRegistrationController extends Controller
{
    public function findAdministratorByToken($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (is_null($user))
            return response()->json(['meta' => ['success' => false, 'message' => 'Token de finalização de cadastro inválido.']], 404);

        return response()->json([
            'data'  => ['email' => $user->email],
            'meta'  => ['success' => true, 'message' => 'Solicitação autorizada.', 'activation_token' => $user->activation_token],
            'links' => ['description' => 'Completar cadastro.', 'uri' => env('APP_URL', 'http://localhost:8000/') . 'api/register/administrators/complete-registration', 'method' => 'POST']
        ]);
    }

    public function completeRegistration(CompleteRegistrationRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::where('activation_token', $request->activation_token)->where('email', $request->email)->first();

            if (is_null($user))
                return response()->json(['meta' => ['success' => false, 'message' => 'O usuário não foi encontrado.']], 404);

            $user->update([
                'password'         => Hash::make($request->password),
                'active'           => true,
                'activation_token' => ''
            ]);

            $user->administrator->update([
                'name'      => $request->name,
                'last_name' => $request->last_name
            ]);

            DB::commit();

            return (new AdministratorResource($user))->additional(['meta' => ['success' => true, 'message' => 'Cadastro finalizado com sucesso.']]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['meta' => ['success' => false, 'message' => 'Aconteceu um erro. Tente novamente mais tarde']], 500);
        }
    }
}
