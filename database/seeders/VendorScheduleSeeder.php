<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\VendorSchedule;

class VendorScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::all();

        foreach ($vendors as $vendor) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            foreach ($days as $day) {
                $isWeekend = in_array($day, ['saturday', 'sunday']);
                
                VendorSchedule::create([
                    'vendor_id' => $vendor->id,
                    'day' => $day,
                    'open_time' => $isWeekend ? '09:00:00' : '08:00:00',
                    'close_time' => $isWeekend ? '17:00:00' : '20:00:00',
                    'is_closed' => $day === 'sunday' && $vendor->is_informal,
                    'notes' => $day === 'sunday' ? 'Hari Minggu tutup' : null
                ]);
            }
        }
    }
}