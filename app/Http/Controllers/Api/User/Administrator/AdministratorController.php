<?php

namespace App\Http\Controllers\Api\User\Administrator;

use App\Http\Requests\Api\Users\Administrator\RegisterRequest;
use App\Http\Requests\Api\Users\Administrator\UpdateRequest;
use App\Http\Resources\Api\Users\AdministratorResource;
use App\Models\Api\Users\Administrator;
use App\Notifications\Api\Auth\Administrator\RegisterNotification;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdministratorController extends Controller
{
    public function index(Request $request)
    {
        $administrator = User::whereHas('administrator')->paginate(10);
        return AdministratorResource::collection($administrator)->additional(['meta' => ['success' => true, 'message' => 'Administratores recuperados com sucesso.']]);
    }

    public function store(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'email'            => $request->email,
                'active'           => false,
                'activation_token' => Str::random(60)
            ])->administrator()->create([
                'name'             => '',
                'last_name'        => ''
            ]);

            if ($request->is_super_admin) {
                $user->user->assignRole('super-admin');
                $user->user->givePermissionTo(Role::findByName('super-admin')->getPermissionNames());
            } else {
                $user->user->assignRole('administrator');
                $user->user->givePermissionTo(Role::findByName('administrator')->getPermissionNames());
            }

            DB::commit();

            $user->user->notify(new RegisterNotification($user));

            return response()->json(['meta' => ['success' => true, 'message' => 'Administrador cadastrado com sucesso.']], 201);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['meta' => ['success' => false, 'message' => 'Aconteceu um erro. Tente novamente mais tarde']], 500);
        }
    }

    public function show($id)
    {
        $administrator = Administrator::findOrFail($id);
        return (new AdministratorResource($administrator->user))->additional(['meta' => ['success' => true, 'message' => 'Administrador recuperado com sucesso.']]);
    }

    public function update(UpdateRequest $request)
    {
        $administrator = $request->user()->administrator;

        $administrator->update([
            'name'      => $request->name,
            'last_name' => $request->last_name
        ]);

        return (new AdministratorResource($administrator->user))->additional(['meta' => ['success' => true, 'message' => 'Administrador atualizado com sucesso.']]);
    }
}
