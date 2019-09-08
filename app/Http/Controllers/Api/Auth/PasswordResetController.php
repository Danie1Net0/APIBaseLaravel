<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\PasswordResetRequest;
use App\Http\Requests\Api\Notification\SendEmailRequest;
use App\Models\Api\Auth\PasswordReset;
use App\Notifications\Api\Auth\PasswordResetRequestNotification;
use App\Notifications\Api\Auth\PasswordResetSuccessNotification;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function sendPasswordReset(SendEmailRequest $request)
    {
        $user = User::where('email', $request->email)->where('active', true)->first();

        if (is_null($user))
            return response()->json(['meta' => ['success' => false, 'message' => 'E-mail não encontrado.']], 404);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['email' => $user->email, 'token' => Str::random(60)]
        );

        if ($user && $passwordReset)
            $user->notify(new PasswordResetRequestNotification($passwordReset->token));

        return response()->json(['meta' => ['success' => true, 'message' => 'Recuperação de senha enviada com sucesso.']]);
    }

    public function findPasswordResetToken($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (is_null($passwordReset))
            return response()->json(['meta' => ['success' => false, 'message' => 'Token de recuperação de senha inválido.']], 404);

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json(['meta' => ['success' => false, 'message' => 'Token de recuperação de senha expirado.']], 404);
        }

        return response()->json([
            'data' => ['email' => $passwordReset->email],
            'meta' => ['success' => true, 'message' => 'Solicitação autorizada.', 'token' => $passwordReset->token],
            'links' => ['description' => 'Recuperar senha.', 'uri' => env('APP_URL', 'http://localhost:8000/') . 'api/auth/password/reset', 'method' => 'POST']
        ]);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)->where('email', $request->email)->first();

        if (is_null($passwordReset))
            return response()->json(['meta' => ['success' => false, 'message' => 'Token de recuperação de senha inválido.']], 404);

        $user = User::where('email', $passwordReset->email)->first();

        if (is_null($user))
            return response()->json(['meta' => ['success' => false, 'message' => 'E-mail não encontrado.']], 404);

        $user->update(['password' => Hash::make($request->password)]);

        $passwordReset->delete();

        $user->notify(new PasswordResetSuccessNotification($passwordReset));

        return response()->json(['meta' => ['success' => true, 'message' => 'Senha recuperada com sucesso.']]);
    }
}
