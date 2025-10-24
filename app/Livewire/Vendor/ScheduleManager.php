<?php
// app/Livewire/Vendor/ScheduleManager.php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorSchedule;

class ScheduleManager extends Component
{
    public $schedules = [];
    
    // Days configuration
    public $days = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa', 
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu'
    ];

    public function mount()
    {
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $vendor = Auth::user()->vendor;
        $existingSchedules = $vendor->schedules;
        
        foreach ($this->days as $key => $day) {
            $schedule = $existingSchedules->where('day', $key)->first();
            $this->schedules[$key] = [
                'open_time' => $schedule ? ($schedule->open_time ? $schedule->open_time->format('H:i') : '') : '',
                'close_time' => $schedule ? ($schedule->close_time ? $schedule->close_time->format('H:i') : '') : '',
                'is_closed' => $schedule ? $schedule->is_closed : true,
                'notes' => $schedule ? $schedule->notes : ''
            ];
        }
    }

    public function saveSchedules()
    {
        $vendor = Auth::user()->vendor;

        // Delete existing schedules
        VendorSchedule::where('vendor_id', $vendor->id)->delete();

        // Create new schedules
        foreach ($this->schedules as $day => $schedule) {
            if (!$schedule['is_closed'] && $schedule['open_time'] && $schedule['close_time']) {
                VendorSchedule::create([
                    'vendor_id' => $vendor->id,
                    'day' => $day,
                    'open_time' => $schedule['open_time'],
                    'close_time' => $schedule['close_time'],
                    'is_closed' => false,
                    'notes' => $schedule['notes'],
                ]);
            } else {
                VendorSchedule::create([
                    'vendor_id' => $vendor->id,
                    'day' => $day,
                    'open_time' => null,
                    'close_time' => null,
                    'is_closed' => true,
                    'notes' => $schedule['notes'],
                ]);
            }
        }

        session()->flash('success', 'Jadwal operasional berhasil disimpan!');
    }

    public function toggleDayClosed($day)
    {
        $this->schedules[$day]['is_closed'] = !$this->schedules[$day]['is_closed'];
    }

    public function setAllOpen()
    {
        foreach ($this->days as $key => $day) {
            $this->schedules[$key]['is_closed'] = false;
            $this->schedules[$key]['open_time'] = '08:00';
            $this->schedules[$key]['close_time'] = '17:00';
        }
    }

    public function setAllClosed()
    {
        foreach ($this->days as $key => $day) {
            $this->schedules[$key]['is_closed'] = true;
            $this->schedules[$key]['open_time'] = '';
            $this->schedules[$key]['close_time'] = '';
        }
    }

    public function render()
    {
        return view('livewire.vendor.schedule-manager');
    }
}