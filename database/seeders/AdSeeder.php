<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public static int $recordCount = 100;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for($i = 0 ; $i < static::$recordCount ; $i++){
            $data[] = [
                'title' => json_encode([
                    'en' => fake()->name(),
                    'ar' => fake()->name(),
                    'fr' => fake()->name(),
                ]),
                'description' => json_encode([
                    'en' => fake()->text(),
                    'ar' => fake()->text(),
                    'fr' => fake()->text(),
                ]),
                'discount' => fake()->randomFloat(2,1,90),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach(array_chunk($data , 100) as $collection){
            Ad::insert($collection);
        }
    }
}
