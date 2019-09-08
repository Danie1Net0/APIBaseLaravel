<?php

namespace App\Http\Resources\Api\Users;

use App\Http\Resources\Api\Permissions\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user'          => new UserResource($this),
            'client'     => [
                'id'        => $this->id,
                'name'      => $this->client->name,
                'last_name' => $this->client->last_name,
                'city'      => $this->client->city,
                'state'     => $this->client->state,
            ],
            'role'          => new RoleResource(Role::findByName($this->getRoleNames()->first()))
        ];
    }
}
