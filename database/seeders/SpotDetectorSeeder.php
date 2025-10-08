<?php
// database/seeders/SpotDetectorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SpotDetector;
use App\Models\Rfid;
use App\Models\Vendor;
use App\Models\User;

class SpotDetectorSeeder extends Seeder
{
    public function run()
    {
        // Create sample vendor first
        $vendorUser = User::create([
            'name' => 'Pedagang Sample',
            'email' => 'pedagang@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'role' => 'vendor'
        ]);

        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'business_name' => 'Warung Makan Sederhana',
            'description' => 'Warung makan tradisional dengan berbagai menu nusantara',
            'category_id' => 1,
            'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'type' => 'informal',
            'is_rfid' => true
        ]);

        // Create Spot Detectors (IoT Devices)
        $spotDetectors = [
            [
                'device_id' => 'SPOT001',
                'device_name' => 'Pintu Masuk Pasar Senen',
                'location_name' => 'Pasar Senen - Pintu Utama',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'secret_key' => 'spot001_secret_key_2024',
                'is_active' => true,
                'description' => 'Spot detector di pintu masuk utama Pasar Senen'
            ],
            [
                'device_id' => 'SPOT002', 
                'device_name' => 'Area Food Court',
                'location_name' => 'Pasar Senen - Food Court',
                'latitude' => -6.2090,
                'longitude' => 106.8460,
                'secret_key' => 'spot002_secret_key_2024',
                'is_active' => true,
                'description' => 'Spot detector di area food court lantai 1'
            ],
            [
                'device_id' => 'SPOT003',
                'device_name' => 'Parkiran Pedagang',
                'location_name' => 'Pasar Senen - Parkir Pedagang',
                'latitude' => -6.2085,
                'longitude' => 106.8448,
                'secret_key' => 'spot003_secret_key_2024',
                'is_active' => true,
                'description' => 'Spot detector di area parkir khusus pedagang'
            ]
        ];

        foreach ($spotDetectors as $detector) {
            SpotDetector::create($detector);
        }

        // Create RFID tags (1 untuk vendor sample)
        $rfidTags = [
            [
                'uid' => 'RFID123456789',
                'vendor_id' => $vendor->id,
                'is_active' => true,
                'description' => 'Kartu RFID untuk Warung Makan Sederhana'
            ]
        ];

        foreach ($rfidTags as $rfid) {
            Rfid::create($rfid);
        }

        $this->command->info('✅ Spot Detectors dan RFID tags berhasil dibuat!');
        $this->command->info('📋 Device IDs: SPOT001, SPOT002, SPOT003');
        $this->command->info('📟 RFID UID: RFID123456789');
        $this->command->info('🏪 Vendor: Warung Makan Sederhana');
    }
}