<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\PasswordUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateController extends Controller
{
    public function update(PasswordUpdateRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password))
            return response()->json(['meta' => ['success' => false, 'message' => 'A senha atual não corresponde com o valor informado.']], 202);

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['meta' => ['success' => true, 'message' => 'Senha alterada com sucesso!']]);
    }
}
