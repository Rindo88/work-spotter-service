{{-- resources/views/livewire/vendor/location-manager.blade.php --}}
<div>
    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Map Section di ATAS -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bx bx-map me-2 text-primary"></i>
                Pilih Lokasi di Peta
            </h6>
            
            <!-- Search Box -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari lokasi..." id="searchLocation">
                <button class="btn btn-outline-primary" type="button" id="searchButton">
                    <i class="bx bx-search"></i> Cari
                </button>
            </div>

            <!-- Map Container -->
            <div id="vendor-location-map" style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ddd;" wire:ignore></div>
            
            <!-- Koordinat Display -->
            <div class="mt-3 p-3 bg-light rounded">
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted d-block">Latitude</small>
                        <strong class="text-primary" id="latitudeDisplay">
                            {{ $latitude ? number_format($latitude, 6) : 'Belum dipilih' }}
                        </strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Longitude</small>
                        <strong class="text-primary" id="longitudeDisplay">
                            {{ $longitude ? number_format($longitude, 6) : 'Belum dipilih' }}
                        </strong>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="bx bx-info-circle me-2"></i>
                <small>Klik pada peta untuk menandai lokasi bisnis Anda atau gunakan pencarian di atas.</small>
            </div>
        </div>
    </div>

    <!-- Tombol Lokasi Otomatis -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            <h6 class="fw-bold mb-3">
                <i class="bx bx-current-location me-2 text-primary"></i>
                Ambil Lokasi Otomatis
            </h6>
            
            <button type="button" class="btn btn-primary btn-lg px-4 py-3" id="getLocationBtn" wire:click="getCurrentLocation">
                <i class="bx bx-current-location me-2"></i>
                Dapatkan Lokasi Saya
            </button>
            
            <div class="mt-2">
                <small class="text-muted">
                    <i class="bx bx-info-circle me-1"></i>
                    Izinkan akses lokasi di browser untuk menggunakan fitur ini
                </small>
            </div>
        </div>
    </div>

    <!-- Form Alamat -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bx bx-map-pin me-2 text-primary"></i>
                Informasi Lokasi
            </h6>
            
            <form wire:submit="saveLocation">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Lengkap *</label>
                        <textarea class="form-control" wire:model="address" 
                                  rows="3" placeholder="Masukkan alamat lengkap..." 
                                  id="addressInput" required></textarea>
                        @error('address') 
                            <span class="text-danger small">{{ $message }}</span> 
                        @enderror
                        <div class="form-text">
                            Alamat akan otomatis terisi saat memilih lokasi di peta
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="is_mobile" id="isMobile">
                            <label class="form-check-label" for="isMobile">
                                <i class="bx bx-walk me-1"></i>
                                Saya adalah pedagang keliling (lokasi berubah-ubah)
                            </label>
                        </div>
                    </div>

                    @if($is_mobile)
                    <div class="col-12">
                        <label class="form-label fw-semibold">Area Jualan (Opsional)</label>
                        <textarea class="form-control" rows="2" wire:model="operational_area" 
                                  placeholder="Contoh: Area Malioboro, Sekitar UGM, Pasar Senen, dll..."></textarea>
                        <div class="form-text">
                            Tentukan area utama dimana Anda biasanya berjualan
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-3">
                        <i class="bx bx-save me-2"></i>
                        Simpan Perubahan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
#vendor-location-map {
    background: #f8f9fa;
}

.leaflet-container {
    border-radius: 8px;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Custom marker styles */
.vendor-marker {
    background: transparent !important;
    border: none !important;
}

.coordinate-display {
    font-family: 'Courier New', monospace;
    font-size: 14px;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Pendekatan SEDERHANA
let vendorLocationMap = null;
let vendorLocationMarker = null;

// Fungsi untuk update coordinate display
function updateCoordinateDisplay(lat, lng) {
    const latDisplay = document.getElementById('latitudeDisplay');
    const lngDisplay = document.getElementById('longitudeDisplay');
    
    if (latDisplay && lngDisplay) {
        latDisplay.textContent = lat ? lat.toFixed(6) : 'Belum dipilih';
        lngDisplay.textContent = lng ? lng.toFixed(6) : 'Belum dipilih';
    }
}

// Fungsi inisialisasi map
function initVendorLocationMap() {
    console.log('🗺️ Initializing vendor location map...');
    
    const mapContainer = document.getElementById('vendor-location-map');
    if (!mapContainer) {
        console.error('❌ Vendor location map container not found');
        return;
    }

    // Hancurkan map lama jika ada
    if (vendorLocationMap) {
        vendorLocationMap.remove();
        vendorLocationMap = null;
        vendorLocationMarker = null;
    }

    // Get initial coordinates dari Livewire
    let initialLat = @json($latitude) || -6.2088;
    let initialLng = @json($longitude) || 106.8456;

    console.log('📍 Using coordinates:', initialLat, initialLng);

    try {
        // Initialize map
        vendorLocationMap = L.map('vendor-location-map').setView([initialLat, initialLng], 13);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(vendorLocationMap);

        // Add marker jika ada koordinat
        if (initialLat && initialLng) {
            vendorLocationMarker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(vendorLocationMap);
            
            // Update coordinate display
            updateCoordinateDisplay(initialLat, initialLng);
        }

        // Event click pada map
        vendorLocationMap.on('click', function(e) {
            console.log('🗺️ Map clicked:', e.latlng.lat, e.latlng.lng);
            updateVendorLocationMarker(e.latlng.lat, e.latlng.lng);
            reverseGeocodeVendor(e.latlng.lat, e.latlng.lng);
        });

        // Event drag marker
        if (vendorLocationMarker) {
            vendorLocationMarker.on('dragend', function(e) {
                const position = vendorLocationMarker.getLatLng();
                console.log('📍 Marker dragged to:', position.lat, position.lng);
                updateVendorLocationMarker(position.lat, position.lng);
                reverseGeocodeVendor(position.lat, position.lng);
            });
        }

        // Initialize search
        initVendorLocationSearch();

        console.log('✅ Vendor location map initialized successfully');

        // Fix map size setelah render
        setTimeout(() => {
            if (vendorLocationMap) {
                vendorLocationMap.invalidateSize();
                console.log('✅ Vendor location map size invalidated');
            }
        }, 300);

    } catch (error) {
        console.error('❌ Vendor location map initialization failed:', error);
    }
}

// Global function untuk update marker dari geolocation
window.updateVendorMarker = function(lat, lng) {
    updateVendorLocationMarker(lat, lng);
};

// Update marker position
function updateVendorLocationMarker(lat, lng) {
    if (!vendorLocationMarker) {
        vendorLocationMarker = L.marker([lat, lng], { draggable: true }).addTo(vendorLocationMap);
    } else {
        vendorLocationMarker.setLatLng([lat, lng]);
    }
    
    // Update Livewire
    @this.set('latitude', lat);
    @this.set('longitude', lng);
    
    // Update coordinate display
    updateCoordinateDisplay(lat, lng);
    
    // Center map
    if (vendorLocationMap) {
        vendorLocationMap.setView([lat, lng], 15);
    }
}

// Reverse geocoding
function reverseGeocodeVendor(lat, lng) {
    console.log('🔍 Reverse geocoding:', lat, lng);
    
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data?.display_name) {
                console.log('📫 Address found:', data.display_name);
                @this.set('address', data.display_name);
            }
        })
        .catch(error => {
            console.error('❌ Reverse geocoding error:', error);
            // Fallback
            const fallbackAddress = `Lokasi: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            @this.set('address', fallbackAddress);
        });
}

// Search functionality
function initVendorLocationSearch() {
    const searchInput = document.getElementById('searchLocation');
    const searchButton = document.getElementById('searchButton');
    
    if (!searchInput || !searchButton) {
        console.warn('⚠️ Search elements not found');
        return;
    }

    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();
        if (!query) {
            alert('Masukkan kata kunci pencarian');
            return;
        }
        
        console.log('🔍 Searching for:', query);
        
        // Show loading state
        const originalText = searchButton.innerHTML;
        searchButton.innerHTML = '<i class="bx bx-loader spinner"></i>';
        searchButton.disabled = true;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
            .then(response => response.json())
            .then(data => {
                searchButton.innerHTML = originalText;
                searchButton.disabled = false;

                if (data.length > 0) {
                    const result = data[0];
                    console.log('✅ Search result:', result);
                    updateVendorLocationMarker(parseFloat(result.lat), parseFloat(result.lon));
                    if (result.display_name) {
                        @this.set('address', result.display_name);
                    }
                    searchInput.value = ''; // Clear search input
                } else {
                    alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
                }
            })
            .catch(error => {
                console.error('❌ Search error:', error);
                searchButton.innerHTML = originalText;
                searchButton.disabled = false;
                alert('Terjadi kesalahan saat mencari lokasi');
            });
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchButton.click();
        }
    });
}

// ============ LIVEWIRE INTEGRATION ============

// Inisialisasi saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 DOM loaded, setting up vendor location map...');
    
    // Setup event listener untuk tombol getLocationBtn
    const getLocationBtn = document.getElementById('getLocationBtn');
    if (getLocationBtn) {
        getLocationBtn.addEventListener('click', function() {
            console.log('📍 Get Location button clicked');
        });
    }
    
    // Inisialisasi map dengan delay
    setTimeout(() => {
        initVendorLocationMap();
    }, 800);
});

// Handle Livewire updates
Livewire.hook('morph.updated', function({ component }) {
    if (component.name === '{{ $this->getName() }}') {
        console.log('🔄 Vendor location component updated');
        
        setTimeout(() => {
            if (!vendorLocationMap) {
                console.log('🔄 Initializing new vendor location map...');
                initVendorLocationMap();
            } else {
                console.log('🔄 Invalidating existing vendor location map size...');
                setTimeout(() => {
                    if (vendorLocationMap) {
                        vendorLocationMap.invalidateSize();
                        console.log('✅ Vendor location map size invalidated');
                    }
                }, 100);
            }
        }, 500);
    }
});

// Cleanup saat komponen di-unload
Livewire.hook('morph.removing', function({ component }) {
    if (component.name === '{{ $this->getName() }}') {
        console.log('🗑️ Cleaning up vendor location map...');
        if (vendorLocationMap) {
            try {
                vendorLocationMap.remove();
                vendorLocationMap = null;
                vendorLocationMarker = null;
                console.log('✅ Vendor location map cleaned up');
            } catch (error) {
                console.warn('⚠️ Error cleaning up vendor location map:', error);
            }
        }
    }
});

// Clear success message
Livewire.on('clear-success-message', () => {
    setTimeout(() => {
        @this.set('successMessage', null);
    }, 3000);
});
</script>
@endpush