<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public static int $recordsCount = 100;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data  = [];
        for($i = 0 ; $i<self::$recordsCount ; $i++){
            $data[] = [
                'name' => fake()->name()
            ];
        }

        foreach(array_chunk($data , 1000) as $item) {
            Attribute::insert($item);
        }
    }
}
