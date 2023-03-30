<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutUs::create([
            'name' => [
                'en' => fake()->name(),
                'ar' => fake()->name(),
                'fr' => fake()->name(),
            ],'description' => [
                'en' => fake()->realText(),
                'ar' => fake()->realText(),
                'fr' => fake()->realText(),
            ],
        ]);
    }
}
