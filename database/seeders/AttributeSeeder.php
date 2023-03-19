<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public static int $recordsCount = 100;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0 ; $i<self::$recordsCount ; $i++){
            Attribute::insert([
                'name' => fake()->name()
            ]);
        }
    }
}