<?php
// app/Livewire/Profile/VendorDashboard.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class VendorDashboard extends Component
{
    use WithFileUploads;

    public $activeSection = 'overview'; // 'overview', 'services', 'schedule', 'analytics'
    public $services = [];
    public $schedule = [];
    public $newService = [
        'name' => '',
        'description' => '',
        'price' => '',
        'duration' => ''
    ];

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->vendor) {
            // Load existing services and schedule
            $this->services = $user->vendor->services ?? [];
            $this->schedule = $user->vendor->schedule ?? [];
        }
    }

    public function switchSection($section)
    {
        $this->activeSection = $section;
    }

    public function addService()
    {
        $this->validate([
            'newService.name' => 'required|string|max:255',
            'newService.description' => 'required|string',
            'newService.price' => 'required|numeric|min:0',
            'newService.duration' => 'required|integer|min:1',
        ]);

        $this->services[] = $this->newService;
        $this->saveServices();
        $this->reset('newService');
    }

    public function removeService($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services);
        $this->saveServices();
    }

    public function saveServices()
    {
        $user = Auth::user();
        if ($user && $user->vendor) {
            $user->vendor->update([
                'services' => $this->services
            ]);
        }
    }

    public function updateSchedule($day, $open, $close)
    {
        $this->schedule[$day] = [
            'open' => $open,
            'close' => $close,
            'closed' => empty($open) && empty($close)
        ];

        $this->saveSchedule();
    }

    public function saveSchedule()
    {
        $user = Auth::user();
        if ($user && $user->vendor) {
            $user->vendor->update([
                'schedule' => $this->schedule
            ]);
        }
    }

    public function render()
    {
        $user = Auth::user();
        $vendor = $user ? $user->vendor : null;

        return view('livewire.profile.vendor-dashboard', [
            'user' => $user,
            'vendor' => $vendor,
        ]);
    }
}