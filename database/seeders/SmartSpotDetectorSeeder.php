<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpotDetector;

class SmartSpotDetectorSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['Cileungsi Plaza', -6.3923, 106.9594],
            ['Pasar Lama Cileungsi', -6.3935, 106.9610],
            ['Perumahan Metland Transyogi', -6.3909, 106.9652],
            ['Terminal Cileungsi', -6.3975, 106.9630],
            ['RS MH Thamrin Cileungsi', -6.3983, 106.9618],
            ['Pasar Jonggol', -6.4872, 107.0404],
            ['Pasar Gunung Putri', -6.4287, 106.9289],
            ['Pasar Citeureup', -6.4870, 106.8844],
            ['Pasar Kemang', -6.4583, 106.8009],
            ['Pasar Cibinong', -6.4821, 106.8565],
        ];

        $spotDetectors = [];
        foreach ($locations as $i => $loc) {
            $spotDetectors[] = [
                'device_id' => 'SPOT' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'device_name' => "Smart Detector " . ($i + 1),
                'location_name' => $loc[0],
                'latitude' => $loc[1],
                'longitude' => $loc[2],
                'secret_key' => 'spot_' . strtolower(str_replace(' ', '_', $loc[0])) . '_key_2025',
                'is_active' => true,
                'description' => "Smart detector di area " . $loc[0],
            ];
        }

        SpotDetector::insert($spotDetectors);
        $this->command->info('✅ 10 Smart Spot Detectors berhasil dibuat!');
    }
}
