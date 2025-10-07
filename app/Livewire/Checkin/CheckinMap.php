<?php
// app/Livewire/Checkin/CheckinMap.php

namespace App\Livewire\Checkin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Checkin;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;

class CheckinMap extends Component
{
    public $userLocation = null;
    public $nearbyVendors = [];
    public $selectedVendor = null;
    public $isCheckingIn = false;
    public $successMessage = '';
    public $currentCheckin = null;
    public $isLoading = false;

    // Hapus listeners yang complex
    public function mount()
    {
        Log::info('🔵 CheckinMap mounted - User: ' . Auth::id());
        $this->currentCheckin = Checkin::where('user_id', Auth::id())
            ->where('status', 'checked_in')
            ->with('vendor')
            ->first();
    }

    // Method untuk handle location dari JavaScript
    public function setUserLocation($latitude, $longitude)
    {
        Log::info('📍 Setting user location manually', [
            'lat' => $latitude,
            'lng' => $longitude
        ]);
        
        $this->userLocation = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        
        $this->isLoading = false;
        $this->findNearbyVendors();
    }

    public function getCurrentLocation()
    {
        Log::info('📍 getCurrentLocation called');
        $this->isLoading = true;
        
        // Dispatch event ke JavaScript
        $this->dispatch('request-location');
    }

    public function findNearbyVendors()
    {
        if (!$this->userLocation) {
            Log::warning('❌ No user location for finding vendors');
            return;
        }

        $latitude = $this->userLocation['latitude'];
        $longitude = $this->userLocation['longitude'];

        Log::info('🔍 Finding nearby vendors', ['lat' => $latitude, 'lng' => $longitude]);

        try {
            $vendors = Vendor::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();

            Log::info('🏪 Vendors with coordinates:', ['count' => $vendors->count()]);

            $this->nearbyVendors = $vendors->map(function ($vendor) use ($latitude, $longitude) {
                $vendor->distance = $this->calculateDistance(
                    $latitude, 
                    $longitude, 
                    $vendor->latitude, 
                    $vendor->longitude
                );
                return $vendor;
            })->filter(function ($vendor) {
                return $vendor->distance <= 2;
            })->sortBy('distance')
              ->take(10)
              ->values();

            Log::info('✅ Nearby vendors found:', ['count' => $this->nearbyVendors->count()]);

        } catch (\Exception $e) {
            Log::error('❌ Error finding vendors:', ['error' => $e->getMessage()]);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    public function selectVendor($vendorId)
    {
        Log::info('🎯 Vendor selected:', ['vendorId' => $vendorId]);
        
        $this->selectedVendor = Vendor::find($vendorId);
        Log::info('✅ Vendor set:', ['vendor' => $this->selectedVendor ? $this->selectedVendor->business_name : 'null']);
    }

    public function checkin()
    {
        Log::info('🚀 ========= CHECKIN PROCESS STARTED =========');
        Log::info('📋 Checkin data:', [
            'user_id' => Auth::id(),
            'vendor' => $this->selectedVendor ? $this->selectedVendor->id : 'NULL',
            'vendor_name' => $this->selectedVendor ? $this->selectedVendor->business_name : 'NULL',
            'location' => $this->userLocation
        ]);

        // Validasi
        if (!$this->selectedVendor) {
            $error = '❌ Pilih vendor terlebih dahulu';
            Log::error($error);
            $this->addError('checkin', $error);
            return;
        }

        if (!$this->userLocation) {
            $error = '❌ Lokasi tidak tersedia';
            Log::error($error);
            $this->addError('checkin', $error);
            return;
        }

        $this->isCheckingIn = true;

        try {
            $checkinData = [
                'user_id' => Auth::id(),
                'vendor_id' => $this->selectedVendor->id,
                'latitude' => $this->userLocation['latitude'],
                'longitude' => $this->userLocation['longitude'],
                'location_name' => $this->selectedVendor->business_name,
                'checkin_time' => now(),
                'status' => 'checked_in'
            ];

            Log::info('💾 Creating checkin record:', $checkinData);

            // CREATE CHECKIN
            $checkin = Checkin::create($checkinData);

            Log::info('🎉 ========= CHECKIN SUCCESS =========', [
                'checkin_id' => $checkin->id,
                'checkin_time' => $checkin->checkin_time
            ]);

            // Update state
            $this->currentCheckin = Checkin::with('vendor')->find($checkin->id);
            $this->successMessage = '✅ Check-in berhasil di ' . $this->selectedVendor->business_name;
            $this->selectedVendor = null;
            $this->isCheckingIn = false;

            // Refresh vendor list
            $this->findNearbyVendors();

        } catch (\Exception $e) {
            Log::error('💥 ========= CHECKIN FAILED =========', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->isCheckingIn = false;
            $this->addError('checkin', '❌ Gagal melakukan check-in: ' . $e->getMessage());
        }
    }

    public function checkout()
    {
        Log::info('🚪 Checkout called', ['checkin_id' => $this->currentCheckin?->id]);

        if (!$this->currentCheckin) {
            $this->addError('checkout', 'Tidak ada checkin aktif');
            return;
        }

        try {
            $this->currentCheckin->update([
                'checkout_time' => now(),
                'status' => 'checked_out'
            ]);

            $this->currentCheckin = null;
            $this->successMessage = '✅ Check-out berhasil!';
            
        } catch (\Exception $e) {
            $this->addError('checkout', 'Gagal check-out: ' . $e->getMessage());
        }
    }

    // Method untuk clear selected vendor
    public function clearSelectedVendor()
    {
        $this->selectedVendor = null;
    }

    public function render()
    {
        return view('livewire.checkin.checkin-map');
    }
}