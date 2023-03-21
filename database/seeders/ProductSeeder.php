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
            $product = Product::create([
                'category_id' => fake()->numberBetween(1,CategorySeeder::$recordsCount),
                'brand_id' => fake()->numberBetween(1,BrandSeeder::$recordsCount),
                'attribute_id' => fake()->numberBetween(1,AttributeSeeder::$recordsCount),
                'unit_id' => fake()->numberBetween(1,MeasurementUnitSeeder::$recordsCount),
                'unit_price' => fake()->randomFloat(2,1,300),
                'quantity' => fake()->numberBetween(1,300),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach(config('translatable.locales') as $locale) {
                ProductTranslation::insert([
                    'product_id' => $product->id,
                    'name' => fake()->name(),
                    'description' => fake()->text(),
                    'locale' => $locale
                ]);
            }
        }
    }
}
