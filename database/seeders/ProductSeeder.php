<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public static int $recordsCount = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for($i = 0 ; $i<self::$recordsCount ; $i++){
            $name = [];
            $description = [];
            foreach(config('translatable.locales') as $locale){
                $name[$locale] = fake()->name();
                $description[$locale] = fake()->text();
            }
            $data[] = [
                'name' => json_encode($name),
                'description' => json_encode($description),
                'category_id' => fake()->numberBetween(1,CategorySeeder::$recordsCount),
                'brand_id' => fake()->numberBetween(1,BrandSeeder::$recordsCount),
                'attribute_id' => fake()->numberBetween(1,AttributeSeeder::$recordsCount),
                'unit_id' => fake()->numberBetween(1,MeasurementUnitSeeder::$recordsCount),
                'status' => fake()->boolean(),
                'unit_price' => fake()->randomFloat(2,1,300),
                'quantity' => fake()->numberBetween(1,300),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach(array_chunk($data , 1000) as $item){
            Product::insert($item);
        }
    }
}
