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
            'social_links' => [
                'https://www.google.com',
                'https://www.facebook.com',
                'https://www.twitter.com',
                'https://www.youtube.com'
            ],
            'phones' => [
                fake()->phoneNumber()
            ]
        ]);
    }
}
