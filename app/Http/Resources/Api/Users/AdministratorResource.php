<?php

namespace App\Http\Resources\Api\Users;

use App\Http\Resources\Api\Permissions\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class AdministratorResource extends JsonResource
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
            'administrator' => [
                'id'        => $this->administrator->id,
                'name'      => $this->administrator->name,
                'last_name' => $this->administrator->last_name
            ],
            'role'          => new RoleResource(Role::findByName($this->getRoleNames()->first()))
        ];
    }
}
