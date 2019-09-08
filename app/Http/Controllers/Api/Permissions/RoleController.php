<?php

namespace App\Http\Controllers\Api\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Permissions\RoleResource;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return RoleResource::collection($roles)->additional(['meta' => ['success' => true, 'message' => 'Funções recuperadas com sucesso!']], 200);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return (new RoleResource($role))->additional(['meta' => ['success' => true, 'message' => 'Função recuperada com sucesso!']], 200);
    }
}
