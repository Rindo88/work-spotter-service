<div>
    <!-- 1. HEADER & SEARCH -->
    <div class="card border-0 shadow-sm mb-3 bg-primary text-white">
        <div class="card-body">
            <h5 class="mb-3">
                <i class="bx bx-map me-2"></i>
                Cari Pedagang Terdekat
            </h5>

            <!-- Search Bar -->
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-0">
                            <i class="bx bx-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0" placeholder="Cari nama pedagang..."
                            wire:model.live="searchQuery">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select form-select-sm border-0" wire:model.live="filterCategory">
                        <option value="all">Semua Kategori</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select form-select-sm border-0" wire:model.live="searchRadius">
                        <option value="1">Radius 1 km</option>
                        <option value="3">Radius 3 km</option>
                        <option value="5" selected>Radius 5 km</option>
                        <option value="10">Radius 10 km</option>
                        <option value="10000000000000000000">Semua</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. PETA -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0">
            <div class="d-flex flex-column flex-md-row flex-lg-row justify-content-between align-items-start align-items-lg-center">
                <h6 class="mb-0">
                    <i class="bx bx-map me-2 text-primary"></i>
                    Peta Lokasi Pedagang
                </h6>
                <small class="text-muted">Klik peta untuk pilih lokasi manual</small>
            </div>
        </div>
        <div class="card-body p-0 position-relative">
            <div id="userMap" style="height: 400px; width: 100%;" wire:ignore></div>

            <!-- Search Box -->
            <div class="position-absolute top-0 start-0 m-3">
                <div class="input-group input-group-sm">
                    <input type="text" id="userSearchBox" class="form-control" placeholder="Cari lokasi..."
                        style="width: 200px;">
                    <button class="btn btn-primary" type="button" onclick="searchUserLocation()">
                        <i class="bx bx-search"></i>
                    </button>
                </div>
            </div>

            <!-- Map Legend -->
            <div class="position-absolute bottom-0 end-0 m-3">
                <div class="bg-white rounded shadow-sm p-2 small">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bx bxs-map-pin text-primary me-2"></i>
                        <span>Lokasi Anda</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bx bxs-store text-success me-2"></i>
                        <span>Pedagang Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. INPUT MANUAL (Toggle) -->
    @if ($showManualInput)
        <div class="card border-0 shadow-sm mb-3 border-primary">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 text-primary">
                    <i class="bx bxs-map-pin me-2"></i>Input Lokasi Manual
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
                        <i class="bx bx-check-circle me-1"></i>Gunakan Lokasi Ini
                    </button>
                    <button class="btn btn-outline-primary" wire:click="toggleManualInput">
                        <i class="bx bx-x me-1"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 4. TOMBOL LOKASI -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            @if (!$userLocation)
                <!-- Belum ada lokasi -->
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg py-3" onclick="getUserLocation()" id="getUserLocationBtn"
                        {{ $isLoading ? 'disabled' : '' }}>
                        <span>
                            @if ($isLoading)
                                <i class="bx bx-loader-alt me-1"></i>Mendeteksi Lokasi...
                            @else
                                <i class="bx bxs-map-pin me-1"></i>Ambil Lokasi Saya
                            @endif
                        </span>
                    </button>
                    <button class="btn btn-outline-primary" wire:click="toggleManualInput">
                        <i class="bx bx-map me-1"></i>Input Lokasi Manual
                    </button>
                </div>
            @else
                <!-- Sudah ada lokasi -->
                <div class="p-3 bg-primary-subtle bg-opacity-10 rounded">
                    <i class="bx bx-check-circle text-primary me-2 align-middle"></i><span class="text-primary fw-bold">Lokasi Terdeteksi!</span>
                    @if($locationName)
                        <p class="mb-1 small fw-semibold text-dark">{{ $locationName }}</p>
                    @endif
                    <p class="mb-2 small text-muted">
                        Latitude: {{ number_format($userLocation['latitude'], 6) }}<br>
                        Longitude: {{ number_format($userLocation['longitude'], 6) }}
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-outline-primary btn-sm" onclick="getUserLocation()">
                            <i class="bx bx-refresh me-1"></i>Ambil Ulang
                        </button>
                        <button class="btn btn-primary btn-sm" wire:click="toggleManualInput">
                            <i class="bx bx-pencil me-1"></i>Edit Manual
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- 5. DAFTAR PEDAGANG AKTIF -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bx bxs-store me-2 text-success"></i>
                    Pedagang Aktif
                    <span class="badge bg-success ms-2">{{ count($activeVendors) }}</span>
                </h6>
                <small class="text-muted">Dalam radius {{ $searchRadius }}km</small>
            </div>
        </div>

        <div class="card-body">
            @if (count($activeVendors) > 0)
                <div class="row g-3">
                    @foreach ($activeVendors as $vendor)
                        @if ($vendor->checkin_time)
                            <div class="col-12">
                                <div class="vendor-card p-2 p-sm-3 border rounded hover-shadow {{ $selectedVendor && $selectedVendor->id == $vendor->id ? 'border-primary bg-primary bg-opacity-5' : '' }}"
                                    wire:click="selectVendor({{ $vendor->id }})"
                                    style="cursor: pointer; transition: all 0.3s ease;">
                                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2 gap-sm-3">
                                        <!-- Vendor Image -->
                                        <div class="d-flex align-items-center gap-2 w-100 w-sm-auto">
                                            <img src="{{ $vendor->profile_picture && !str_contains($vendor->profile_picture, 'http') ? asset('storage/' . $vendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->business_name) . '&background=92B6B1&color=white&size=64' }}"
                                                class="rounded-2 flex-shrink-0" style="width: 50px; height: 50px; object-fit: cover;"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($vendor->business_name) }}&background=92B6B1&color=white&size=64'">

                                            <!-- Mobile: Business name and distance inline with image -->
                                            <div class="d-flex justify-content-between align-items-center w-100 d-sm-none">
                                                <h6 class="fw-bold mb-0 text-truncate me-2">
                                                    <a href="{{ route('vendor.show', ['vendor' => $vendor->id]) }}"
                                                       class="text-decoration-none text-dark"
                                                       onclick="event.stopPropagation()">
                                                        {{ $vendor->business_name }}
                                                    </a>
                                                </h6>
                                                <span class="badge bg-light text-dark flex-shrink-0">
                                                    <i class="bx bxs-map-pin me-1"></i>{{ number_format($vendor->distance, 1) }}km
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Vendor Info -->
                                        <div class="flex-grow-1 w-100 w-sm-auto">
                                            <!-- Desktop: Business name and distance -->
                                            <div class="d-none d-sm-flex justify-content-between align-items-start mb-1">
                                                <h6 class="fw-bold mb-0">
                                                    <a href="{{ route('vendor.show', ['vendor' => $vendor->id]) }}"
                                                       class="text-decoration-none text-dark"
                                                       onclick="event.stopPropagation()">
                                                        {{ $vendor->business_name }}
                                                    </a>
                                                </h6>
                                                <span class="badge bg-light text-dark">
                                                    <i class="bx bxs-map-pin me-1"></i>{{ number_format($vendor->distance, 1) }}km
                                                </span>
                                            </div>

                                            <p class="text-muted small mb-2 d-none d-sm-block">{{ $vendor->description }}</p>
                                            <p class="text-muted small mb-2 d-sm-none" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $vendor->description }}</p>

                                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                                <span class="badge bg-light text-dark border border-secondary">
                                                    <i class="bx bx-time me-1"></i>
                                                    Aktif
                                                    @if ($vendor->checkin_time)
                                                        {{ $vendor->checkin_time->diffForHumans() }}
                                                    @else
                                                        Hari ini
                                                    @endif
                                                </span>
                                                <div class="d-flex gap-1 w-100 w-sm-auto">
                                                    <button class="btn btn-outline-primary btn-sm flex-fill flex-sm-grow-0"
                                                        wire:click="startChatWithVendor({{ $vendor->id }})"
                                                        onclick="event.stopPropagation()">
                                                        <i class="bx bx-chat me-1"></i><span>Chat</span>
                                                    </button>
                                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $vendor->latitude }},{{ $vendor->longitude }}"
                                                        target="_blank" class="btn btn-primary btn-sm flex-fill flex-sm-grow-0"
                                                        onclick="event.stopPropagation()">
                                                        <i class="bx bxs-map-pin me-1"></i><span>Maps</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <!-- Tidak ada pedagang -->
                <div class="text-center py-5">
                    <i class="bx bxs-store display-1 text-muted mb-3"></i>
                    <h6 class="text-muted">Tidak ada pedagang aktif</h6>
                    <p class="text-muted small mb-0">
                        @if ($userLocation)
                            Tidak ada pedagang dalam radius {{ $searchRadius }}km dari lokasi Anda.
                        @else
                            Pilih lokasi Anda terlebih dahulu untuk melihat pedagang terdekat.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        .vendor-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .leaflet-popup-content {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 12px;
        }

        .vendor-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .leaflet-popup-content {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Custom marker styles */
        .user-location-marker {
            background: transparent !important;
            border: none !important;
        }

        .vendor-location-marker {
            background: transparent !important;
            border: none !important;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        // Simple JavaScript tanpa Alpine.js
        let userMap;
        let userLocationMarker = null;
        let vendorMarkers = [];
        let userClickMarker = null;

        function initUserMap() {
            console.log('🗺️ Initializing user map...');

            const mapElement = document.getElementById('userMap');
            if (!mapElement) {
                console.error('❌ Map element not found');
                return;
            }

            // Initialize map
            userMap = L.map('userMap').setView([-6.2, 106.8], 13);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(userMap);

            // Add search control
            L.Control.geocoder({
                defaultMarkGeocode: false,
                placeholder: 'Cari lokasi...'
            }).on('markgeocode', function(e) {
                const location = {
                    latitude: e.geocode.center.lat,
                    longitude: e.geocode.center.lng
                };
                updateUserLocation(location, 'Lokasi dari pencarian: ' + e.geocode.name);
            }).addTo(userMap);

            // Click event untuk pilih lokasi manual
            userMap.on('click', function(e) {
                const location = {
                    latitude: e.latlng.lat,
                    longitude: e.latlng.lng
                };
                updateUserLocation(location, 'Lokasi yang dipilih di peta');
            });

            console.log('✅ User map initialized successfully');

            @if ($userLocation)
                // setTimeout(() => {
                updateUserLocation(@json($userLocation), 'Lokasi Anda')
                // }, 5000);
            @endif
        }

        // Function untuk update lokasi user (baik dari GPS atau manual)
        function updateUserLocation(location, locationName) {
            if (!userMap) return;

            // Hapus semua marker lokasi user yang ada
            if (userLocationMarker) {
                userMap.removeLayer(userLocationMarker);
                userLocationMarker = null;
            }
            if (userClickMarker) {
                userMap.removeLayer(userClickMarker);
                userClickMarker = null;
            }

            // Set view ke lokasi baru
            userMap.setView([location.latitude, location.longitude], 16);

            // Buat custom icon untuk lokasi user
            const userIcon = L.divIcon({
                className: 'user-location-marker',
                html: `
                <div style="
                    background-color: #007bff;
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    border: 4px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    position: relative;
                ">
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 8px;
                        height: 8px;
                        background: white;
                        border-radius: 50%;
                    "></div>
                </div>
            `,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            // Tambahkan marker lokasi user
            userLocationMarker = L.marker([location.latitude, location.longitude], {
                    icon: userIcon
                })
                .addTo(userMap)
                .bindPopup(`
            <div class="text-center">
                <b>📍 ${locationName}</b><br>
                <small>Lat: ${location.latitude.toFixed(6)}</small><br>
                <small>Lng: ${location.longitude.toFixed(6)}</small>
            </div>
        `)
                .openPopup();

            // Tambahkan circle radius
            const radius = document.querySelector('[wire\\:model="searchRadius"]')?.value || 5;
            L.circle([location.latitude, location.longitude], {
                color: '#007bff',
                fillColor: '#007bff',
                fillOpacity: 0.1,
                radius: radius * 1000
            }).addTo(userMap);

            // Update vendor markers
            updateVendorMarkers();

            // Send to Livewire
            if (window.Livewire) {
                window.Livewire.dispatch('set-user-location', {
                    latitude: location.latitude,
                    longitude: location.longitude
                });
            }
        }

        // Function untuk update vendor markers
        function updateVendorMarkers() {
            if (!userMap) return;

            // Clear existing vendor markers
            vendorMarkers.forEach(marker => {
                if (userMap.hasLayer(marker)) {
                    userMap.removeLayer(marker);
                }
            });
            vendorMarkers = [];

            // Buat custom icon untuk vendor
            const vendorIcon = L.divIcon({
                className: 'vendor-location-marker',
                html: `
                <div style="
                    background-color: #28a745;
                    width: 20px;
                    height: 20px;
                    border-radius: 50% 50% 50% 0;
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    transform: rotate(-45deg);
                    position: relative;
                ">
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%) rotate(45deg);
                        color: white;
                        font-size: 10px;
                        font-weight: bold;
                    ">🏪</div>
                </div>
            `,
                iconSize: [20, 20],
                iconAnchor: [10, 20]
            });

            // Tambahkan vendor markers dari data Livewire
            // Note: Vendor markers akan di-update melalui Livewire events
            console.log('📍 Vendor markers cleared, waiting for Livewire data');
        }

        // Function untuk menambahkan vendor marker secara individual
        function addVendorMarker(vendor) {
            if (!userMap || !vendor.latitude || !vendor.longitude) return;

            const vendorIcon = L.divIcon({
                className: 'vendor-location-marker',
                html: `
                <div style="
                    background-color: #28a745;
                    width: 20px;
                    height: 20px;
                    border-radius: 50% 50% 50% 0;
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    transform: rotate(-45deg);
                    position: relative;
                ">
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%) rotate(45deg);
                        color: white;
                        font-size: 10px;
                        font-weight: bold;
                    ">🏪</div>
                </div>
            `,
                iconSize: [20, 20],
                iconAnchor: [10, 20]
            });

            const vendorMarker = L.marker([vendor.latitude, vendor.longitude], {
                    icon: vendorIcon
                })
                .addTo(userMap)
                .bindPopup(`
            <div style="min-width: 200px;">
                <h6 class="fw-bold mb-1">${vendor.business_name}</h6>
                <p class="small mb-2 text-muted">${vendor.description}</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-success">
                        <i class="bx bx-time me-1"></i>Aktif
                    </span>
                    <span class="badge bg-light text-dark">
                        ${vendor.distance ? vendor.distance.toFixed(1) : '0'} km
                    </span>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-primary btn-sm flex-fill"
                            onclick="window.Livewire.dispatch('select-vendor', {vendorId: ${vendor.id}})">
                        <i class="bx bx-show me-1"></i>Lihat
                    </button>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${vendor.latitude},${vendor.longitude}"
                       target="_blank"
                       class="btn btn-success btn-sm">
                        <i class="bx bxs-map-pin me-1"></i>Google Maps
                    </a>
                </div>
            </div>
        `);

            vendorMarkers.push(vendorMarker);
        }

        function getUserLocation() {
            console.log('📍 Getting user location via GPS...');

            if (!navigator.geolocation) {
                alert('Browser tidak mendukung geolocation');
                return;
            }

            const btn = document.getElementById('getUserLocationBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader-alt me-1"></i>Mendeteksi Lokasi...';
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const location = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };

                    updateUserLocation(location, 'Lokasi GPS Anda');

                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bx bxs-map-pin me-2"></i>Ambil Lokasi Saya';
                    }
                },
                (error) => {
                    console.error('❌ GPS location error:', error);
                    let message = 'Gagal mendapatkan lokasi. ';
                    switch (error.code) {
                        case 1:
                            message += 'Izinkan akses lokasi di browser.';
                            break;
                        case 2:
                            message += 'Lokasi tidak tersedia.';
                            break;
                        case 3:
                            message += 'Waktu permintaan habis.';
                            break;
                        default:
                            message += 'Error tidak diketahui.';
                    }
                    alert(message);

                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bx bxs-map-pin me-2"></i>📍 Ambil Lokasi Saya';
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
                }
            );
        }

        function searchUserLocation() {
            const searchInput = document.getElementById('userSearchBox');
            const query = searchInput.value.trim();

            if (!query) {
                alert('Masukkan nama lokasi yang ingin dicari');
                return;
            }

            const geocoder = L.Control.Geocoder.nominatim();
            geocoder.geocode(query, function(results) {
                if (results && results.length > 0) {
                    const result = results[0];
                    const location = {
                        latitude: result.center.lat,
                        longitude: result.center.lng
                    };

                    updateUserLocation(location, 'Lokasi dari pencarian: ' + result.name);
                    searchInput.value = '';
                } else {
                    alert('Lokasi tidak ditemukan. Coba dengan nama yang lebih spesifik.');
                }
            });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initUserMap, 1000);

            // Enter key untuk search
            const searchBox = document.getElementById('userSearchBox');
            if (searchBox) {
                searchBox.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchUserLocation();
                    }
                });
            }
        });

        // Livewire event listeners
        document.addEventListener('livewire:initialized', () => {
            console.log('✅ User map Livewire initialized');

            // Listen untuk update vendor markers dari Livewire
            Livewire.on('update-vendor-markers', (event) => {
                console.log('📍 Updating vendor markers from Livewire', event.vendors);

                // Clear existing vendor markers
                vendorMarkers.forEach(marker => {
                    if (userMap && userMap.hasLayer(marker)) {
                        userMap.removeLayer(marker);
                    }
                });
                vendorMarkers = [];

                // Add new vendor markers
                if (event.vendors && Array.isArray(event.vendors)) {
                    event.vendors.forEach(vendor => {
                        addVendorMarker(vendor);
                    });
                }
            });

            // Listen untuk focus on vendor
            Livewire.on('focus-on-vendor', (event) => {
                if (userMap) {
                    userMap.setView([event.latitude, event.longitude], 16);

                    // Open popup untuk vendor yang dipilih
                    vendorMarkers.forEach(marker => {
                        const latLng = marker.getLatLng();
                        if (Math.abs(latLng.lat - event.latitude) < 0.0001 &&
                            Math.abs(latLng.lng - event.longitude) < 0.0001) {
                            marker.openPopup();
                        }
                    });
                }
            });
        });
    </script>
@endpush
