<?php

namespace Database\Seeders;

use App\Models\SpotDetector;
use App\Models\Vendor;
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
            CategoriesTableSeeder::class,
            VendorSeeder::class,
            VendorScheduleSeeder::class,
            SmartSpotDetectorSeeder::class,
        ]);
    }
}
