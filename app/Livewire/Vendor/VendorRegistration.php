<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vendor;
use App\Models\VendorSchedule;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

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
    
    // Schedule fields (digunakan untuk komunikasi dengan child component)
    public $has_schedule = false;
    public $schedules = [];
    public $address_auto;

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
        'business_images.*' => 'image|max:2048',
        
        // Informal vendor rules
        'is_mobile' => 'boolean',
        'operational_notes' => 'nullable|string|max:500',
        'has_schedule' => 'boolean',
    ];

    // Event listeners untuk komunikasi dengan child component
    protected $listeners = [
        'schedule-updated' => 'handleScheduleUpdate',
        'schedule-toggle' => 'handleScheduleToggle'
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->phone = Auth::user()->phone;
        $this->email = Auth::user()->email;
    }

    public function setVendorType($type)
    {
        $this->vendorType = $type;
        $this->currentStep = 1;
        $this->progress = 33;
        $this->resetValidation();
        
        // Reset schedule ketika ganti vendor type
        $this->dispatch('resetSchedules');
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'business_name' => 'required|min:3|max:255|unique:vendors,business_name',
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|min:10|max:1000',
            ]);
        } 
        elseif ($this->currentStep === 2) {
            $validationRules = [
                'phone' => 'required|regex:/^[0-9]{9,13}$/',
                'email' => 'required|email',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ];
            
            $this->validate($validationRules);
        }

        $this->currentStep++;
        $this->progress = ($this->currentStep / 3) * 100;
    }

    public function previousStep()
    {
        $this->currentStep--;
        $this->progress = ($this->currentStep / 3) * 100;
    }

    // Handler untuk update schedule dari child component
    public function handleScheduleUpdate($schedules)
    {
        $this->schedules = $schedules;
    }

    // Handler untuk toggle schedule dari child component
    public function handleScheduleToggle($hasSchedule)
    {
        $this->has_schedule = $hasSchedule;
        if (!$hasSchedule) {
            $this->schedules = [];
        }
    }

    public function submit()
    {
        // Validasi untuk step 3
        $validationRules = [
            'business_images' => 'required|array|min:1|max:4',
            'business_images.*' => 'image|max:2048',
        ];

        $this->validate($validationRules);

        try {
            // Upload images
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

            // Create schedules jika ada jadwal
            if ($this->has_schedule && !empty($this->schedules)) {
                foreach ($this->schedules as $day => $schedule) {
                    if (!$schedule['closed']) {
                        VendorSchedule::create([
                            'vendor_id' => $vendor->id,
                            'day' => $day,
                            'open_time' => $schedule['open'],
                            'close_time' => $schedule['close'],
                            'is_closed' => false,
                        ]);
                    }
                }
            }

            // Update user info
            $user = Auth::user();
            $user->phone = $this->phone;
            $user->email = $this->email;
            $user->role = 'vendor';
            $user->save();

            session()->flash('success', 'Pendaftaran berhasil!');
            return redirect()->route('home');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('vendor.vendor-registration')
            ->layout('layouts.vendor-registration');
    }
}