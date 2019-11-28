<?php

namespace App\Http\Controllers\Api\User\Client;

use App\Http\Requests\Api\Notification\SendEmailRequest;
use App\Notifications\Api\Auth\Client\RegisterNotification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivateRegisterController extends Controller
{
    public function activateRegistration($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (is_null($user))
            return response()->json(['meta' => ['success' => false, 'message' => 'Token de ativação inválido!']], 404);

        $user->update(['active' => true, 'activation_token' => '']);

        return response()->json(['meta' => ['success' => true, 'message' => 'Cadastro ativado com sucesso.']]);
    }

    public function resendConfirmationEmail(SendEmailRequest $request) {
        $user = User::where('email', $request->email)->where('active', false)->first();

        if (is_null($user))
            return response()->json(['meta' => ['success' => false, 'message' => 'E-mail já ativo ou não encontrado.']], 404);

        $user->notify(new RegisterNotification($user));

        return response()->json(['meta' => ['success' => true, 'message' => 'Confirmação reenviada com sucesso! Acesse seu e-mail e ative o cadastro.']]);
    }
}
