{{-- resources/views/livewire/checkin/checkin-map.blade.php --}}
<div>
    @dd(session()->all())
    <!-- Header -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">Check-in Pedagang</h5>
                    <small class="text-muted">Aktifkan lokasi Anda untuk hari ini</small>
                </div>
                @if($currentCheckin)
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i>Aktif
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Messages -->
    @error('checkin')
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    </div>
    @enderror

    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ $successMessage }}
        <button type="button" class="btn-close" wire:click="$set('successMessage', '')"></button>
    </div>
    @endif

    <!-- Current Check-in Status -->
    @if($currentCheckin && $currentCheckin->vendor)
    <div class="card border-0 shadow-sm mb-3 bg-success bg-opacity-10">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 text-success">
                        <i class="bi bi-geo-alt me-2"></i>Anda sedang aktif di:
                    </h6>
                    <p class="mb-0 fw-bold">{{ $currentCheckin->vendor->business_name }}</p>
                    <small class="text-muted">
                        Sejak {{ $currentCheckin->checkin_time->format('H:i') }}
                    </small>
                </div>
                <button class="btn btn-outline-danger btn-sm" wire:click="checkout">
                    <i class="bi bi-power me-1"></i>Nonaktifkan
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Map Section -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <div id="checkinMap" style="height: 250px; width: 100%;" wire:ignore></div>
        </div>
    </div>

    <!-- Single Location Button -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            @if(!$currentCheckin)
            <button class="btn btn-primary btn-lg w-100 py-3" 
                    onclick="getUserLocation()"
                    id="getLocationBtn">
                <i class="bi bi-geo-alt-fill me-2 fs-5"></i>
                <span class="fs-6">📍 Aktifkan Lokasi Saya</span>
            </button>
            @else
            <div class="p-3 bg-success bg-opacity-10 rounded">
                <i class="bi bi-check-circle-fill text-success me-2 fs-4"></i>
                <div class="text-success fw-bold">Lokasi Aktif!</div>
                <small class="text-muted">Anda sudah check-in hari ini</small>
            </div>
            @endif
            
            @if($userLocation && !$currentCheckin)
            <div class="mt-3 p-3 bg-info bg-opacity-10 rounded">
                <i class="bi bi-info-circle text-info me-2"></i>
                <span class="text-info fw-bold">Lokasi Ditemukan!</span>
                <br>
                <small class="text-muted">
                    Pilih vendor di bawah untuk check-in
                </small>
            </div>
            @endif
        </div>
    </div>

    <!-- Nearby Vendors Section -->
    @if($userLocation && count($nearbyVendors) > 0 && !$currentCheckin)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                <i class="bi bi-shop me-2 text-primary"></i>
                Pilih Lokasi Berjualan
                <span class="badge bg-primary ms-2">{{ count($nearbyVendors) }}</span>
            </h5>
            <small class="text-muted">Pilih tempat Anda berjualan hari ini</small>
        </div>
        <div class="card-body">
            @foreach($nearbyVendors as $vendor)
            <div class="vendor-card mb-3 p-3 border rounded-3"
                 style="cursor: pointer; transition: all 0.3s;"
                 onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                 onclick="selectVendor({{ $vendor->id }})">
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Vendor Avatar -->
                    <img src="{{ $vendor->profile_picture ? asset('storage/' . $vendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->business_name) . '&background=92B6B1&color=fff&size=128' }}" 
                         class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">

                    <!-- Vendor Info -->
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold text-dark">{{ $vendor->business_name }}</h6>
                        
                        <p class="text-muted small mb-2">{{ $vendor->description }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-geo-alt me-1"></i>{{ number_format($vendor->distance, 2) }} km
                            </span>
                            <span class="badge {{ $vendor->type === 'formal' ? 'bg-info' : 'bg-warning' }} text-dark">
                                {{ $vendor->type === 'formal' ? '🏢 Formal' : '🛵 Informal' }}
                            </span>
                        </div>
                    </div>

                    <!-- Check-in Button -->
                    <button class="btn btn-primary px-3" 
                            onclick="event.stopPropagation(); selectVendor({{ $vendor->id }});">
                        <i class="bi bi-geo-alt me-1"></i>
                        Pilih
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @elseif($userLocation && !$currentCheckin)
    <!-- No Vendors Found -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-geo-alt-x fs-1 text-muted mb-3"></i>
            <h6 class="text-muted">Tidak ada vendor terdekat</h6>
            <p class="text-muted small mb-0">Tidak ada lokasi berjualan dalam radius 2km</p>
        </div>
    </div>
    @endif

    <!-- Checkin Confirmation Modal -->
    @if($selectedVendor)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" 
         onclick="closeModal()">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        Konfirmasi Aktifkan Lokasi
                    </h5>
                    <button type="button" class="btn-close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <!-- Vendor Info -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $selectedVendor->profile_picture ? asset('storage/' . $selectedVendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($selectedVendor->business_name) . '&background=92B6B1&color=fff' }}" 
                             class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">{{ $selectedVendor->business_name }}</h6>
                            <p class="text-muted small mb-2">{{ $selectedVendor->description }}</p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ number_format($this->calculateDistance($userLocation['latitude'], $userLocation['longitude'], $selectedVendor->latitude, $selectedVendor->longitude), 2) }} km
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Message -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Anda akan mengaktifkan lokasi berjualan di sini untuk hari ini.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="checkin" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check-circle me-1"></i>Ya, Aktifkan
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #checkinMap { 
        border-radius: 12px; 
        min-height: 250px;
    }
    .vendor-card {
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Global variables
let map;

// Initialize map
function initMap() {
    console.log('🗺️ Initializing map...');
    
    try {
        map = L.map('checkinMap').setView([-6.2, 106.8], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
        console.log('✅ Map initialized successfully');
    } catch (error) {
        console.error('❌ Map initialization failed:', error);
    }
}

// Get user location
function getUserLocation() {
    console.log('📍 Getting user location...');
    
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung geolocation');
        return;
    }

    // Show loading state
    const btn = document.getElementById('getLocationBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-geo-alt me-2"></i>🔄 Mencari Lokasi...';

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const location = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            };
            
            console.log('✅ Location found:', location);
            
            // Update map
            updateUserLocation(location);
            
            // Send to Livewire - menggunakan method call langsung
            @this.call('setUserLocation', location.latitude, location.longitude);
            
            // Reset button
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-geo-alt me-2"></i>📍 Aktifkan Lokasi Saya';
        },
        (error) => {
            console.error('❌ Location error:', error);
            let message = 'Gagal mendapatkan lokasi. ';
            switch(error.code) {
                case 1: message += 'Izinkan akses lokasi di browser.'; break;
                case 2: message += 'Lokasi tidak tersedia.'; break;
                case 3: message += 'Waktu permintaan habis.'; break;
                default: message += 'Error tidak diketahui.';
            }
            alert(message);
            
            // Reset button
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-geo-alt me-2"></i>📍 Aktifkan Lokasi Saya';
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 60000
        }
    );
}

// Update user location on map
function updateUserLocation(location) {
    if (!map) {
        console.error('Map not initialized');
        return;
    }

    const { latitude, longitude } = location;
    
    // Update map view
    map.setView([latitude, longitude], 15);
    
    // Add marker
    L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup('<b>📍 Lokasi Anda</b>')
        .openPopup();

    console.log('✅ User location updated on map');
}

// Select vendor
function selectVendor(vendorId) {
    console.log('🎯 Selecting vendor:', vendorId);
    @this.call('selectVendor', vendorId);
}

// Close modal
function closeModal() {
    console.log('❌ Closing modal');
    @this.call('clearSelectedVendor');
}

// Livewire event listeners
document.addEventListener('livewire:initialized', () => {
    console.log('✅ Livewire initialized for checkin');

    // Initialize map
    setTimeout(initMap, 100);
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 Checkin page loaded');
});
</script>
@endpush