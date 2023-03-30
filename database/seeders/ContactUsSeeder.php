<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder
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
                'name' => fake()->name(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'message' => fake()->realText(),
                'created_at' =>now()
            ];
        }

        foreach(array_chunk($data,100) as $item){
            ContactUs::insert($item);
        }
    }
}
