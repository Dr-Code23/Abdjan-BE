<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public static int $recordsCount = 60;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0 ; $i<10 ; $i++){
            Category::insert([
                'name' => fake()->name()
            ]);
        }

        for($i = 0 ; $i<50 ; $i++){
            Category::insert([
                'name' => fake()->name(),
                'parent_id' => rand(1,10)
            ]);
        }
    }
}
