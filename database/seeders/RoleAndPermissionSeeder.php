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

        $allPermissions = config('permission.permissions');
        foreach(config('permission.permissions') as $permission){
            Permission::create(['name' => $permission]);
        }

        foreach(config('permission.roles') as $key=>$value){
            $permissions = array_diff($allPermissions , $value);

            Role::create(['name' => $key])
                ->givePermissionTo($permissions);
        }
    }
}
