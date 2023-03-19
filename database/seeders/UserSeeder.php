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
        self::$recordsCount = count(config('roles.all_roles'));
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = config('roles.all_roles');
        for($i = 0 ; $i< count($roles) ; $i++){

            User::create([
                'name' => $roles[$i],
                'email' => $roles[$i].'@admin.com',
                'password' => $roles[$i],
                'role_id' => $i+1
            ]);
        }
    }
}
