<?php
// app/Livewire/Checkin/CheckinMap.php

namespace App\Livewire\Checkin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Checkin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckinMap extends Component
{
    public $userLocation = null;
    public $nearbyActiveVendors = [];
    public $isCheckingIn = false;
    public $successMessage = '';
    public $currentCheckin = null;
    public $isLoading = false;
    public $vendorProfile = null;

    // Manual location input
    public $manualLatitude = '';
    public $manualLongitude = '';
    public $showManualInput = false;

    public function mount()
    {
        if (!Auth::user()->isVendor()) {
            Log::warning('❌ User bukan vendor mencoba akses checkin', ['user_id' => Auth::id()]);
            return;
        }

        // Load userLocation dari session jika ada
        if (Session::has('vendor_last_location')) {
            $this->userLocation = Session::get('vendor_last_location');
            $this->findNearbyActiveVendors(); // Auto load nearby vendors
        }

        Log::info('🔵 Vendor CheckinMap mounted', [
            'user_id' => Auth::id(),
            'vendor_id' => Auth::user()->vendor->id,
            'has_location' => !is_null($this->userLocation)
        ]);

        $this->vendorProfile = Auth::user()->vendor;
        $this->currentCheckin = Auth::user()->currentCheckin();
    }

    public function getCurrentLocation()
    {
        Log::info('📍 Vendor mendapatkan lokasi', ['vendor_id' => $this->vendorProfile->id]);
        $this->isLoading = true;
        $this->showManualInput = false;

        $this->dispatch('request-location');
    }

    public function setUserLocation($latitude, $longitude)
    {
        Log::info('📍 Vendor set lokasi', [
            'vendor_id' => $this->vendorProfile->id,
            'lat' => $latitude,
            'lng' => $longitude
        ]);

        $this->userLocation = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        // SIMPAN KE SESSION
        Session::put('vendor_last_location', $this->userLocation);

        $this->isLoading = false;
        $this->findNearbyActiveVendors();
    }

    // Method untuk set lokasi manual dari peta
    public function setManualLocation($latitude, $longitude)
    {
        $this->userLocation = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        // SIMPAN KE SESSION
        Session::put('vendor_last_location', $this->userLocation);

        $this->manualLatitude = $latitude;
        $this->manualLongitude = $longitude;
        $this->showManualInput = true;

        $this->findNearbyActiveVendors();

        Log::info('📍 Lokasi manual dipilih', [
            'lat' => $latitude,
            'lng' => $longitude
        ]);
    }

    // Method untuk set lokasi dari input manual
    public function setLocationFromInput()
    {
        $this->validate([
            'manualLatitude' => 'required|numeric|between:-90,90',
            'manualLongitude' => 'required|numeric|between:-180,180',
        ]);

        $this->userLocation = [
            'latitude' => (float) $this->manualLatitude,
            'longitude' => (float) $this->manualLongitude
        ];

        // SIMPAN KE SESSION
        Session::put('vendor_last_location', $this->userLocation);

        $this->showManualInput = false;
        $this->findNearbyActiveVendors();

        // Update map via JavaScript
        $this->dispatch('update-map-location', location: $this->userLocation);

        Log::info('📍 Lokasi dari input manual', $this->userLocation);
    }

    public function toggleManualInput()
    {
        $this->showManualInput = !$this->showManualInput;
        if ($this->showManualInput && $this->userLocation) {
            $this->manualLatitude = $this->userLocation['latitude'];
            $this->manualLongitude = $this->userLocation['longitude'];
        }
    }

    public function findNearbyActiveVendors()
    {
        if (!$this->userLocation) return;

        $latitude = $this->userLocation['latitude'];
        $longitude = $this->userLocation['longitude'];

        try {
            $activeCheckins = Checkin::where('status', 'checked_in')
                ->whereDate('checkin_time', today())
                ->where('user_id', '!=', Auth::id())
                ->with('vendor')
                ->get();

            $this->nearbyActiveVendors = $activeCheckins->map(function ($checkin) use ($latitude, $longitude) {
                $vendor = $checkin->vendor;
                $vendor->distance = $this->calculateDistance(
                    $latitude,
                    $longitude,
                    $checkin->latitude,
                    $checkin->longitude
                );
                $vendor->checkin_time = $checkin->checkin_time;
                return $vendor;
            })->filter(function ($vendor) {
                return $vendor->distance <= 5;
            })->sortBy('distance')
                ->take(5)
                ->values();
        } catch (\Exception $e) {
            Log::error('❌ Error mencari pedagang aktif:', ['error' => $e->getMessage()]);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function checkin()
    {
        Log::info('🚀 ========= VENDOR CHECKIN STARTED =========', [
            'vendor_id' => $this->vendorProfile->id,
            'vendor_name' => $this->vendorProfile->business_name,
            'location' => $this->userLocation
        ]);

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
                'vendor_id' => $this->vendorProfile->id,
                'latitude' => $this->userLocation['latitude'],
                'longitude' => $this->userLocation['longitude'],
                'location_name' => $this->vendorProfile->business_name,
                'checkin_time' => now(),
                'status' => 'checked_in'
            ];

            $checkin = Checkin::create($checkinData);

            Log::info('🎉 ========= VENDOR CHECKIN SUCCESS =========', [
                'checkin_id' => $checkin->id,
                'vendor_id' => $this->vendorProfile->id
            ]);

            $this->currentCheckin = $checkin;
            $this->successMessage = '✅ Berhasil aktif di lokasi ini! Pelanggan dapat menemukan Anda.';
            $this->isCheckingIn = false;
            $this->findNearbyActiveVendors();
        } catch (\Exception $e) {
            Log::error('💥 ========= VENDOR CHECKIN FAILED =========', [
                'error' => $e->getMessage()
            ]);

            $this->isCheckingIn = false;
            dd($e);
            $this->addError('checkin', '❌ Gagal mengaktifkan lokasi: ' . $e->getMessage());
        }
    }

    public function checkout()
    {
        if (!$this->currentCheckin) {
            $this->addError('checkout', 'Tidak ada lokasi aktif');
            return;
        }

        try {
            $this->currentCheckin->update([
                'checkout_time' => now(),
                'status' => 'checked_out'
            ]);

            $this->currentCheckin = null;
            $this->successMessage = '✅ Lokasi dinonaktifkan. Anda tidak terlihat oleh pelanggan.';
        } catch (\Exception $e) {
            $this->addError('checkout', 'Gagal menonaktifkan lokasi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if (!Auth::user()->isVendor()) {
            return view('livewire.checkin.unauthorized');
        }

        return view('livewire.checkin.checkin-map');
    }
}
