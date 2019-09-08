<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        try {
            DB::beginTransaction();

            $permissions = [
                ['name' => 'edit-registration',    'guard_name' => 'api'],
                ['name' => 'view-client',          'guard_name' => 'api'],
                ['name' => 'list-clients',         'guard_name' => 'api'],
                ['name' => 'create-administrator', 'guard_name' => 'api'],
                ['name' => 'view-administrator',   'guard_name' => 'api'],
                ['name' => 'list-administrators',  'guard_name' => 'api'],
                ['name' => 'create-permission',    'guard_name' => 'api'],
                ['name' => 'list-permissions',     'guard_name' => 'api'],
                ['name' => 'delete-permission',    'guard_name' => 'api'],
                ['name' => 'great-permission',     'guard_name' => 'api'],
                ['name' => 'revoke-permission',    'guard_name' => 'api'],
                ['name' => 'create-role',          'guard_name' => 'api'],
                ['name' => 'list-roles',           'guard_name' => 'api'],
                ['name' => 'delete-role',          'guard_name' => 'api'],
                ['name' => 'great-role',           'guard_name' => 'api'],
                ['name' => 'revoke-role',          'guard_name' => 'api'],
            ];

            foreach ($permissions as $permission) {
                Permission::create($permission);
            }

            $role = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);
            $role->givePermissionTo(Permission::all());

            $role = Role::create(['name' => 'administrator', 'guard_name' => 'api']);
            $role->givePermissionTo('edit-registration');
            $role->givePermissionTo('view-client');
            $role->givePermissionTo('list-clients');

            $role = Role::create(['name' => 'client', 'guard_name' => 'api']);
            $role->givePermissionTo('edit-registration');

            DB::commit();
        } catch(Exception $ex) {
            echo $ex->getMessage();
            DB::rollBack();
        }
    }
}
