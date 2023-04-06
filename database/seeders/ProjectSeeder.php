<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public static int $recordCount = 5;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for($i = 0 ; $i<self::$recordCount ; $i++){
            $data[] = [
                'customer_name' => fake()->name(),
                'project_name' => fake()->name(),
                'total' => fake()->randomFloat(2,1,1000),
                'project_total' => fake()->randomFloat(2,1,400),
                'start_date' => now(),
                'end_date' => date('Y-m-d' , strtotime('+' . rand(1,100) . 'days')),
            ];
        }

        foreach(array_chunk($data , 100) as $item){
            Project::insert($item);
        }
    }
}
