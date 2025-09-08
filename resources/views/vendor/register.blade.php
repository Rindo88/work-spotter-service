@extends('layouts.vendor-registration')

@section('title', 'Daftar Sebagai Pedagang - Work Spotter')

@section('content')
    <form action="{{ route('vendor.register.submit') }}" method="POST" enctype="multipart/form-data" id="vendorForm">
        @csrf

        <!-- Upload Foto Profil -->
        <div class="form-section">
            <h3 class="section-title">Pilih Foto</h3>

            <div class="photo-upload-container">
                <div id="imagePreview" class="profile-preview d-flex align-items-center justify-content-center bg-light">
                    <i class="bi bi-camera fs-1 text-muted"></i>
                </div>

                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="d-none">
                <label for="profile_picture" class="upload-btn">
                    <i class="bi bi-cloud-upload me-2"></i>Pilih Foto
                </label>

                @error('profile_picture')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Nama Usaha -->
        <div class="form-section">
            <div class="mb-3">
                <label for="business_name" class="form-label required-field">Nama Usaha</label>
                <input type="text" class="form-control" id="business_name" name="business_name"
                    value="{{ old('business_name') }}" required placeholder="Masukkan nama usaha Anda">
                @error('business_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Jenis Jasa/Produk -->
        <div class="form-section">
            <div class="mb-3">
                <label for="category_id" class="form-label required-field">Jenis Jasa/Produk</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Pilih jenis jasa/produk anda</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Deskripsi Singkat -->
        <div class="form-section">
            <div class="mb-3">
                <label for="description" class="form-label required-field">Deskripsi Singkat</label>
                <textarea class="form-control" id="description" name="description" rows="4" required
                    placeholder="Masukkan deskripsi singkat usaha Anda">{{ old('description') }}</textarea>
                <div class="input-hint">Jelaskan secara singkat tentang usaha Anda</div>
                @error('description')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Alamat -->
        <div class="form-section">
            <div class="mb-3">
                <label for="address" class="form-label required-field">Alamat Usaha</label>
                <textarea class="form-control" id="address" name="address" rows="3" required
                    placeholder="Masukkan alamat lengkap usaha Anda">{{ old('address') }}</textarea>
                @error('address')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Koordinat Lokasi -->
        <div class="form-section">
            <h4 class="form-label required-field">Koordinat Lokasi</h4>

            <div class="coordinate-buttons mb-3">
                <button type="button" id="getLocationBtn" class="btn btn-outline-primary">
                    <i class="bi bi-geo-alt me-2"></i>Dapatkan Lokasi Otomatis
                </button>
                <button type="button" id="manualLocationBtn" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Input Manual
                </button>
            </div>

            <div id="manualInput" style="display: none;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="number" step="any" class="form-control" id="latitude" name="latitude"
                            value="{{ old('latitude') }}" placeholder="-6.2088">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="number" step="any" class="form-control" id="longitude" name="longitude"
                            value="{{ old('longitude') }}" placeholder="106.8456">
                    </div>
                </div>
                <div class="input-hint">Koordinat akan digunakan untuk menampilkan lokasi Anda di peta</div>
            </div>

            <div id="locationStatus" class="alert alert-info mt-3" style="display: none;"></div>

            @error('latitude')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            @error('longitude')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nomor WhatsApp -->
        <div class="form-section">
            <div class="mb-3">
                <label for="phone" class="form-label required-field">No. WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input type="tel" class="form-control" id="phone" name="phone"
                        value="{{ old('phone', Auth::user()->phone) }}" required placeholder="8123456789" pattern="[0-9]{9,13}">
                </div>
                <div class="input-hint">Gunakan format 8123456789 (tanpa +62)</div>
                @error('phone')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <div class="form-section">
            <div class="mb-3">
                <label for="email" class="form-label required-field">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', Auth::user()->email) }}" required placeholder="nama@contoh.com">
                <div class="input-hint">Email akan digunakan untuk komunikasi penting</div>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100 mt-4">
            Daftar Sebagai Pedagang
        </button>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview functionality
            const profilePictureInput = document.getElementById('profile_picture');
            const imagePreview = document.getElementById('imagePreview');

            profilePictureInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imagePreview.innerHTML =
                            `<img src="${e.target.result}" class="profile-preview" alt="Preview">`;
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Location handling
            const getLocationBtn = document.getElementById('getLocationBtn');
            const manualLocationBtn = document.getElementById('manualLocationBtn');
            const manualInput = document.getElementById('manualInput');
            const locationStatus = document.getElementById('locationStatus');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const form = document.getElementById('vendorForm');

            let hasLocation = false;

            // Manual input toggle
            manualLocationBtn.addEventListener('click', function() {
                manualInput.style.display = 'block';
                locationStatus.style.display = 'none';
                hasLocation = false;
            });

            // Get location automatically
            getLocationBtn.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    showLocationStatus('Geolocation tidak didukung oleh browser Anda.', 'danger');
                    return;
                }

                showLocationStatus('Mengambil lokasi...', 'info');

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude.toFixed(6);
                        const lng = position.coords.longitude.toFixed(6);

                        latitudeInput.value = lat;
                        longitudeInput.value = lng;
                        manualInput.style.display = 'block';
                        hasLocation = true;

                        showLocationStatus(`Lokasi berhasil didapatkan: ${lat}, ${lng}`, 'success');
                    },
                    function(error) {
                        let errorMessage = 'Gagal mendapatkan lokasi. ';
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
                        showLocationStatus(errorMessage, 'danger');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            });

            function showLocationStatus(message, type) {
                locationStatus.textContent = message;
                locationStatus.className = `alert alert-${type} mt-3`;
                locationStatus.style.display = 'block';
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                if (!hasLocation && (!latitudeInput.value || !longitudeInput.value)) {
                    e.preventDefault();
                    showLocationStatus('Silakan pilih metode untuk mendapatkan koordinat lokasi.',
                    'danger');
                    manualInput.style.display = 'block';
                }
            });

            // Format phone number input
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
@endpush
