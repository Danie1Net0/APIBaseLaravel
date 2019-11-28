<?php

namespace App\Http\Controllers\Api\User\Client;

use App\Http\Requests\Api\Users\Client\RegisterRequest;
use App\Http\Requests\Api\Users\Client\UpdateRequest;
use App\Http\Resources\Api\Users\ClientResource;
use App\Models\Api\Users\Client;
use App\Notifications\Api\Auth\Client\RegisterNotification;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = User::whereHas('client')->paginate(10);
        return ClientResource::collection($clients)->additional(['meta' => ['success' => true, 'message' => 'Clientes recuperados com sucesso.']]);
    }

    public function store(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'email'            => $request->email,
                'password'         => Hash::make($request->password),
                'activation_token' => Str::random(60)
            ])->client()->create([
                'name'             => $request->name,
                'last_name'        => $request->last_name,
                'city'             => $request->city,
                'state'            => $request->state
            ]);

            $user->user->assignRole('client');
            $user->user->givePermissionTo(Role::findByName('client')->getPermissionNames());

            $user->user->notify(new RegisterNotification($user));

            DB::commit();

            return response()->json(['meta' => ['success' => true, 'message' => 'Cadastro realizado com sucesso! Por favor acesse seu e-mail e confirme sua conta.']], 201);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['meta' => ['success' => false, 'message' => 'Aconteceu um erro. Tente novamente mais tarde']], 500);
        }
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return (new ClientResource($client->user))->additional(['meta' => ['success' => true, 'message' => 'Cliente recuperado com sucesso.']]);
    }

    public function update(UpdateRequest $request)
    {
        $client = $request->user()->client;

        $client->update([
            'name'      => $request->name,
            'last_name' => $request->last_name,
            'city'      => $request->city,
            'state'     => $request->state
        ]);

        return (new ClientResource($client->user))->additional(['meta' => ['success' => true, 'message' => 'Cadastro atualizado com sucesso.']]);
    }
}
