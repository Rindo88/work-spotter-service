<?php
// app/Livewire/Vendor/ProfileManager.php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class ProfileManager extends Component
{
    use WithFileUploads;

    public $business_name;
    public $description;
    public $category_id;
    public $address;
    public $latitude;
    public $longitude;
    public $profile_picture;
    public $operational_notes;
    public $type = 'informal';
    public $is_rfid = false;
    public $successMessage;

    public function mount()
    {
        $user = Auth::user();
        
        if ($user && $user->vendor) {
            $vendor = $user->vendor;
            $this->business_name = $vendor->business_name;
            $this->description = $vendor->description;
            $this->category_id = $vendor->category_id;
            $this->address = $vendor->address;
            $this->latitude = $vendor->latitude;
            $this->longitude = $vendor->longitude;
            $this->operational_notes = $vendor->operational_notes;
            $this->type = $vendor->type;
            $this->is_rfid = $vendor->is_rfid;
        }
    }

    public function saveProfile()
    {
        $user = Auth::user();
        
        if (!$user || !$user->vendor) {
            $this->addError('general', 'Vendor tidak ditemukan.');
            return;
        }

        $this->validate([
            'business_name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'address' => 'required|string|min:10',
            'profile_picture' => 'nullable|image|max:2048',
            'operational_notes' => 'nullable|string|max:500',
        ]);

        $vendor = $user->vendor;
        
        if ($this->profile_picture) {
            $logoPath = $this->profile_picture->store('vendor-profile-pictures', 'public');
            $vendor->profile_picture = $logoPath;
        }

        $vendor->update([
            'business_name' => $this->business_name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'operational_notes' => $this->operational_notes,
            'type' => $this->type,
            'is_rfid' => $this->is_rfid,
        ]);

        $this->successMessage = 'Profile vendor berhasil diperbarui!';
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.vendor.profile-manager', compact('categories'));
    }
}