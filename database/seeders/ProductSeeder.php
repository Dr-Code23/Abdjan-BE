<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public static int $recordsCount = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0 ; $i<100 ; $i++){
            Product::insert([
                ''
            ]);
        }
    }
}
