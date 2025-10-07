<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/VendorSeeder.php
    public function run()
    {
        Vendor::create([
            'user_id' => 1,
            'business_name' => 'Warung Nasi Uduk Bu Sri',
            'description' => 'Nasi Uduk tradisional dengan lauk lengkap',
            'category_id' => 1,
            'address' => 'Jl. Merdeka No. 123',
            'latitude' => -6.402,
            'longitude' => 106.963,
            'type' => 'informal',
            'is_rfid' => false,
        ]);

        Vendor::create([

            'user_id' => 2,
            'business_name' => 'Pizza Hot Corner',
            'description' => 'Pizza dan makanan western',
            'category_id' => 1,
            'address' => 'Jl. Sudirman No. 456',
            'latitude' => -6.408,
            'longitude' => 106.968,
            'type' => 'formal',
            'is_rfid' => true,
        ]);
    }
}
