<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'name' => [
                'en' => fake()->name(),
                'ar' => fake()->name(),
                'fr' => fake()->name(),
            ],
            'address' => fake()->text(),
            'facebook' => fake()->url(),
            'whatsapp' => fake()->url(),
            'instagram' => fake()->url(),
            'youtube' => fake()->url(),
            'phones' => fake()->phoneNumber() ."/" . fake()->phoneNumber(),
            'email' => fake()->email()
        ]);
    }
}
