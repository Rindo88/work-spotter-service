<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Checkin;
use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class UserMap extends Component
{
    public $userLocation = null;
    public $activeVendors = [];
    public $selectedVendor = null;
    public $isLoading = false;
    public $searchRadius = 5;
    public $searchQuery = '';
    public $filterCategory = 'all';

    // Manual location input
    public $manualLatitude = '';
    public $manualLongitude = '';
    public $showManualInput = false;

    public function mount()
    {
        // Load userLocation dari session jika ada
        if (Session::has('user_last_location')) {
            $this->userLocation = Session::get('user_last_location');
            $this->findActiveVendors();
        }

        Log::info('🔵 UserMap mounted', [
            'user_id' => Auth::id(),
            'has_location' => !is_null($this->userLocation)
        ]);
    }



    #[On('set-user-location')]
    public function handleSetUserLocation($latitude, $longitude)
    {
        $this->setUserLocation($latitude, $longitude);
    }

    #[On('set-manual-location')]  
    public function handleSetManualLocation($latitude, $longitude)
    {
        $this->setManualLocation($latitude, $longitude);
    }


    public function getCurrentLocation()
    {
        Log::info('📍 User mendapatkan lokasi');
        $this->isLoading = true;
        $this->showManualInput = false;
        $this->dispatch('request-user-location');
    }

    public function setUserLocation($latitude, $longitude)
    {
        Log::info('📍 User set lokasi', [
            'user_id' => Auth::id(),
            'lat' => $latitude,
            'lng' => $longitude
        ]);

        $this->userLocation = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        Session::put('user_last_location', $this->userLocation);
        $this->isLoading = false;
        $this->findActiveVendors();
    }

    public function setManualLocation($latitude, $longitude)
    {
        $this->userLocation = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        Session::put('user_last_location', $this->userLocation);
        $this->manualLatitude = $latitude;
        $this->manualLongitude = $longitude;
        $this->showManualInput = true;
        $this->findActiveVendors();

        Log::info('📍 Lokasi manual user dipilih', [
            'lat' => $latitude,
            'lng' => $longitude
        ]);
    }

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

        Session::put('user_last_location', $this->userLocation);
        $this->showManualInput = false;
        $this->findActiveVendors();

        $this->dispatch('update-user-map-location', location: $this->userLocation);
        Log::info('📍 Lokasi dari input manual user', $this->userLocation);
    }

    public function toggleManualInput()
    {
        $this->showManualInput = !$this->showManualInput;
        if ($this->showManualInput && $this->userLocation) {
            $this->manualLatitude = $this->userLocation['latitude'];
            $this->manualLongitude = $this->userLocation['longitude'];
        }
    }


    public function findActiveVendors()
    {
        if (!$this->userLocation) return;

        $latitude = $this->userLocation['latitude'];
        $longitude = $this->userLocation['longitude'];

        try {
            // Ambil vendor yang aktif hari ini dengan checkin_time yang valid
            $activeCheckins = Checkin::where('status', 'checked_in')
                ->whereDate('checkin_time', today())
                ->whereNotNull('checkin_time')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->with(['vendor.user', 'vendor.category'])
                ->get();

            $this->activeVendors = $activeCheckins->map(function ($checkin) use ($latitude, $longitude) {
                $vendor = $checkin->vendor;
                
                if (!$vendor) {
                    return null;
                }

                // Hitung distance
                $vendor->distance = $this->calculateDistance(
                    $latitude,
                    $longitude,
                    $checkin->latitude,
                    $checkin->longitude
                );
                
                $vendor->checkin_time = $checkin->checkin_time;
                $vendor->checkin_id = $checkin->id;
                $vendor->latitude = $checkin->latitude;
                $vendor->longitude = $checkin->longitude;
                
                return $vendor;
            })
            ->filter()
            ->filter(function ($vendor) {
                return $vendor->distance <= $this->searchRadius;
            })
            ->when($this->filterCategory !== 'all', function ($collection) {
                return $collection->filter(function ($vendor) {
                    return $vendor->category_id == $this->filterCategory;
                });
            })
            ->when($this->searchQuery, function ($collection) {
                $searchLower = strtolower($this->searchQuery);
                return $collection->filter(function ($vendor) use ($searchLower) {
                    return str_contains(strtolower($vendor->business_name), $searchLower) || 
                           str_contains(strtolower($vendor->description), $searchLower);
                });
            })
            ->sortBy('distance')
            ->values();

            // Dispatch event ke JavaScript untuk update vendor markers
            $this->dispatch('update-vendor-markers', vendors: $this->activeVendors->toArray());

            Log::info('📍 Processed active vendors for user', [
                'count' => $this->activeVendors->count(),
                'radius' => $this->searchRadius
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error mencari vendor aktif:', ['error' => $e->getMessage()]);
            $this->activeVendors = collect();
        }
    }

    public function updatedSearchRadius()
    {
        $this->findActiveVendors();
    }

    public function updatedSearchQuery()
    {
        $this->findActiveVendors();
    }

    public function updatedFilterCategory()
    {
        $this->findActiveVendors();
    }

    public function selectVendor($vendorId)
    {
        $this->selectedVendor = collect($this->activeVendors)
            ->firstWhere('id', $vendorId);

        if ($this->selectedVendor) {
            $this->dispatch('focus-on-vendor', [
                'latitude' => $this->selectedVendor->latitude,
                'longitude' => $this->selectedVendor->longitude,
                'vendor_name' => $this->selectedVendor->business_name
            ]);

            Log::info('📍 Vendor dipilih oleh user', [
                'vendor_id' => $this->selectedVendor->id,
                'vendor_name' => $this->selectedVendor->business_name
            ]);
        }
    }

    public function clearSelectedVendor()
    {
        $this->selectedVendor = null;
    }

    public function startChatWithVendor($vendorId)
    {
        return redirect()->route('chat.room', ['vendorId' => $vendorId]);
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

    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    public function render()
    {
        return view('livewire.user.user-map');
    }
}

