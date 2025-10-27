<?php
// app/Livewire/Vendor/LocationManager.php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LocationManager extends Component
{
    public $address;
    public $latitude;
    public $longitude;
    public $is_mobile = false;
    public $operational_area;
    public $successMessage;

    protected $listeners = [
        'location-updated' => 'handleLocationUpdate',
        'address-updated' => 'handleAddressUpdate'
    ];

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

    public function handleLocationUpdate($location)
    {
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];
        Log::info('Location updated via event', $location);
    }

    public function handleAddressUpdate($address)
    {
        $this->address = $address;
        Log::info('Address updated via event', ['address' => $address]);
    }

    public function getCurrentLocation()
    {
        $this->js(<<<JS
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung browser Anda.');
                return;
            }

            const btn = document.getElementById('getLocationBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bx bx-loader spinner"></i> Mendeteksi Lokasi...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;

                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    console.log('📍 GPS Location:', lat, lng);
                    
                    // Update Livewire properties
                    \$wire.set('latitude', lat);
                    \$wire.set('longitude', lng);
                    
                    // Update map marker
                    if (window.updateVendorMarker) {
                        window.updateVendorMarker(lat, lng);
                    }
                    
                    // Reverse geocode to get address
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=\${lat}&lon=\${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                \$wire.set('address', data.display_name);
                                console.log('📫 Address found:', data.display_name);
                            }
                        })
                        .catch(error => {
                            console.error('Reverse geocode error:', error);
                            // Fallback address
                            \$wire.set('address', 'Lokasi: ' + lat + ', ' + lng);
                        });
                },
                (error) => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    
                    let message = 'Gagal mendapatkan lokasi: ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message += 'Izin lokasi ditolak. Izinkan akses lokasi di browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message += 'Informasi lokasi tidak tersedia. Pastikan GPS aktif.';
                            break;
                        case error.TIMEOUT:
                            message += 'Permintaan lokasi timeout. Coba lagi.';
                            break;
                        default:
                            message += 'Error tidak diketahui.';
                    }
                    alert(message);
                    console.error('Geolocation error:', error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
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