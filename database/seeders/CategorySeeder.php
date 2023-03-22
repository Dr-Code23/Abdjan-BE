<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Translations\CategoryTranslation;
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
        for($i = 1 ; $i<=10 ; $i++){
            $name = [];
            foreach(config('translatable.locales') as $locale){
                $name[$locale] = fake()->name();
            }
            Category::create([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        for($i = 11 ; $i<=60 ; $i++){
            $name = [];
            foreach(config('translatable.locales') as $locale){
                $name[$locale] = fake()->name();
            }
            Category::create([
                'name' => $name,
                'parent_id' => rand(1,10),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
