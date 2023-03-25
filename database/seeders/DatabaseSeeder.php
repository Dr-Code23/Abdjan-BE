<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            MeasurementUnitSeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            ProjectMaterialSeeder::class,
        ]);
    }
}
