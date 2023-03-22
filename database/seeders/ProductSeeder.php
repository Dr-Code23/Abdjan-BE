<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Translations\ProductTranslation;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public static int $recordsCount = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0 ; $i<self::$recordsCount ; $i++){
            $name = [];
            $description = [];
            foreach(config('translatable.locales') as $locale){
                $name[$locale] = fake()->name();
                $description[$locale] = fake()->text();
            }
            Product::create([
                'name' => $name,
                'description' => $description,
                'category_id' => fake()->numberBetween(1,CategorySeeder::$recordsCount),
                'brand_id' => fake()->numberBetween(1,BrandSeeder::$recordsCount),
                'attribute_id' => fake()->numberBetween(1,AttributeSeeder::$recordsCount),
                'unit_id' => fake()->numberBetween(1,MeasurementUnitSeeder::$recordsCount),
                'unit_price' => fake()->randomFloat(2,1,300),
                'quantity' => fake()->numberBetween(1,300),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
