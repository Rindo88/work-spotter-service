{{-- resources/views/livewire/checkin/checkin-map.blade.php --}}
<div>
    <!-- Cek akses vendor -->
    @if(!auth()->user()->isVendor())
    <div class="alert alert-danger">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>Akses Ditolak</strong>
        <p class="mb-0">Halaman ini hanya untuk pedagang terdaftar.</p>
    </div>
    @else
    <!-- 1. HEADER -->
    <div class="card border-0 shadow-sm mb-3 bg-primary text-white">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">
                        <i class="bi bi-shop me-2"></i>
                        {{ $vendorProfile->business_name }}
                    </h5>
                    <p class="mb-0 opacity-75">{{ $vendorProfile->description }}</p>
                </div>
                <div class="text-end">
                    @if($currentCheckin)
                    <span class="badge bg-success fs-6">
                        <i class="bi bi-broadcast me-1"></i>AKTIF
                    </span>
                    @else
                    <span class="badge bg-secondary fs-6">
                        <i class="bi bi-eye-slash me-1"></i>NONAKTIF
                    </span>
                    @endif
                </div>
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

    <!-- 2. PETA -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-map me-2 text-primary"></i>
                    Peta Lokasi
                </h6>
                <small class="text-muted">Klik peta untuk pilih lokasi manual</small>
            </div>
        </div>
        <div class="card-body p-0 position-relative">
            <div id="checkinMap" style="height: 300px; width: 100%;" wire:ignore></div>
            <!-- Search Box -->
            <div class="position-absolute top-0 start-0 m-3">
                <div class="input-group input-group-sm">
                    <input type="text" id="searchBox" class="form-control" placeholder="Cari lokasi..." style="width: 200px;">
                    <button class="btn btn-primary" type="button" onclick="searchLocation()">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. INPUT MANUAL (Toggle) -->
    @if($showManualInput)
    <div class="card border-0 shadow-sm mb-3 border-primary">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-geo-alt me-2"></i>Input Lokasi Manual
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-6">
                    <label class="form-label small">Latitude</label>
                    <input type="number" step="any" class="form-control" wire:model="manualLatitude" 
                           placeholder="-6.123456">
                </div>
                <div class="col-6">
                    <label class="form-label small">Longitude</label>
                    <input type="number" step="any" class="form-control" wire:model="manualLongitude"
                           placeholder="106.123456">
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill" wire:click="setLocationFromInput">
                    <i class="bi bi-check-circle me-1"></i>Gunakan Lokasi Ini
                </button>
                <button class="btn btn-outline-secondary" wire:click="toggleManualInput">
                    <i class="bi bi-x me-1"></i>Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- 4. TOMBOL CHECKIN -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            @if($currentCheckin)
            <!-- Status Aktif -->
            <div class="p-3 bg-success bg-opacity-10 rounded">
                <i class="bi bi-broadcast-pin text-success me-2 fs-4"></i>
                <div class="text-success fw-bold">Lokasi Aktif!</div>
                <small class="text-muted">Anda terlihat oleh pelanggan</small>
                <div class="mt-3">
                    <button class="btn btn-outline-danger" wire:click="checkout">
                        <i class="bi bi-power me-1"></i>Nonaktifkan Lokasi
                    </button>
                </div>
            </div>
            @else
            <!-- Tombol Checkin Dinamis -->
            @if(!$userLocation)
            <!-- Belum ada lokasi -->
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-lg py-3" 
                        onclick="getUserLocation()"
                        id="getLocationBtn"
                        {{ $isLoading ? 'disabled' : '' }}>
                    <i class="bi bi-geo-alt me-2"></i>
                    <span>
                        @if($isLoading)
                        🔄 Mendeteksi Lokasi...
                        @else
                        📍 Ambil Lokasi Saya
                        @endif
                    </span>
                </button>
                <button class="btn btn-outline-primary" wire:click="toggleManualInput">
                    <i class="bi bi-geo me-1"></i>Input Lokasi Manual
                </button>
            </div>
            @else
            <!-- Sudah ada lokasi - Konfirmasi Checkin -->
            <div class="p-3 bg-info bg-opacity-10 rounded">
                <i class="bi bi-check-circle text-info me-2"></i>
                <span class="text-info fw-bold">Lokasi Siap!</span>
                <p class="mb-2 small">
                    {{ number_format($userLocation['latitude'], 6) }}, {{ number_format($userLocation['longitude'], 6) }}
                </p>
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-lg py-3" wire:click="checkin" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check-circle me-1"></i>Konfirmasi Check-in
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Mengaktifkan...
                        </span>
                    </button>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary flex-fill" onclick="getUserLocation()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Ambil Ulang Lokasi
                        </button>
                        <button class="btn btn-outline-secondary flex-fill" wire:click="toggleManualInput">
                            <i class="bi bi-pencil me-1"></i>Edit Manual
                        </button>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    <!-- 5. PEDAGANG TERDEKAT -->
    @if($userLocation && count($nearbyActiveVendors) > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0">
                <i class="bi bi-people me-2 text-info"></i>
                Pedagang Aktif Terdekat
                <span class="badge bg-info ms-2">{{ count($nearbyActiveVendors) }}</span>
            </h6>
            <small class="text-muted">Dalam radius 5km dari Anda</small>
        </div>
        <div class="card-body">
            @foreach($nearbyActiveVendors as $vendor)
            <div class="vendor-info mb-2 p-3 border rounded">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $vendor->profile_picture ? asset('storage/' . $vendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->business_name) . '&background=6c757d&color=fff&size=64' }}" 
                         class="rounded-2" style="width: 50px; height: 50px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">{{ $vendor->business_name }}</h6>
                        <p class="text-muted small mb-2">{{ $vendor->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-geo-alt me-1"></i>{{ number_format($vendor->distance, 1) }} km
                            </span>
                            <span class="badge bg-success">
                                <i class="bi bi-clock me-1"></i>Aktif {{ $vendor->checkin_time->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @elseif($userLocation)
    <!-- Tidak ada pedagang terdekat -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-4">
            <i class="bi bi-people fs-1 text-muted mb-3"></i>
            <h6 class="text-muted">Tidak ada pedagang aktif terdekat</h6>
            <p class="text-muted small mb-0">Anda akan menjadi yang pertama di area ini!</p>
        </div>
    </div>
    @endif

    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    #checkinMap { 
        border-radius: 12px; 
        min-height: 300px;
    }
    .vendor-info {
        transition: all 0.3s ease;
    }
    .vendor-info:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
    .leaflet-control-geocoder {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
// Global variables
let map;
let userMarker;
let clickMarker;

// Initialize map with search
function initMap() {
    console.log('🗺️ Initializing vendor map with search...');
    
    try {
        map = L.map('checkinMap').setView([-6.2, 106.8], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Add search control
        L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: 'Cari lokasi...',
            errorMessage: 'Lokasi tidak ditemukan.'
        })
        .on('markgeocode', function(e) {
            const { center, name } = e.geocode;
            map.setView(center, 16);
            
            // Set manual location
            const location = {
                latitude: center.lat,
                longitude: center.lng
            };
            
            updateManualLocation(location, name);
        })
        .addTo(map);

        // Click event untuk pilih lokasi manual
        map.on('click', function(e) {
            const location = {
                latitude: e.latlng.lat,
                longitude: e.latlng.lng
            };
            
            updateManualLocation(location, 'Lokasi yang dipilih');
        });
        
        console.log('✅ Vendor map with search initialized successfully');
    } catch (error) {
        console.error('❌ Map initialization failed:', error);
    }
}

// Update manual location dari klik peta atau search
function updateManualLocation(location, locationName) {
    const { latitude, longitude } = location;
    
    // Update map view
    map.setView([latitude, longitude], 16);
    
    // Remove existing click marker
    if (clickMarker) {
        map.removeLayer(clickMarker);
    }
    
    // Add new marker untuk lokasi manual
    clickMarker = L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup(`<b>${locationName}</b><br>Klik "Input Lokasi Manual" untuk menggunakan`)
        .openPopup();

    // Kirim ke Livewire
    @this.call('setManualLocation', latitude, longitude);
    
    console.log('📍 Manual location selected:', location);
}

// Get user location via GPS
function getUserLocation() {
    console.log('📍 Getting user location via GPS...');
    
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung geolocation');
        return;
    }

    const btn = document.getElementById('getLocationBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-geo-alt me-2"></i>🔄 Mendeteksi Lokasi...';

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const location = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            };
            
            console.log('✅ GPS location found:', location);
            
            updateGPSLocation(location);
            
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-geo-alt me-2"></i>📍 Ambil Lokasi Saya';
        },
        (error) => {
            console.error('❌ GPS location error:', error);
            let message = 'Gagal mendapatkan lokasi. ';
            switch(error.code) {
                case 1: message += 'Izinkan akses lokasi di browser.'; break;
                case 2: message += 'Lokasi tidak tersedia.'; break;
                case 3: message += 'Waktu permintaan habis.'; break;
                default: message += 'Error tidak diketahui.';
            }
            alert(message);
            
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-geo-alt me-2"></i>📍 Ambil Lokasi Saya';
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 60000
        }
    );
}

// Update GPS location
function updateGPSLocation(location) {
    if (!map) return;

    const { latitude, longitude } = location;
    
    map.setView([latitude, longitude], 16);
    
    // Remove existing markers
    if (userMarker) map.removeLayer(userMarker);
    if (clickMarker) map.removeLayer(clickMarker);
    
    // Add GPS marker
    userMarker = L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup('<b>📍 Lokasi GPS Anda</b><br>Klik "Konfirmasi Check-in"')
        .openPopup();

    // Kirim ke Livewire
    @this.call('setUserLocation', latitude, longitude);
}

// Search location function
function searchLocation() {
    const searchInput = document.getElementById('searchBox');
    const query = searchInput.value.trim();
    
    if (!query) {
        alert('Masukkan nama lokasi yang ingin dicari');
        return;
    }

    // Use Leaflet geocoder untuk search
    const geocoder = L.Control.Geocoder.nominatim();
    geocoder.geocode(query, function(results) {
        if (results && results.length > 0) {
            const result = results[0];
            const location = {
                latitude: result.center.lat,
                longitude: result.center.lng
            };
            
            updateManualLocation(location, result.name);
            searchInput.value = ''; // Clear search input
        } else {
            alert('Lokasi tidak ditemukan. Coba dengan nama yang lebih spesifik.');
        }
    });
}

// Livewire event listeners
document.addEventListener('livewire:initialized', () => {
    console.log('✅ Vendor checkin Livewire initialized');
    
    setTimeout(initMap, 100);
    
    // Listen untuk update map dari Livewire
    window.Livewire.on('update-map-location', (event) => {
        console.log('🗺️ Updating map from Livewire:', event.location);
        updateManualLocation(event.location, 'Lokasi dari input manual');
    });
});

// Enter key untuk search
document.addEventListener('DOMContentLoaded', function() {
    const searchBox = document.getElementById('searchBox');
    if (searchBox) {
        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchLocation();
            }
        });
    }
});
</script>
@endpush