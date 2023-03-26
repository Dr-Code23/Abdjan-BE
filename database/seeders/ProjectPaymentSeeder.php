<?php

namespace Database\Seeders;

use App\Models\ProjectPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectPaymentSeeder extends Seeder
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
                'price' => fake()->randomFloat(2,1,400),
                'created_at' => now()
            ];
        }

        foreach(array_chunk($data , 100) as $item){
            ProjectPayment::insert($item);
        }
    }
}
