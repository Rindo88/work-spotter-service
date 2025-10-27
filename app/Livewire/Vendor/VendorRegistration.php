<?php
// app/Livewire/Vendor/VendorRegistration.php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vendor;
use App\Models\VendorSchedule;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VendorRegistration extends Component
{
    use WithFileUploads;

    public $vendorType = 'formal';
    public $currentStep = 1;
    public $progress = 33;
    public $categories;

    // Common fields
    public $business_name;
    public $category_id;
    public $description;
    public $address;
    public $phone;
    public $email;
    public $latitude;
    public $longitude;
    public $business_images = [];

    // Informal vendor fields
    public $is_mobile = false;
    public $operational_notes;

    // Schedule fields
    public $has_schedule = false;
    public $schedules = [];

    // Service fields  
    public $has_services = false;
    public $vendor_services = [];

    protected $rules = [
        // Common rules
        'business_name' => 'required|min:3|max:255|unique:vendors,business_name',
        'category_id' => 'required|exists:categories,id',
        'description' => 'required|min:10|max:1000',
        'address' => 'required|min:10|max:500',
        'phone' => 'required|regex:/^[0-9]{9,13}$/',
        'email' => 'required|email',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'business_images' => 'required|array|min:1|max:4',
        'business_images.*' => 'image|max:2048',

        // Informal vendor rules
        'is_mobile' => 'boolean',
        'operational_notes' => 'nullable|string|max:500',
        'has_schedule' => 'boolean',
        'has_services' => 'boolean',
    ];

    protected $listeners = [
        'schedule-updated' => 'handleScheduleUpdate',
        'schedule-toggle' => 'handleScheduleToggle',
        'service-updated' => 'handleServiceUpdate',
        'service-toggle' => 'handleServiceToggle',
        'resetSchedules' => 'handleResetSchedules',
        'resetServices' => 'handleResetServices'
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->phone = Auth::user()->phone;
        $this->email = Auth::user()->email;

        // Set default coordinates
        $this->latitude = -6.2088;
        $this->longitude = 106.8456;

        // Initialize schedules and services
        $this->initializeSchedules();
        $this->initializeServices();
    }

    public function initializeSchedules()
    {
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa', 
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu'
        ];
        
        foreach ($days as $dayKey => $dayName) {
            $this->schedules[$dayKey] = [
                'day_name' => $dayName,
                'open_time' => '08:00',
                'close_time' => '17:00',
                'is_closed' => $dayKey === 'sunday',
                'notes' => ''
            ];
        }
    }

    public function initializeServices()
    {
        $this->vendor_services = [
            [
                'name' => '',
                'price' => '',
                'description' => '',
                'image' => null,
                'image_url' => null
            ]
        ];
    }

    public function setVendorType($type)
    {
        $this->vendorType = $type;
        $this->currentStep = 1;
        $this->progress = 33;
        $this->resetValidation();
        $this->dispatch('resetSchedules');
        $this->dispatch('resetServices');
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'business_name' => 'required|min:3|max:255|unique:vendors,business_name',
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|min:10|max:1000',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'phone' => 'required|regex:/^[0-9]{9,13}$/',
                'email' => 'required|email',
                'address' => 'required|min:10|max:500',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);
        }

        $this->currentStep++;
        $this->progress = ($this->currentStep / 3) * 100;
    }

    public function previousStep()
    {
        $this->currentStep--;
        $this->progress = ($this->currentStep / 3) * 100;
    }

    // Schedule handlers
    public function handleScheduleUpdate($schedules)
    {
        $this->schedules = $schedules;
        Log::info('Schedule updated', ['schedules' => $schedules]);
    }

    public function handleScheduleToggle($hasSchedule)
    {
        $this->has_schedule = $hasSchedule;
        if (!$hasSchedule) {
            $this->schedules = [];
            $this->initializeSchedules();
        }
        Log::info('Schedule toggle', ['has_schedule' => $hasSchedule]);
    }

    public function handleResetSchedules()
    {
        $this->has_schedule = false;
        $this->schedules = [];
        $this->initializeSchedules();
    }

    // Service handlers
    public function handleServiceUpdate($services)
    {
        $this->vendor_services = $services;
        Log::info('Services updated', ['services' => $services]);
    }

    public function handleServiceToggle($hasServices)
    {
        $this->has_services = $hasServices;
        if (!$hasServices) {
            $this->vendor_services = [];
            $this->initializeServices();
        }
        Log::info('Service toggle', ['has_services' => $hasServices]);
    }

    public function handleResetServices()
    {
        $this->has_services = false;
        $this->vendor_services = [];
        $this->initializeServices();
    }

    // Validation for step 3
    public function validateStep3()
    {
        $rules = [
            'business_images' => 'required|array|min:1|max:4',
            'business_images.*' => 'image|max:2048',
        ];

        // Validate services if has_services is true
        if ($this->has_services) {
            foreach ($this->vendor_services as $index => $service) {
                if (!empty(trim($service['name'] ?? ''))) {
                    $rules["vendor_services.{$index}.name"] = 'required|min:3|max:255';
                    $rules["vendor_services.{$index}.price"] = 'required|numeric|min:0';
                    $rules["vendor_services.{$index}.description"] = 'nullable|string|max:500';
                }
            }
        }

        $this->validate($rules);

        // Additional validation for at least one valid service
        if ($this->has_services) {
            $validServices = collect($this->vendor_services)->filter(function ($service) {
                return !empty(trim($service['name'] ?? ''));
            });

            if ($validServices->isEmpty()) {
                $this->addError('vendor_services', 'Setidaknya satu layanan harus diisi dengan nama dan harga');
                return false;
            }
        }

        return true;
    }

    public function submit()
    {
        if (!$this->validateStep3()) {
            return;
        }

        try {
            // Upload business images
            $imagePaths = [];
            foreach ($this->business_images as $image) {
                if ($image) {
                    $imagePaths[] = $image->store('vendor-images', 'public');
                }
            }

            // Create vendor
            $vendor = Vendor::create([
                'user_id' => Auth::id(),
                'business_name' => $this->business_name,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'type' => $this->vendorType,
                'is_mobile' => $this->is_mobile,
                'profile_picture' => $imagePaths[0] ?? null,
                'operational_notes' => $this->operational_notes,
            ]);

            Log::info('Vendor created', ['vendor_id' => $vendor->id]);

            // Create schedules jika ada jadwal
            if ($this->has_schedule && !empty($this->schedules)) {
                foreach ($this->schedules as $day => $schedule) {
                    if (!$schedule['is_closed'] && !empty($schedule['open_time']) && !empty($schedule['close_time'])) {
                        VendorSchedule::create([
                            'vendor_id' => $vendor->id,
                            'day' => $day,
                            'open_time' => $schedule['open_time'],
                            'close_time' => $schedule['close_time'],
                            'is_closed' => false,
                            'notes' => $schedule['notes'] ?? null,
                        ]);
                        Log::info('Schedule created', ['day' => $day, 'vendor_id' => $vendor->id]);
                    }
                }
            }

            // Create services jika ada layanan
            if ($this->has_services && !empty($this->vendor_services)) {
                foreach ($this->vendor_services as $serviceData) {
                    if (!empty(trim($serviceData['name'] ?? '')) && !empty($serviceData['price'])) {
                        // Upload service image jika ada
                        $serviceImagePath = null;
                        if ($serviceData['image'] ?? null) {
                            $serviceImagePath = $serviceData['image']->store('service-images', 'public');
                        }

                        Service::create([
                            'vendor_id' => $vendor->id,
                            'name' => $serviceData['name'],
                            'price' => $serviceData['price'],
                            'description' => $serviceData['description'] ?? null,
                            'image_url' => $serviceImagePath,
                        ]);
                        Log::info('Service created', ['service' => $serviceData['name'], 'vendor_id' => $vendor->id]);
                    }
                }
            }

            // Update user info
            $user = Auth::user();
            $user->phone = $this->phone;
            $user->email = $this->email;
            $user->role = 'vendor';
            $user->save();

            Log::info('User updated to vendor', ['user_id' => $user->id]);

            session()->flash('success', 'Pendaftaran berhasil! Usaha Anda sekarang aktif.');
            return redirect()->route('vendor.dashboard');

        } catch (\Exception $e) {
            Log::error('Vendor registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('vendor.vendor-registration')
            ->layout('layouts.livewire');
    }
}