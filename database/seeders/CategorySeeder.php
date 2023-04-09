<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Translations\CategoryTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public static int $recordsCount = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1 ; $i<=30 ; $i++){
            Category::create([
                'name' => $this->getNameTranslations(),
                'status' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        for($i = 11 ; $i<=60 ; $i++){
            Category::create([
                'name' => $this->getNameTranslations(),
                'parent_id' => rand(1,30),
                'status' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        for($i = 0; $i <40 ; $i++){
            Category::create([
                'name' => $this->getNameTranslations(),
                'parent_id' => 1,
                'status' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        for($i = 61 ; $i<=100 ; $i++){

            Category::create([
                'name' => $this->getNameTranslations(),
                'parent_id' => rand(11,60),
                'status' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $subCategory = Category::create([
            'name' => 'Sub Category',
            'parent_id' => 1,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Category::create([
            'name' => 'Sub Sub Category',
            'parent_id' => $subCategory->id,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }


    private function getNameTranslations(): array
    {
        $name = [];
        foreach(config('translatable.locales') as $locale){
            $name[$locale] = fake()->name();
        }

        return $name;
    }
}
