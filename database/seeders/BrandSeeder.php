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
        $data = [];
        for($i = 0 ; $i<self::$recordsCount ; $i++){
            $name = [];
            foreach(config('translatable.locales') as $locale){
                $name[$locale] = fake()->name();
            }
            $data[] = [
                'name' => json_encode($name),
                'img' => null,
            ];
        }

        foreach(array_chunk($data , 1000) as $item) {
            Brand::insert($item);
        }
    }
}
