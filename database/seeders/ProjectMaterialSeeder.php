<?php

namespace Database\Seeders;

use App\Models\ProjectMaterial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectMaterialSeeder extends Seeder
{
    public static int $recordCount = 100;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for($i = 0 ; $i<static::$recordCount ; $i++){
            $data[] = [
                'project_id' => fake()->numberBetween(1,ProjectSeeder::$recordCount),
                'product_id' => fake()->numberBetween(1,ProductSeeder::$recordsCount),
                'quantity' => fake()->numberBetween(1,400),
                'price_per_unit' => fake()->randomFloat(2,1,300)
            ];
        }

        foreach(array_chunk($data , 100) as $item){
            ProjectMaterial::insert($item);
        }
    }
}
