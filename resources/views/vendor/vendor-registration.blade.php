<div>
    <!-- Informational Sections -->
    @if ($currentStep == 1)
        <div class="mt-4">
            <div class="alert alert-info">
                <h6><i class="bx bx-info-circle me-2"></i>Informasi Penting</h6>
                <p class="mb-0">Pastikan data yang Anda masukkan akurat dan valid. Data ini akan ditampilkan kepada
                    calon pelanggan.</p>
            </div>
        </div>
    @endif

    @if ($currentStep == 2)
        <div class="mt-4">
            <div class="alert alert-warning">
                <h6><i class="bx bx-map me-2"></i>Pentingnya Lokasi</h6>
                <p class="mb-0">Lokasi yang akurat membantu pelanggan menemukan usaha Anda dengan mudah. Pastikan
                    koordinat sesuai dengan lokasi sebenarnya.</p>
            </div>
        </div>
    @endif

    @if ($currentStep == 3)
        <div class="mt-4">
            <div class="alert alert-success">
                <h6><i class="bx bx-camera me-2"></i>Tips Foto Usaha</h6>
                <ul class="mb-0 ps-3">
                    <li>Gunakan foto yang jelas dan terang</li>
                    <li>Tampilkan produk atau layanan terbaik</li>
                    <li>Foto dari berbagai angle</li>
                    <li>Maksimal 4 foto untuk tampilan optimal</li>
                </ul>
            </div>
        </div>
    @endif

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress">
            <div class="progress-bar" style="width: {{ $progress }}%;"></div>
        </div>
        <div class="text-center mt-2">
            <small>Langkah {{ $currentStep }} dari 3</small>
        </div>
    </div>

    <!-- Toggle Vendor Type -->
    <div class="vendor-type-toggle">
        <div class="toggle-slider {{ $vendorType }}"></div>
        <div class="toggle-option {{ $vendorType === 'formal' ? 'active' : '' }}" wire:click="setVendorType('formal')">
            Pedagang Formal
        </div>
        <div class="toggle-option {{ $vendorType === 'informal' ? 'active' : '' }}"
            wire:click="setVendorType('informal')">
            Pedagang Informal
        </div>
    </div>

    <!-- Step 1: Basic Information -->
    @if ($currentStep === 1)
        <div class="form-section">
            <h3 class="section-title">
                <span class="section-number">1</span>
                Informasi Dasar
            </h3>

            <div class="mb-3">
                <label class="form-label required-field">Nama Usaha</label>
                <input type="text" class="form-control" wire:model="business_name" required>
                @error('business_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label required-field">Jenis Usaha</label>
                <select class="form-select" wire:model="category_id" required>
                    <option value="">Pilih jenis usaha</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required-field">Deskripsi Usaha</label>
                <textarea class="form-control" wire:model="description" rows="3" required></textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @endif

    <!-- Step 2: Contact & Location -->
    @if ($currentStep === 2)
        <div class="form-section">
            <h3 class="section-title">
                <span class="section-number">2</span>
                {{ $vendorType === 'formal' ? 'Kontak & Lokasi' : 'Kontak & Jadwal' }}
            </h3>

            <div class="mb-3">
                <label class="form-label required-field">Nomor WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input type="tel" class="form-control" wire:model="phone" required>
                </div>
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required-field">Email</label>
                <input type="email" class="form-control" wire:model="email" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required-field">Lokasi Usaha</label>

                <!-- Tombol untuk mendapatkan lokasi otomatis -->
                <div class="coordinate-buttons mb-3">
                    <button type="button" class="btn btn-outline-primary" id="getLocationBtn">
                        <i class="bx bx-map me-2"></i>Dapatkan Lokasi Otomatis
                    </button>
                    <div class="form-text">Klik tombol di atas untuk mendapatkan lokasi Anda secara otomatis</div>
                </div>

                <!-- Search Box untuk Map -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Cari lokasi..." id="searchLocation">
                    <button class="btn btn-outline-primary" type="button" id="searchButton">
                        <i class="bx bx-search"></i> Cari
                    </button>
                </div>

                <!-- Map Container - Pendekatan Sederhana -->
                <div class="mb-3">
                    <div id="vendor-map-container"
                        style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ddd;" wire:ignore>
                    </div>
                    <div class="form-text">Klik pada peta atau geser pin untuk menentukan lokasi yang tepat</div>
                </div>

                <!-- Input Koordinat (Hidden) -->
                <input type="hidden" wire:model="latitude" id="latitude-input">
                <input type="hidden" wire:model="longitude" id="longitude-input">

                <!-- Input Alamat -->
                <div class="mb-3">
                    <label class="form-label required-field">Alamat Detail</label>
                    <textarea class="form-control" wire:model="address" rows="2" required id="address-input"></textarea>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @error('latitude')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                @error('longitude')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @endif

    <!-- Step 3: Documents & Images -->
    @if ($currentStep === 3)
        <div class="form-section">
            <h3 class="section-title">
                <span class="section-number">3</span>
                {{ $vendorType === 'formal' ? 'Dokumen & Foto' : 'Foto Usaha' }}
            </h3>

            <div class="mb-4">
                <label class="form-label required-field">Foto Usaha (Maksimal 4)</label>
                <div class="image-upload-grid">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="image-upload-item">
                            <label class="image-preview" for="business_image_{{ $i }}">
                                @if (isset($business_images[$i]))
                                    <img src="{{ $business_images[$i]->temporaryUrl() }}" alt="Preview">
                                    <div class="image-overlay">
                                        <i class="bx bxs-check-circle text-success"></i>
                                    </div>
                                @else
                                    <div class="image-preview-placeholder">
                                        <i class="bx bx-plus-circle"></i>
                                    </div>
                                @endif
                            </label>
                            <input type="file" id="business_image_{{ $i }}"
                                wire:model="business_images.{{ $i }}" accept="image/*" class="d-none">
                            <small>Foto {{ $i + 1 }}</small>
                        </div>
                    @endfor
                </div>
                @error('business_images')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                @error('business_images.*')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                @if (count($business_images) > 0)
                    <div class="mt-2 p-2 border rounded bg-light">
                        <i class="bx bx-info-circle me-2"></i>
                        <span>{{ count($business_images) }} foto terpilih</span>
                    </div>
                @endif
            </div>

            <!-- Schedule Form Component -->
            <livewire:components.schedule-form :hasSchedule="$has_schedule" :existingSchedules="$schedules"
                key="schedule-form-{{ now()->timestamp }}" />

            <!-- Service Form Component -->
            <livewire:components.service-form :hasServices="$has_services" :existingServices="$vendor_services"
                key="service-form-{{ now()->timestamp }}" />
        </div>
    @endif

    <!-- Navigation Buttons -->
    <div class="form-navigation">
        @if ($currentStep > 1)
            <button type="button" class="btn btn-outline-secondary" wire:click="previousStep">
                <i class="bx bx-left-arrow-alt me-2"></i>Kembali
            </button>
        @else
            <div></div>
        @endif

        @if ($currentStep < 3)
            <button type="button" class="btn btn-primary" wire:click="nextStep">
                Lanjut <i class="bx bx-right-arrow-alt ms-2"></i>
            </button>
        @else
            <button type="button" class="btn btn-success" wire:click="submit">
                <i class="bx bx-check-circle me-2"></i>Daftar Sekarang
            </button>
        @endif
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .image-upload-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }

        .image-upload-item {
            text-align: center;
        }

        .image-preview {
            display: block;
            width: 100%;
            height: 120px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #6c757d;
            font-size: 24px;
        }

        .image-overlay {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            padding: 2px;
        }

        .vendor-type-toggle {
            display: flex;
            position: relative;
            background: #f8f9fa;
            border-radius: 25px;
            padding: 5px;
            margin-bottom: 20px;
        }

        .toggle-option {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            z-index: 2;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .toggle-option.active {
            color: white;
            font-weight: bold;
        }

        .toggle-slider {
            position: absolute;
            top: 5px;
            bottom: 5px;
            width: 50%;
            background: var(--primary-color);
            border-radius: 20px;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .toggle-slider.formal {
            left: 5px;
        }

        .toggle-slider.informal {
            left: calc(50% - 5px);
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Map container styling */
        #vendor-map-container {
            background: #f8f9fa;
        }

        .leaflet-container {
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Pendekatan SEDERHANA seperti di UserMap
        let vendorMap = null;
        let vendorMarker = null;

        // Fungsi inisialisasi map yang SEDERHANA
        function initVendorMap() {
            console.log('🗺️ Initializing vendor map...');

            const mapContainer = document.getElementById('vendor-map-container');
            if (!mapContainer) {
                console.error('❌ Vendor map container not found');
                return;
            }

            // Hancurkan map lama jika ada
            if (vendorMap) {
                vendorMap.remove();
                vendorMap = null;
                vendorMarker = null;
            }

            // Get initial coordinates dari Livewire
            let initialLat = @json($latitude) || -6.2088;
            let initialLng = @json($longitude) || 106.8456;

            console.log('📍 Using coordinates:', initialLat, initialLng);

            // Initialize map - SEDERHANA seperti di UserMap
            vendorMap = L.map('vendor-map-container').setView([initialLat, initialLng], 13);

            // Add tile layer - SEDERHANA
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(vendorMap);

            // Add marker - SEDERHANA
            vendorMarker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(vendorMap);

            // Event click pada map - SEDERHANA
            vendorMap.on('click', function(e) {
                console.log('🗺️ Map clicked:', e.latlng.lat, e.latlng.lng);
                updateVendorMarkerPosition(e.latlng.lat, e.latlng.lng);
                reverseGeocodeVendor(e.latlng.lat, e.latlng.lng);
            });

            // Event drag marker - SEDERHANA
            vendorMarker.on('dragend', function(e) {
                const position = vendorMarker.getLatLng();
                console.log('📍 Marker dragged to:', position.lat, position.lng);
                updateVendorMarkerPosition(position.lat, position.lng);
                reverseGeocodeVendor(position.lat, position.lng);
            });

            // Initialize search - SEDERHANA
            initVendorSearch();

            console.log('✅ Vendor map initialized successfully');

            // Fix map size setelah render
            setTimeout(() => {
                if (vendorMap) {
                    vendorMap.invalidateSize();
                    console.log('✅ Map size invalidated');
                }
            }, 300);
        }

        // Update marker position - SEDERHANA
        function updateVendorMarkerPosition(lat, lng) {
            if (!vendorMarker) {
                vendorMarker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(vendorMap);
            } else {
                vendorMarker.setLatLng([lat, lng]);
            }

            // Update Livewire - SEDERHANA
            @this.set('latitude', lat);
            @this.set('longitude', lng);

            // Center map
            if (vendorMap) {
                vendorMap.setView([lat, lng], 15);
            }
        }

        // Reverse geocoding - SEDERHANA
        function reverseGeocodeVendor(lat, lng) {
            console.log('🔍 Reverse geocoding:', lat, lng);

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data?.display_name) {
                        console.log('📫 Address found:', data.display_name);
                        @this.set('address', data.display_name);
                        const addrInput = document.getElementById('address-input');
                        if (addrInput) addrInput.value = data.display_name;
                    }
                })
                .catch(error => {
                    console.error('❌ Reverse geocoding error:', error);
                    // Fallback
                    const fallbackAddress = `Lat: ${lat}, Lng: ${lng}`;
                    @this.set('address', fallbackAddress);
                    const addrInput = document.getElementById('address-input');
                    if (addrInput) addrInput.value = fallbackAddress;
                });
        }

        // Search functionality - SEDERHANA
        function initVendorSearch() {
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

                fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`
                        )
                    .then(response => response.json())
                    .then(data => {
                        searchButton.innerHTML = originalText;
                        searchButton.disabled = false;

                        if (data.length > 0) {
                            const result = data[0];
                            console.log('✅ Search result:', result);
                            updateVendorMarkerPosition(parseFloat(result.lat), parseFloat(result.lon));
                            if (result.display_name) {
                                @this.set('address', result.display_name);
                            }
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

        // Geolocation - SEDERHANA
        function getVendorLocation() {
            console.log('📍 getVendorLocation function called');

            if (!navigator.geolocation) {
                alert('Browser tidak mendukung geolocation');
                return;
            }

            const btn = document.getElementById('getLocationBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader spinner me-2"></i>Mendeteksi...';
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    console.log('📍 GPS location success:', position.coords);

                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    updateVendorMarkerPosition(lat, lng);
                    reverseGeocodeVendor(lat, lng);

                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bx bx-map me-2"></i>Dapatkan Lokasi Otomatis';
                    }

                    // Show success alert
                    alert('Lokasi berhasil didapatkan! Silakan periksa pin pada peta.');
                },
                (error) => {
                    console.error('❌ Geolocation error:', error);
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

                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bx bx-map me-2"></i>Dapatkan Lokasi Otomatis';
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
                }
            );
        }

        // ============ LIVEWIRE INTEGRATION SEDERHANA ============

        // Inisialisasi saat DOM ready - seperti di UserMap
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 DOM loaded, setting up vendor map...');

            // Setup event listeners - PERBAIKAN: Pastikan button ada
            const locationBtn = document.getElementById('getLocationBtn');
            if (locationBtn) {
                console.log('✅ Found getLocationBtn, adding event listener');
                locationBtn.addEventListener('click', getVendorLocation);
            } else {
                console.error('❌ getLocationBtn not found!');
            }

            // Inisialisasi map dengan delay seperti di UserMap
            setTimeout(() => {
                if (@this.get('currentStep') === 2) {
                    console.log('🔄 Initializing vendor map on step 2...');
                    initVendorMap();
                }
            }, 800);
        });

        // Handle step changes - TAMBAHKAN setup ulang event listeners
        Livewire.hook('morph.updated', function({
            component
        }) {
            if (component.name === '{{ $this->getName() }}' && @this.get('currentStep') === 2) {
                console.log('🔄 Step 2 activated, initializing vendor map...');

                // Setup ulang event listeners karena DOM mungkin di-render ulang
                setTimeout(() => {
                    const locationBtn = document.getElementById('getLocationBtn');
                    if (locationBtn) {
                        console.log('✅ Re-adding event listener to getLocationBtn');
                        // Hapus event listener lama dulu
                        locationBtn.replaceWith(locationBtn.cloneNode(true));
                        // Tambahkan event listener baru
                        document.getElementById('getLocationBtn').addEventListener('click',
                            getVendorLocation);
                    }

                    if (!vendorMap) {
                        console.log('🔄 Initializing new vendor map...');
                        initVendorMap();
                    } else {
                        console.log('🔄 Invalidating existing vendor map size...');
                        setTimeout(() => {
                            if (vendorMap) {
                                vendorMap.invalidateSize();
                                console.log('✅ Vendor map size invalidated');
                            }
                        }, 100);
                    }
                }, 500);
            }
        });

        // Cleanup saat keluar dari step 2
        Livewire.hook('morph.removing', function({
            component
        }) {
            if (component.name === '{{ $this->getName() }}' && @this.get('currentStep') !== 2) {
                console.log('🗑️ Cleaning up vendor map...');
                if (vendorMap) {
                    try {
                        vendorMap.remove();
                        vendorMap = null;
                        vendorMarker = null;
                        console.log('✅ Vendor map cleaned up');
                    } catch (error) {
                        console.warn('⚠️ Error cleaning up vendor map:', error);
                    }
                }
            }
        });

        // Image preview & phone formatting
        document.addEventListener('livewire:initialized', () => {
            const imageInputs = document.querySelectorAll('input[type="file"]');
            imageInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        const preview = this.previousElementSibling;
                        reader.onload = function(e) {
                            if (preview.querySelector('img')) {
                                preview.querySelector('img').src = e.target.result;
                            } else {
                                preview.innerHTML = `
                                <img src="${e.target.result}" class="w-100 h-100 object-fit-cover">
                                <div class="image-overlay">
                                    <i class="bx bxs-check-circle text-success"></i>
                                </div>
                            `;
                            }
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });

            const phoneInput = document.querySelector('[wire\\:model="phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
@endpush
