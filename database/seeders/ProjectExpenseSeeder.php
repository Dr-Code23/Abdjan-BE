<?php

namespace Database\Seeders;

use App\Models\ProjectExpense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectExpenseSeeder extends Seeder
{
    public static int $recordCount = 100;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for($i = 0 ; $i< static::$recordCount ; $i++){
            $data[] = [
                'project_id' => fake()->numberBetween(1,ProjectSeeder::$recordCount),
                'created_at' => now(),
            ];
        }

        foreach(array_chunk($data , 100) as $item){
            ProjectExpense::insert($item);
        }
    }
}
