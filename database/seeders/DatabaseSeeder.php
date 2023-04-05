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
            SettingSeeder::class,
            AboutUsSeeder::class,
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
//            CategorySeeder::class,
//            AttributeSeeder::class,
//            MeasurementUnitSeeder::class,
//            ProductSeeder::class,
//            ServiceSeeder::class,
//            ProjectSeeder::class,
//            ProjectMaterialSeeder::class,
//            ProjectPaymentSeeder::class,
//            ProjectExpenseSeeder::class,
//            ProjectExpenseProductSeeder::class,
//            GeneralExpenseSeeder::class,
//            ContactUsSeeder::class,
//            AdSeeder::class,
        ]);
    }
}
