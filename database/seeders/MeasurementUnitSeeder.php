<?php

namespace Database\Seeders;

use App\Models\MeasureUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasurementUnitSeeder extends Seeder
{
    public static int $recordsCount;

    public function __construct(){
        self::$recordsCount = count(config('units.available_units'));
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = config('units.available_units');
        for($i = 0 ; $i < self::$recordsCount ; $i++)
        {
            MeasureUnit::insert([
                'name' => $units[$i]
            ]);
        }

        for($i = 0 ; $i<100 ; $i++){
            MeasureUnit::create(['name' => fake()->name()]);
        }
    }
}
