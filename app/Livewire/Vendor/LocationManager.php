<?php
// app/Livewire/Vendor/LocationManager.php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LocationManager extends Component
{
    public $address;
    public $latitude;
    public $longitude;
    public $is_mobile = false;
    public $operational_area;
    public $successMessage;

    public function mount()
    {
        $this->loadVendorData();
    }

    public function loadVendorData()
    {
        $user = Auth::user();
        
        if ($user && $user->vendor) {
            $vendor = $user->vendor;
            $this->address = $vendor->address;
            $this->latitude = $vendor->latitude;
            $this->longitude = $vendor->longitude;
            $this->is_mobile = $vendor->type === 'informal';
            $this->operational_area = $vendor->operational_area;
        }
    }

    public function getCurrentLocation()
    {
        // Hanya panggil JavaScript, tidak perlu check di PHP
        $this->js(<<<JS
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung browser Anda.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Update Livewire properties
                    \$wire.set('latitude', lat);
                    \$wire.set('longitude', lng);
                    
                    // Reverse geocode to get address
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=\${lat}&lon=\${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                \$wire.set('address', data.display_name);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                },
                (error) => {
                    alert('Tidak dapat mendapatkan lokasi saat ini: ' + error.message);
                }
            );
        JS);
    }

    public function saveLocation()
    {
        $user = Auth::user();
        
        if (!$user || !$user->vendor) {
            $this->addError('general', 'Vendor tidak ditemukan.');
            return;
        }

        $this->validate([
            'address' => 'required|string|min:10',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'operational_area' => 'nullable|string|max:255',
        ]);

        $vendor = $user->vendor;
        
        $vendor->update([
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'operational_area' => $this->operational_area,
        ]);

        $this->successMessage = 'Lokasi berhasil diperbarui!';
        
        // Clear success message after 3 seconds
        $this->dispatch('clear-success-message');
    }

    public function render()
    {
        return view('livewire.vendor.location-manager');
    }
}