<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdministratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email'     => 'email@dominio.com',
            'password'  => Hash::make('asdfasdf'),
            'active'    => true,
        ])->administrator()->create([
            'name'      => 'Super',
            'last_name' => 'Administrador',
        ]);

        $user->user->assignRole('super-admin');
        $user->user->givePermissionTo(Role::findByName('super-admin')->getPermissionNames());
    }
}
