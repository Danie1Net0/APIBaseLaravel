<?php

namespace App;

use App\Models\Api\Users\Administrator;
use App\Models\Api\Users\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $guard_name = 'api';
    protected $fillable = [
        'email', 'password', 'path_image', 'active', 'activation_token'
    ];

    protected $hidden = [
        'password', 'activation_token',
    ];

    public function administrator()
    {
        return $this->hasOne(Administrator::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
