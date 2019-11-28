<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\Api\Users\UploadImageRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function update(UploadImageRequest $request) {
        $user = $request->user();

        if($request->hasFile('path_image') && $request->file('path_image')->isValid()){
            if ($user->path_image != 'user_images/default.png')
                Storage::delete($user->path_image);

            $path = $request->path_image->store('public/user_images');

            $user->update(['path_image' => $path]);

            return response()->json(['meta' => ['success' => true, 'message' => 'Imagem de perfil atualizada com sucesso!']]);
        }
    }
}
