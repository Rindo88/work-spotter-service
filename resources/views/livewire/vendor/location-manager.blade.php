{{-- resources/views/livewire/vendor/location-manager.blade.php --}}
<div>
    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" wire:ignore.self>
        <i class="bx bx-check-circle me-2"></i>
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Lokasi Utama</h6>
            <form wire:submit="saveLocation">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Lengkap *</label>
                        <div class="input-group">
                            <input type="text" class="form-control" wire:model="address" 
                                   placeholder="Masukkan alamat lengkap..." id="addressInput">
                            <button type="button" class="btn btn-outline-primary" wire:click="getCurrentLocation" wire:loading.attr="disabled">
                                <i class="bx bx-current-location"></i>
                                <span wire:loading wire:target="getCurrentLocation">Loading...</span>
                            </button>
                        </div>
                        @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="text" class="form-control" wire:model="latitude" readonly>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="text" class="form-control" wire:model="longitude" readonly>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="is_mobile" id="isMobile">
                            <label class="form-check-label" for="isMobile">
                                Saya adalah pedagang keliling (lokasi berubah-ubah)
                            </label>
                        </div>
                    </div>

                    @if($is_mobile)
                    <div class="col-12">
                        <label class="form-label fw-semibold">Area Jualan (Opsional)</label>
                        <textarea class="form-control" rows="2" wire:model="operational_area" 
                                  placeholder="Contoh: Area Malioboro, Sekitar UGM, dll..."></textarea>
                    </div>
                    @endif
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="bx bx-save me-2"></i>
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Map Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Pilih Lokasi di Peta</h6>
            <p class="text-muted small mb-3">Klik pada peta untuk menandai lokasi atau gunakan pencarian</p>
            
            <!-- Search Box -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari lokasi..." id="searchLocation">
                <button class="btn btn-outline-primary" type="button" id="searchButton">
                    <i class="bx bx-search"></i> Cari
                </button>
            </div>

            <!-- Map Container -->
            <div id="locationMap" style="height: 400px; border-radius: 8px;" class="mb-3"></div>
            
            <div class="alert alert-info">
                <i class="bx bx-info-circle me-2"></i>
                <small>Klik pada peta untuk menandai lokasi bisnis Anda. Lokasi akan otomatis tersimpan.</small>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
#locationMap { 
    height: 400px; 
    z-index: 1;
}
.leaflet-container {
    border-radius: 8px;
}
/* Loading state for buttons */
[wire\:loading] {
    opacity: 0.5;
    pointer-events: none;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Global variables
let map = null;
let marker = null;
let isMapInitialized = false;

// Function to initialize map
function initializeMap() {
    console.log('Initializing map...');
    
    // Destroy existing map if any
    if (map) {
        map.remove();
        map = null;
        marker = null;
    }

    // Get container and ensure it's visible
    const mapContainer = document.getElementById('locationMap');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }

    // Set initial coordinates
    let initialLat = @js($latitude) || -6.2088; // Default Jakarta
    let initialLng = @js($longitude) || 106.8456;
    
    // Initialize map
    map = L.map('locationMap').setView([initialLat, initialLng], 13);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Add marker if coordinates exist and are valid
    if (initialLat && initialLng && initialLat != 0 && initialLng != 0) {
        marker = L.marker([initialLat, initialLng]).addTo(map);
        map.setView([initialLat, initialLng], 15);
    }

    // Click event to set marker
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        console.log('Map clicked:', lat, lng);
        
        // Update Livewire properties
        Livewire.dispatch('update-location', { latitude: lat, longitude: lng });
        
        // Update or create marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
        
        // Reverse geocode to get address
        reverseGeocode(lat, lng);
    });

    // Initialize search functionality
    initializeSearch();
    
    // Mark as initialized
    isMapInitialized = true;
    console.log('Map initialized successfully');
}

// Function to reverse geocode coordinates to address
function reverseGeocode(lat, lng) {
    console.log('Reverse geocoding:', lat, lng);
    
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                Livewire.dispatch('update-address', { address: data.display_name });
            }
        })
        .catch(error => console.error('Reverse geocode error:', error));
}

// Function to initialize search
function initializeSearch() {
    const searchInput = document.getElementById('searchLocation');
    const searchButton = document.getElementById('searchButton');
    
    if (!searchInput || !searchButton) {
        console.error('Search elements not found');
        return;
    }
    
    searchButton.addEventListener('click', function() {
        performSearch(searchInput.value);
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch(searchInput.value);
        }
    });
}

// Function to perform location search
function performSearch(query) {
    if (!query.trim()) {
        alert('Masukkan kata kunci pencarian');
        return;
    }
    
    console.log('Searching for:', query);
    
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=id`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                console.log('Search result:', result);
                
                // Update Livewire
                Livewire.dispatch('update-location', { 
                    latitude: lat, 
                    longitude: lng 
                });
                Livewire.dispatch('update-address', { 
                    address: result.display_name 
                });
                
                // Update map view
                if (map) {
                    map.setView([lat, lng], 15);
                    
                    // Update or create marker
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                }
            } else {
                alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            alert('Terjadi kesalahan saat mencari lokasi');
        });
}

// Function to invalidate map size (fix rendering issues)
function invalidateMapSize() {
    if (map) {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }
}

// Livewire event listeners
document.addEventListener('livewire:initialized', () => {
    console.log('Livewire initialized');
    
    // Initialize map after a short delay to ensure DOM is ready
    setTimeout(() => {
        initializeMap();
    }, 500);
});

// Reinitialize map when component updates
document.addEventListener('livewire:update', () => {
    console.log('Livewire updated');
    
    if (!isMapInitialized) {
        setTimeout(() => {
            initializeMap();
        }, 500);
    } else {
        invalidateMapSize();
    }
});

// Handle component morph
document.addEventListener('livewire:morph', () => {
    console.log('Livewire morphed');
    invalidateMapSize();
});

// Listen for tab changes (if using tabs)
document.addEventListener('DOMContentLoaded', function() {
    const tabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabTriggers.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            setTimeout(() => {
                invalidateMapSize();
            }, 300);
        });
    });
});

// Custom events for location updates
Livewire.on('update-location', (data) => {
    @this.set('latitude', data.latitude);
    @this.set('longitude', data.longitude);
});

Livewire.on('update-address', (data) => {
    @this.set('address', data.address);
});

// Clear success message
Livewire.on('clear-success-message', () => {
    setTimeout(() => {
        @this.set('successMessage', null);
    }, 3000);
});
</script>
@endpush