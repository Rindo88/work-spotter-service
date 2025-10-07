<div>
    <!-- Informational Sections -->
    @if ($currentStep == 1)
        <div class="mt-4">
            <div class="alert alert-info">
                <h6><i class="bi bi-info-circle me-2"></i>Informasi Penting</h6>
                <p class="mb-0">Pastikan data yang Anda masukkan akurat dan valid. Data ini akan ditampilkan kepada
                    calon pelanggan.</p>
            </div>
        </div>
    @endif

    @if ($currentStep == 2)
        <div class="mt-4">
            <div class="alert alert-warning">
                <h6><i class="bi bi-geo-alt me-2"></i>Pentingnya Lokasi</h6>
                <p class="mb-0">Lokasi yang akurat membantu pelanggan menemukan usaha Anda dengan mudah. Pastikan
                    koordinat sesuai dengan lokasi sebenarnya.</p>
            </div>
        </div>
    @endif

    @if ($currentStep == 3)
        <div class="mt-4">
            <div class="alert alert-success">
                <h6><i class="bi bi-camera me-2"></i>Tips Foto Usaha</h6>
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
                    <button type="button" class="btn btn-outline-primary" onclick="getLocation()">
                        <i class="bi bi-geo-alt me-2"></i>Dapatkan Lokasi Otomatis
                    </button>
                    <div class="form-text">Klik tombol di atas untuk mendapatkan lokasi Anda secara otomatis</div>
                </div>

                <!-- Map Container - Selalu ditampilkan -->
                <div class="mb-3">
                    <div id="map-container" class="map-container" style="height: 0; overflow: hidden;"></div>
                    <div class="form-text">Klik pada peta atau geser pin untuk menentukan lokasi yang tepat</div>
                </div>

                <!-- Input Koordinat -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Latitude</label>
                        <input type="number" step="any" class="form-control" wire:model="latitude"
                            id="latitude-input" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Longitude</label>
                        <input type="number" step="any" class="form-control" wire:model="longitude"
                            id="longitude-input" required>
                    </div>
                </div>

                <!-- Input Alamat -->
                <div class="mb-3">
                    <label class="form-label required-field">Alamat</label>
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

            <!-- ... (kode jadwal operasional tetap sama) ... -->
        </div>
    @endif

    <!-- Step 3: Documents & Images -->
    @if ($currentStep === 3)
        <div class="form-section">
            <h3 class="section-title">
                <span class="section-number">3</span>
                {{ $vendorType === 'formal' ? 'Dokumen & Foto' : 'Foto Usaha' }}
            </h3>

            <div class="mb-3">
                <label class="form-label required-field">Foto Usaha (Maksimal 4)</label>
                <div class="image-upload-grid">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="image-upload-item">
                            <label class="image-preview" for="business_image_{{ $i }}">
                                @if (isset($business_images[$i]))
                                    <img src="{{ $business_images[$i]->temporaryUrl() }}" alt="Preview">
                                    <div class="image-overlay">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    </div>
                                @else
                                    <div class="image-preview-placeholder">
                                        <i class="bi bi-plus-circle"></i>
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
                        <i class="bi bi-info-circle me-2"></i>
                        <span>{{ count($business_images) }} foto terpilih</span>
                    </div>
                @endif
            </div>
        </div>
    @endif


    <!-- Navigation Buttons -->
    <div class="form-navigation">
        @if ($currentStep > 1)
            <button type="button" class="btn btn-outline-secondary" wire:click="previousStep">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </button>
        @else
            <div></div>
        @endif

        @if ($currentStep < 3)
            <button type="button" class="btn btn-primary" wire:click="nextStep">
                Lanjut <i class="bi bi-arrow-right ms-2"></i>
            </button>
        @else
            <button type="button" class="btn btn-success" wire:click="submit">
                <i class="bi bi-check-circle me-2"></i>Daftar Sekarang
            </button>
        @endif
    </div>

    <!-- Modal untuk hasil lokasi -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel">Lokasi Ditemukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Lokasi Anda berhasil dideteksi. Silakan periksa dan sesuaikan pin pada peta untuk keakuratan.</p>
                    <div class="alert alert-info">
                        <strong>Alamat terdeteksi:</strong>
                        <div id="detected-address">{{ $address_auto ?? '' }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="confirm-location">Gunakan Lokasi Ini</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk error lokasi -->
    <div class="modal fade" id="locationErrorModal" tabindex="-1" aria-labelledby="locationErrorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationErrorModalLabel">Gagal Mendapatkan Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="location-error-message">Terjadi kesalahan saat mendapatkan lokasi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>



@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

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

        .schedule-container {
            background-color: #f8f9fa;
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
            background: #007bff;
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

        .location-mode-btn.active {
            background-color: #0d6efd;
            color: white;
        }

        .leaflet-tile {
            transform: none !important;
            image-rendering: pixelated;
        }

        .leaflet-container {
            z-index: 1;
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

        .map-container {
            width: 100%;
            max-width: 400px;
            height: 300px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: #f8f9fa;
            overflow: hidden;
            margin: 0 auto;
            position: relative;
        }

        .leaflet-container {
            width: 100% !important;
            height: 100% !important;
            border-radius: 12px;
            position: relative;
        }

        .leaflet-tile-container,
        .leaflet-marker-pane,
        .leaflet-shadow-pane,
        .leaflet-overlay-pane {
            transform: none !important;
        }

        .leaflet-tile {
            transform: none !important;
            image-rendering: pixelated;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        // Fungsi untuk inisialisasi peta — HANYA JIKA #map-container ADA
        function initMap() {
            const mapContainer = document.getElementById('map-container');
            if (!mapContainer || map) return;

            // Set tinggi dan tampilkan
            mapContainer.style.height = '300px';
            mapContainer.style.overflow = 'hidden';

            // Delay kecil untuk memastikan DOM siap
            setTimeout(() => {
                map = L.map('map-container').setView([-6.2088, 106.8456], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    noWrap: true,
                    bounds: [
                        [-90, -180],
                        [90, 180]
                    ]
                }).addTo(map);

                marker = L.marker([-6.2088, 106.8456], {
                    draggable: true
                }).addTo(map);

                map.on('click', function(e) {
                    updateMarker(e.latlng.lat, e.latlng.lng);
                    updateCoordinateInputs(e.latlng.lat, e.latlng.lng);
                    reverseGeocode(e.latlng.lat, e.latlng.lng);
                });

                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    updateCoordinateInputs(position.lat, position.lng);
                    reverseGeocode(position.lat, position.lng);
                });

                // Paksa ukuran
                setTimeout(() => {
                    if (map) map.invalidateSize();
                }, 100);

            }, 100);
        }

        // Fungsi update marker
        function updateMarker(lat, lng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);
            map.setView([lat, lng], 15);

            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                updateCoordinateInputs(position.lat, position.lng);
                reverseGeocode(position.lat, position.lng);
            });
        }

        // Update input koordinat & Livewire state
        function updateCoordinateInputs(lat, lng) {
            const latInput = document.getElementById('latitude-input');
            const lngInput = document.getElementById('longitude-input');
            if (latInput) latInput.value = lat;
            if (lngInput) lngInput.value = lng;
            @this.set('latitude', lat);
            @this.set('longitude', lng);
        }

        // Reverse geocoding
        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        const address = data.display_name;
                        @this.set('address', address);
                        const addrInput = document.getElementById('address-input');
                        if (addrInput) addrInput.value = address;
                        const detectedAddr = document.getElementById('detected-address');
                        if (detectedAddr) detectedAddr.textContent = address;
                    }
                })
                .catch(error => console.error('Error reverse geocoding:', error));
        }

        // Setup listener input manual
        function setupCoordinateListeners() {
            const latInput = document.getElementById('latitude-input');
            const lngInput = document.getElementById('longitude-input');

            if (latInput && lngInput) {
                latInput.addEventListener('change', function() {
                    if (this.value && lngInput.value) {
                        const lat = parseFloat(this.value);
                        const lng = parseFloat(lngInput.value);
                        updateMarker(lat, lng);
                        reverseGeocode(lat, lng);
                    }
                });

                lngInput.addEventListener('change', function() {
                    if (this.value && latInput.value) {
                        const lat = parseFloat(latInput.value);
                        const lng = parseFloat(this.value);
                        updateMarker(lat, lng);
                        reverseGeocode(lat, lng);
                    }
                });
            }
        }

        // Ambil lokasi pengguna
        function getLocation() {
            if (!navigator.geolocation) {
                showLocationError('Geolocation tidak didukung browser Anda');
                return;
            }

            const locationBtn = document.querySelector('[onclick="getLocation()"]');
            const originalText = locationBtn.innerHTML;
            locationBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Mendeteksi...';
            locationBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    locationBtn.innerHTML = originalText;
                    locationBtn.disabled = false;

                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    updateCoordinateInputs(lat, lng);
                    updateMarker(lat, lng);
                    reverseGeocode(lat, lng);

                    const modalElement = document.getElementById('locationModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    // Tambahkan event listener ke tombol konfirmasi
                    const confirmBtn = document.getElementById('confirm-location');
                    if (confirmBtn) {
                        // Hapus event listener lama (jika ada) agar tidak double
                        confirmBtn.replaceWith(confirmBtn.cloneNode(true));
                        document.getElementById('confirm-location').addEventListener('click', function() {
                            modal.hide(); // Tutup modal secara manual
                            // Lokasi sudah di-set via updateCoordinateInputs() & updateMarker()
                        });
                    }
                },
                (error) => {
                    locationBtn.innerHTML = originalText;
                    locationBtn.disabled = false;

                    let errorMessage = 'Gagal mendapatkan lokasi: ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Izin lokasi ditolak.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Permintaan lokasi timeout.';
                            break;
                        default:
                            errorMessage += 'Error tidak diketahui.';
                    }
                    showLocationError(errorMessage);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                }
            );
        }

        function showLocationError(message) {
            const errorMsg = document.getElementById('location-error-message');
            if (errorMsg) errorMsg.textContent = message;
            const errorModal = new bootstrap.Modal(document.getElementById('locationErrorModal'));
            errorModal.show();
        }

        // ================ INISIALISASI & HOOKS LIVWIRE ================

        document.addEventListener('livewire:initialized', () => {
            // Coba inisialisasi peta jika step 2 aktif
            if (@this.get('currentStep') === 2) {
                setTimeout(() => {
                    initMap();
                    setupCoordinateListeners();
                    if (@this.get('latitude') && @this.get('longitude')) {
                        updateMarker(@this.get('latitude'), @this.get('longitude'));
                    }
                }, 100); // Delay kecil untuk pastikan DOM siap
            }

            // Setup event listener untuk tombol konfirmasi lokasi (jaga-jaga)
            const confirmBtnGlobal = document.getElementById('confirm-location');
            if (confirmBtnGlobal) {
                confirmBtnGlobal.replaceWith(confirmBtnGlobal.cloneNode(true));
                document.getElementById('confirm-location').addEventListener('click', function() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('locationModal'));
                    if (modal) modal.hide();
                });
            }
        });

        // Re-inisialisasi saat Livewire update DOM (misal: ganti step)
        Livewire.hook('morph.updated', ({
            el,
            component
        }) => {
            if (component.name === '{{ $this->getName() }}') {
                if (@this.get('currentStep') === 2) {
                    setTimeout(() => {
                        initMap();
                        setupCoordinateListeners();
                        if (@this.get('latitude') && @this.get('longitude')) {
                            updateMarker(@this.get('latitude'), @this.get('longitude'));
                        } else {
                            updateMarker(-6.2088, 106.8456);
                        }
                        // Paksa ukuran lagi
                        if (map) {
                            setTimeout(() => map.invalidateSize(), 200);
                        }
                    }, 300); // Delay lebih lama
                }
            }
        });

        // Image preview & phone formatting (tetap sama)
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
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </div>
                            `;
                            }
                        }
                        reader.readAsDataURL(this.files[0]);
                        @this.call('$refresh');
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
