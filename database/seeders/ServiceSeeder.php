<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Translations\ServiceTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public static int $recordsCount = 100;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1 ; $i<self::$recordsCount ; $i++){
            Service::insert([
                'category_id' => fake()->numberBetween(1,CategorySeeder::$recordsCount),
                'price' => fake()->randomFloat(2,1,500),
                'phone' => fake()->phoneNumber(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach(config('translatable.locales') as $locale){
                ServiceTranslation::insert([
                    'service_id' => $i,
                    'locale' => $locale,
                    'name' => fake()->name(),
                    'description' => fake()->text(),
                ]);
            }
        }
    }
}
