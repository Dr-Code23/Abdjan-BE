<?php

namespace Database\Seeders;

use App\Models\ProjectExpenseProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectExpenseProductSeeder extends Seeder
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
                'product_id' => fake()->numberBetween(1,ProductSeeder::$recordsCount),
                'project_expense_id' => fake()->numberBetween(1,ProjectExpenseSeeder::$recordCount),
                'quantity' => fake()->numberBetween(1,500),
                'price_per_unit' => fake()->randomFloat(2,1,400)
            ];
        }

        foreach(array_chunk($data , 100) as $item){
            ProjectExpenseProduct::insert($item);
        }
    }
}
