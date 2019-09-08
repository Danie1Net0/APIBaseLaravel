<?php

namespace App\Http\Controllers\Api\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Permissions\PermissionResource;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return PermissionResource::collection($permissions)->additional(['meta' => ['success' => true, 'message' => 'Permissões recuperadas com sucesso!']]);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return (new PermissionResource($permission))->additional(['meta' => ['success' => true, 'message' => 'Permissão recuperada com sucesso!']]);
    }
}
