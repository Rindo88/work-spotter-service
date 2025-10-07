<?php
// app/Livewire/Profile/VendorProfileComponent.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class VendorProfileComponent extends Component
{
    use WithFileUploads;

    public $business_name;
    public $description;
    public $address;
    public $profile_picture;
    public $operational_notes;
    public $successMessage;

    public function mount()
    {
        $user = Auth::user();
        
        // Pastikan user dan vendor tidak null
        if ($user && $user->vendor) {
            $vendor = $user->vendor;
            $this->business_name = $vendor->business_name;
            $this->description = $vendor->description;
            $this->address = $vendor->address;
            $this->operational_notes = $vendor->operational_notes;
        }
    }

    public function saveProfile()
    {
        $user = Auth::user();
        
        // Pastikan user dan vendor tidak null
        if (!$user || !$user->vendor) {
            return;
        }

        $this->validate([
            'business_name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
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
            'address' => $this->address,
            'operational_notes' => $this->operational_notes,
        ]);

        $this->successMessage = 'Profile vendor berhasil diperbarui!';
    }

    public function render()
    {
        $user = Auth::user();
        $vendor = $user ? $user->vendor : null;
        
        return view('livewire.profile.vendor-profile-component', [
            'user' => $user,
            'vendor' => $vendor,
        ]);
    }
}