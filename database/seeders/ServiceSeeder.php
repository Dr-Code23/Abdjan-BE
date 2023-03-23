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
        $data = [];
        for($i = 1 ; $i<self::$recordsCount ; $i++){
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
                'price' => fake()->randomFloat(2,1,500),
                'phone' => fake()->phoneNumber(),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach(array_chunk($data , 1000) as $item){
            Service::insert($item);
        }
    }
}
