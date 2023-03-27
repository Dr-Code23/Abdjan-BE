<?php

namespace Database\Seeders;

use App\Models\GeneralExpense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralExpenseSeeder extends Seeder
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
                'price' => fake()->randomFloat(2,1,400),
                'reason' => fake()->text(),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach(array_chunk($data ,100) as $item){
            GeneralExpense::insert($item);
        }
    }
}
