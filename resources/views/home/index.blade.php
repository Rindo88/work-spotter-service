{{-- resources/views/home/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Work Spotter - Home')
@section('header-title', 'Work Spotter')

@section('content')
<div class="container-fluid px-0">

    <!-- Search Bar & Notification -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Search Input -->
                <div class="flex-grow-1 position-relative">
                    <i class="bi bi-search position-absolute top-50 start-3 translate-middle-y text-muted"></i>
                    <input type="text" class="form-control ps-5 rounded-pill" placeholder="Cari layanan atau vendor...">
                </div>

                <!-- Notification Icon -->
                @livewire('notification.notifications-vendor')
            </div>
        </div>
    </div>

    <!-- Banner Slider -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="banner-item d-flex align-items-center justify-content-center text-white"
                            style="height: 200px; background: linear-gradient(135deg, #92B6B1, #6D8B87);">
                            <div class="text-center">
                                <h5 class="fw-bold mb-2">Temukan Vendor Terbaik</h5>
                                <p class="mb-0 small">Layanan berkualitas dengan harga terjangkau</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="banner-item d-flex align-items-center justify-content-center text-white"
                            style="height: 200px; background: linear-gradient(135deg, #FF6B6B, #FF8E8E);">
                            <div class="text-center">
                                <h5 class="fw-bold mb-2">Promo Spesial</h5>
                                <p class="mb-0 small">Diskon hingga 50% untuk layanan tertentu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Akses Cepat</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @php
                    $quickAccess = [
                        ['icon' => 'bi-cart', 'color' => '#28a745,#20c997', 'title' => 'Pedagang Informal', 'desc' => 'Kaki lima & UMKM'],
                        ['icon' => 'bi-star-fill', 'color' => '#ffc107,#fd7e14', 'title' => 'Rating Tertinggi', 'desc' => 'Terbaik & Terpercaya'],
                        ['icon' => 'bi-geo-alt-fill', 'color' => '#dc3545,#e83e8c', 'title' => 'Lokasi Terdekat', 'desc' => 'Ditempat Anda'],
                    ];
                @endphp
                @foreach ($quickAccess as $item)
                    <div class="col-4 col-md-3 col-lg-2">
                        <div class="quick-access-card text-center p-2">
                            <div class="icon-wrapper mb-2 mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background: linear-gradient(135deg, {{ $item['color'] }});">
                                <i class="bi {{ $item['icon'] }} fs-5 text-white"></i>
                            </div>
                            <h6 class="mb-1 small fw-bold">{{ $item['title'] }}</h6>
                            <small class="text-muted">{{ $item['desc'] }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Kategori Layanan</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 text-center">
                @php
                    $categories = [
                        ['icon' => 'bi-cup-hot-fill', 'color' => 'text-warning', 'name' => 'Makanan'],
                        ['icon' => 'bi-cup-straw', 'color' => 'text-success', 'name' => 'Minuman'],
                        ['icon' => 'bi-tools', 'color' => 'text-primary', 'name' => 'Reparasi'],
                        ['icon' => 'bi-bag-fill', 'color' => 'text-info', 'name' => 'Fashion'],
                        ['icon' => 'bi-laptop', 'color' => 'text-secondary', 'name' => 'Elektronik'],
                        ['icon' => 'bi-three-dots', 'color' => 'text-muted', 'name' => 'Lainnya'],
                    ];
                @endphp
                @foreach ($categories as $cat)
                    <div class="col-4 col-md-2">
                        <div class="category-card p-2">
                            <i class="bi {{ $cat['icon'] }} fs-2 {{ $cat['color'] }}"></i>
                            <h6 class="mb-0 small fw-bold mt-1">{{ $cat['name'] }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recommended Vendors -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Rekomendasi</h5>
            <a href="#" class="text-primary text-decoration-none small">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse ($recommendedVendors as $vendor)
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <a href="{{ route('vendor.show', $vendor) }}" class="text-decoration-none text-dark">
                            <div class="vendor-grid-card h-100">
                                <img src="{{ $vendor->image_url ?? 'https://placehold.co/300x200' }}"
                                    class="w-100 rounded-3 mb-2" style="height: 160px; object-fit: cover;">
                                <h6 class="fw-bold mb-1 text-truncate">{{ $vendor->business_name }}</h6>
                                <p class="text-muted small mb-1 text-truncate">{{ $vendor->category->name ?? '-' }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">
                                        <i class="bi bi-star-fill me-1"></i>{{ number_format($vendor->rating, 1) }}
                                    </span>
                                    <span class="text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $vendor->distance ?? '—' }} km
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-center text-muted small">Belum ada vendor rekomendasi.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- All Vendors -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Semua Vendor</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse ($vendors as $vendor)
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <a href="{{ route('vendor.show', $vendor) }}" class="text-decoration-none text-dark">
                            <div class="vendor-grid-card h-100">
                                <img src="{{ $vendor->image_url ?? 'https://placehold.co/300x200' }}"
                                    class="w-100 rounded-3 mb-2" style="height: 140px; object-fit: cover;">
                                <h6 class="fw-bold mb-1 text-truncate">{{ $vendor->business_name }}</h6>
                                <p class="text-muted small mb-1 text-truncate">{{ $vendor->category->name ?? '-' }}</p>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="badge bg-success">
                                        <i class="bi bi-star-fill me-1"></i>{{ number_format($vendor->rating, 1) }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $vendor->distance ?? '—' }} km
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-center text-muted small">Belum ada vendor yang terdaftar.</p>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $vendors->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.quick-access-card, .category-card, .vendor-grid-card {
    transition: all .3s ease;
    cursor: pointer;
    border-radius: 12px;
}
.quick-access-card:hover, .category-card:hover, .vendor-grid-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}
.vendor-grid-card {
    background: #fff;
    padding: 12px;
    height: 100%;
}
.text-truncate {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
@endpush
