<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            VendorSeeder::class,
            VendorDetailsSeeder::class,
            PaymentMethodSeeder::class,
            AdsSeeder::class,
            CouponSeeder::class,
            CategorySeeder::class,
            ServicesSeeder::class,
            VendorServicesSeeder::class,
            PagesSeeder::class,
            SettingsSeeder::class,
            LangsSeeder::class,
        ]);

    }
}
