<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public static int $recordsCount;
    public function __construct(){
        self::$recordsCount = count(array_keys(config('permission.roles')));
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = array_keys(config('permission.roles'));

        for($i = 0 ; $i< count($roles) ; $i++){
            $user = User::create([
                'name' => $roles[$i],
                'email' => $roles[$i].'@admin.com',
                'password' => $roles[$i],
            ]);

            $user->assignRole($roles[$i]);
        }

        $rolesCount = count(array_keys(config('permission.roles')));

        for($i= 0 ; $i < 100 ; $i++){
            $user = User::create([
                'name' => $roles[fake()->numberBetween(0,$rolesCount-1)],
                'email' => fake()->safeEmail(),
                'password' => $roles[fake()->numberBetween(0,$rolesCount-1)],
            ]);

            $user->assignRole($roles[fake()->numberBetween(0,$rolesCount-1)]);
        }
    }
}
