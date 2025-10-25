@extends('layouts.app')

@section('title', $vendor->business_name . ' - Detail Pedagang')

@section('content')
    <div class="container py-4" style="max-width: 600px;">
        <!-- Profile Section -->
        <div class="card border-0 shadow-sm text-center p-4 mb-4">
            <img src="{{ $vendor->profile_picture ? asset('storage/' . $vendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->business_name) . '&background=92B6B1&color=white&size=120' }}"
                class="rounded-circle mx-auto mb-3 border" alt="Foto {{ $vendor->business_name }}"
                style="width: 100px; height: 100px; object-fit: cover; border-width: 3px !important;">

            <h5 class="fw-bold mb-1">{{ $vendor->business_name }}</h5>
            <p class="text-muted small mb-2">
                <i class="bx bx-tag me-1"></i>{{ $vendor->category->name ?? 'Pedagang' }}
                <span class="badge bg-{{ $vendor->type === 'formal' ? 'primary' : 'success' }} ms-2">
                    {{ $vendor->type === 'formal' ? 'Toko Tetap' : 'Pedagang Keliling' }}
                </span>
            </p>

            <!-- Rating Display -->
            <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="rating-display me-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <i
                            class="bx bx-star{{ $i <= floor($vendor->rating_avg) ? 's text-warning' : ($i <= $vendor->rating_avg ? '-half text-warning' : ' text-muted') }}"></i>
                    @endfor
                </div>
                <span class="text-muted small">
                    {{ number_format($vendor->rating_avg, 1) }} ({{ $vendor->reviews->count() }} ulasan)
                </span>
            </div>

            <!-- Status Aktif -->
            @if ($vendor->type === 'informal' && $currentLocation && $currentLocation['is_active'])
                <div class="alert alert-success py-2 mb-3">
                    <i class="bx bx-broadcast me-1"></i>
                    <strong>Sedang Berjualan!</strong>
                    <small class="d-block">Aktif sejak {{ $currentLocation['checkin_time']->diffForHumans() }}</small>
                </div>
            @elseif($vendor->type === 'informal')
                <div class="alert alert-secondary py-2 mb-3">
                    <i class="bx bx-hide me-1"></i>
                    <strong>Sedang Tidak Berjualan</strong>
                </div>
            @endif

            <div class="bg-light rounded-3 p-3 mb-3 text-start">
                <h6 class="fw-bold text-primary mb-1">
                    <i class="bx bx-info-circle me-1"></i>Deskripsi Usaha
                </h6>
                <p class="mb-0 small text-muted">{{ $vendor->description ?? 'Belum ada deskripsi.' }}</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('chat.room', $vendor->id) }}" class="btn btn-success flex-fill rounded-pill">
                    <i class="bx bx-chat me-1"></i> Chat
                </a>
                @if ($currentLocation && $currentLocation['is_active'])
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $currentLocation['latitude'] }},{{ $currentLocation['longitude'] }}"
                        target="_blank" class="btn btn-primary rounded-pill">
                        <i class="bx bx-map me-1"></i> Navigasi
                    </a>
                @endif
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills nav-justified mb-3" id="vendorTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button"
                    role="tab">
                    <i class="bx bx-info-circle me-1"></i>Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="location-tab" data-bs-toggle="pill" data-bs-target="#location" type="button"
                    role="tab">
                    <i class="bx bx-map me-1"></i>Lokasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="services-tab" data-bs-toggle="pill" data-bs-target="#services" type="button"
                    role="tab">
                    <i class="bx bx-basket me-1"></i>Layanan
                </button>
            </li>
        </ul>

        <div class="tab-content" id="vendorTabContent">
            <!-- Tab Info -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <!-- Informasi Kontak -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-primary">
                            <i class="bx bx-phone me-1"></i>Informasi Kontak
                        </h6>
                        <div class="row small text-muted">
                            <div class="col-12 mb-2">
                                <i class="bx bx-user me-2"></i>
                                <strong>Pemilik:</strong> {{ $vendor->user->name ?? 'Tidak diketahui' }}
                            </div>
                            @if ($vendor->phone)
                                <div class="col-12 mb-2">
                                    <i class="bx bx-phone me-2"></i>
                                    <strong>Telepon:</strong>
                                    <a href="tel:{{ $vendor->phone }}"
                                        class="text-decoration-none">{{ $vendor->phone }}</a>
                                </div>
                            @endif
                            @if ($vendor->type === 'formal' && $vendor->address)
                                <div class="col-12">
                                    <i class="bx bx-map me-2"></i>
                                    <strong>Alamat:</strong> {{ $vendor->address }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Informasi Usaha -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-primary">
                            <i class="bx bx-info-circle me-1"></i>Informasi Usaha
                        </h6>
                        <div class="row small text-muted">
                            <div class="col-6 mb-2">
                                <i class="bx bx-tag me-2"></i>
                                <strong>Kategori:</strong><br>
                                {{ $vendor->category->name ?? '-' }}
                            </div>
                            <div class="col-6 mb-2">
                                <i class="bx bx-store me-2"></i>
                                <strong>Tipe:</strong><br>
                                {{ $vendor->type === 'formal' ? 'Toko Tetap' : 'Pedagang Keliling' }}
                            </div>
                            <div class="col-6">
                                <i class="bx bx-star me-2"></i>
                                <strong>Rating:</strong><br>
                                {{ number_format($vendor->rating_avg, 1) }}/5.0
                            </div>
                            <div class="col-6">
                                <i class="bx bx-chat me-2"></i>
                                <strong>Ulasan:</strong><br>
                                {{ $vendor->reviews->count() }} ulasan
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Jadwal Operasional -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-primary">
                            <i class="bx bx-time me-1"></i>Jadwal Operasional
                        </h6>
                        <div class="schedule-list">
                            @php
                                $days = [
                                    'monday' => 'Senin',
                                    'tuesday' => 'Selasa',
                                    'wednesday' => 'Rabu',
                                    'thursday' => 'Kamis',
                                    'friday' => 'Jumat',
                                    'saturday' => 'Sabtu',
                                    'sunday' => 'Minggu',
                                ];
                                $today = strtolower(now()->englishDayOfWeek);
                            @endphp

                            @foreach ($days as $englishDay => $indonesianDay)
                                @php
                                    $schedule = $vendor->schedules->firstWhere('day', $englishDay);
                                    $isToday = $today === $englishDay;
                                @endphp
                                <div
                                    class="schedule-item d-flex justify-content-between align-items-center py-2 {{ $isToday ? 'bg-light rounded-2 px-2' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="{{ $isToday ? 'fw-bold text-primary' : 'text-muted' }}">{{ $indonesianDay }}</span>
                                        @if ($isToday)
                                            <span class="badge bg-primary ms-2">Hari Ini</span>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        @if ($schedule && $schedule->is_closed)
                                            <span class="text-danger small">Tutup</span>
                                        @elseif($schedule && $schedule->open_time && $schedule->close_time)
                                            <span class="{{ $isToday ? 'fw-bold text-primary' : 'text-muted' }} small">
                                                {{ \Carbon\Carbon::parse($schedule->open_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($schedule->close_time)->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <hr class="my-1">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Review Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-primary">
                            <i class="bx bx-star me-1"></i>Ulasan Pelanggan
                            <span class="badge bg-primary ms-2">{{ $vendor->reviews->count() }}</span>
                        </h6>

                        @auth
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <h6 class="fw-bold mb-2 small">Tambahkan Review</h6>
                                <form action="{{ route('vendor.review.store', $vendor) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="small text-muted mb-1 d-block">Rating:</label>
                                        <div class="rating-stars">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" id="star{{ $i }}"
                                                    value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                                <label for="star{{ $i }}"><i class="bx bxs-star"></i></label>
                                            @endfor
                                        </div>
                                        @error('rating')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <textarea name="comment" class="form-control mb-2" rows="2" placeholder="Bagaimana pengalaman Anda?">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <button type="submit" class="btn btn-success btn-sm w-100 rounded-pill">
                                        <i class="bx bx-send me-1"></i>Kirim Review
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info text-center py-2 mb-3">
                                <i class="bx bx-info-circle me-1"></i>
                                <a href="{{ route('login') }}" class="alert-link">Login</a> untuk menambahkan review
                            </div>
                        @endauth

                        <!-- Reviews List -->
                        @forelse ($vendor->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex gap-3">
                                    <img src="{{ $review->user->profile_picture ? asset('storage/' . $review->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=6c757d&color=fff&size=64' }}"
                                        class="rounded-circle flex-shrink-0"
                                        style="width: 45px; height: 45px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold mb-0 small">{{ $review->user->name }}</h6>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="bx bx-star{{ $i <= $review->rating ? 's text-warning' : ' text-muted' }} small"></i>
                                            @endfor
                                        </div>
                                        <p class="small text-muted mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bx bx-chat display-4 text-muted mb-3"></i>
                                <p class="text-muted small">Belum ada review untuk {{ $vendor->business_name }}</p>
                                <small class="text-muted">Jadilah yang pertama memberikan ulasan!</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tab Lokasi -->
            <div class="tab-pane fade" id="location" role="tabpanel">
                <!-- Informasi Lokasi -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-primary">
                            <i class="bx bx-map me-1"></i>Informasi Lokasi
                        </h6>

                        @if ($vendor->type === 'formal')
                            <!-- Pedagang Formal -->
                            <div class="d-flex align-items-start mb-2">
                                <i class="bx bx-store text-muted me-2 mt-1"></i>
                                <div>
                                    <strong>Alamat Toko:</strong>
                                    <p class="mb-0 text-muted small">{{ $vendor->address ?? 'Alamat belum tersedia' }}</p>
                                </div>
                            </div>
                        @else
                            <!-- Pedagang Informal -->
                            @if ($currentLocation && $currentLocation['is_active'])
                                <div class="d-flex align-items-start mb-2">
                                    <i class="bx bx-map-pin text-success me-2 mt-1"></i>
                                    <div>
                                        <strong>Lokasi Saat Ini:</strong>
                                        <p class="mb-0 text-muted small">
                                            {{ $currentLocation['location_name'] ?? 'Lokasi aktif' }}</p>
                                        <small class="text-success">
                                            <i class="bx bx-time me-1"></i>
                                            Check-in: {{ $currentLocation['checkin_time']->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-start mb-2">
                                    <i class="bx bx-map text-muted me-2 mt-1"></i>
                                    <div>
                                        <strong>Status:</strong>
                                        <p class="mb-0 text-muted small">Sedang tidak berjualan</p>
                                        <small class="text-muted">Pedagang akan muncul di peta saat melakukan
                                            check-in</small>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="d-flex align-items-start">
                            <i class="bx bx-info-circle text-muted me-2 mt-1"></i>
                            <div>
                                <strong>Tipe:</strong>
                                <p class="mb-0 text-muted small">
                                    {{ $vendor->type === 'formal' ? 'Toko tetap dengan alamat permanen' : 'Pedagang keliling dengan lokasi berubah' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div id="vendorMap" style="height: 400px; width: 100%; border-radius: 12px;"></div>

                        @if ($vendor->type === 'informal' && (!$currentLocation || !$currentLocation['is_active']))
                            <div class="text-center py-5">
                                <i class="bx bx-map display-4 text-muted"></i>
                                <p class="text-muted small mt-2">Peta akan muncul saat pedagang melakukan check-in</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab Layanan/Produk -->
            <div class="tab-pane fade" id="services" role="tabpanel">
                @if ($services->count() > 0)
                    <div class="row g-3">
                        @foreach ($services as $service)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            @if ($service->image_url)
                                                <div class="col-auto">
                                                    <img src="{{ asset('storage/' . $service->image_url) }}"
                                                        class="rounded-3"
                                                        style="width: 80px; height: 80px; object-fit: cover;"
                                                        alt="{{ $service->name }}">
                                                </div>
                                            @endif
                                            <div class="col">
                                                <h6 class="fw-bold mb-1">{{ $service->name }}</h6>
                                                @if ($service->price)
                                                    <p class="text-success fw-bold mb-1">Rp
                                                        {{ number_format($service->price, 0, ',', '.') }}</p>
                                                @endif
                                                @if ($service->description)
                                                    <p class="text-muted small mb-0">
                                                        {{ Str::limit($service->description, 100) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-basket display-4 text-muted mb-3"></i>
                        <p class="text-muted">Belum ada layanan atau produk</p>
                        <small class="text-muted">Pedagang ini belum menambahkan layanan</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 16px;
        }

        .nav-pills .nav-link {
            border-radius: 12px;
            background-color: #f8f9fa;
            color: #6c757d;
            font-weight: 500;
            border: 1px solid #dee2e6;
        }

        .nav-pills .nav-link.active {
            background-color: #92B6B1;
            color: white;
            border-color: #92B6B1;
        }

        .rating-stars {
            direction: rtl;
            text-align: left;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars label {
            font-size: 20px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
            margin-right: 5px;
        }

        .rating-stars input:checked~label,
        .rating-stars label:hover,
        .rating-stars label:hover~label {
            color: #ffc107;
        }

        .rating-display .bi-star-half {
            position: relative;
        }

        .rating-display .bi-star-half:before {
            content: "\f5c0";
        }

        .schedule-list hr:last-child {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tentukan koordinat untuk map
            let lat, lng, locationName;

            @if ($vendor->type === 'formal')
                // Pedagang formal: gunakan alamat tetap
                lat = {{ $vendor->latitude ?? -6.2 }};
                lng = {{ $vendor->longitude ?? 106.8 }};
                locationName = "{{ $vendor->business_name }} - Toko Tetap";
            @elseif ($currentLocation && $currentLocation['is_active'])
                // Pedagang informal dengan checkin aktif
                lat = {{ $currentLocation['latitude'] }};
                lng = {{ $currentLocation['longitude'] }};
                locationName =
                    "{{ $currentLocation['location_name'] ?? $vendor->business_name . ' - Lokasi Saat Ini' }}";
            @else
                // Pedagang informal tanpa checkin aktif - hide map
                lat = null;
                lng = null;
            @endif

            // Inisialisasi map hanya jika koordinat valid
            if (lat && lng) {
                const map = L.map('vendorMap', {
                    zoomControl: true,
                    scrollWheelZoom: false
                }).setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Buat custom icon berdasarkan tipe vendor
                const vendorIcon = L.divIcon({
                    className: 'vendor-map-marker',
                    html: `
                    <div style="
                        background-color: {{ $vendor->type === 'formal' ? '#007bff' : '#28a745' }}; 
                        width: 40px; 
                        height: 40px; 
                        border-radius: 50% 50% 50% 0; 
                        border: 4px solid white; 
                        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
                        transform: rotate(-45deg);
                        position: relative;
                        cursor: pointer;
                    ">
                        <div style="
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(45deg);
                            color: white;
                            font-size: 16px;
                            font-weight: bold;
                        ">{{ $vendor->type === 'formal' ? '🏪' : '🛒' }}</div>
                    </div>
                `,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });

                // Tambahkan marker ke map
                const marker = L.marker([lat, lng], {
                    icon: vendorIcon
                }).addTo(map);

                // Popup content dengan link navigasi
                let popupContent = `
                <div style="min-width: 220px; text-align: center;">
                    <h6 class="fw-bold mb-1">${locationName}</h6>
                    <p class="small text-muted mb-2">{{ $vendor->description ? Str::limit($vendor->description, 80) : 'Tidak ada deskripsi' }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-{{ $vendor->type === 'formal' ? 'primary' : 'success' }}">
                            {{ $vendor->type === 'formal' ? 'Toko Tetap' : 'Pedagang Keliling' }}
                        </span>
                        @if ($vendor->type === 'informal' && $currentLocation && $currentLocation['is_active'])
                            <small class="text-success">
                                <i class="bx bx-check-circle me-1"></i>Aktif
                            </small>
                        @endif
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" 
                       target="_blank" 
                       class="btn btn-primary btn-sm w-100">
                        <i class="bx bx-map me-1"></i>Buka di Google Maps
                    </a>
                </div>
            `;

                marker.bindPopup(popupContent).openPopup();

                // Click event untuk marker - redirect ke Google Maps
                marker.on('click', function() {
                    window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`,
                        '_blank');
                });

                // Pastikan map di-render dengan benar
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            } else {
                // Tampilkan pesan jika map tidak bisa ditampilkan
                document.getElementById('vendorMap').innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bx bx-map display-4"></i>
                    <p class="mt-3 mb-0">Lokasi tidak tersedia</p>
                    <small>Pedagang belum mengatur lokasi atau sedang tidak berjualan</small>
                </div>
            `;
            }

            // Handle tab changes untuk resize map
            const tabTriggers = document.querySelectorAll('button[data-bs-toggle="pill"]');
            tabTriggers.forEach(trigger => {
                trigger.addEventListener('shown.bs.tab', function(e) {
                    if (e.target.id === 'location-tab' && map) {
                        setTimeout(() => {
                            map.invalidateSize();
                        }, 300);
                    }
                });
            });
        });
    </script>
@endpush
