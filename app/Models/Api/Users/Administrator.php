<?php

namespace App\Models\Api\Users;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Administrator extends Model
{
    use HasRoles;

    protected $fillable = [
        'name', 'last_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
