<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach(config('roles-permissions')['permissions'] as $permission){
            Permission::create(['name' => $permission]);
        }

        foreach(config('roles-permissions')['roles'] as $roleName => $excludedPermissions){
            $role = Role::create(['name' => $roleName]);

            $permissions = config('roles-permissions')['permissions'];

            foreach($excludedPermissions as $excludedPermission){
                unset($permissions[array_search($excludedPermission , $permissions)]);
            }

            $role->givePermissionTo($permissions);

        }

    }
}
