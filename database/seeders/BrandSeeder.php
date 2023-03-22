<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public static int $recordsCount = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0 ; $i<self::$recordsCount ; $i++){
            $name = [];
            foreach(config('translatable.locales') as $locale){
                $name[$locale] = fake()->name();
            }

            Brand::create(['name' =>$name]);
        }
    }
}
