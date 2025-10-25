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
                    @livewire('search.search-bar')
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
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Akses Cepat</h5>
            <a href="{{ route('quick-access.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                Lihat Semua <i class="bx bx-right-arrow-alt ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="row g-0">
                @php
                    $quickAccess = [
                        ['icon' => 'bx-cart', 'color' => 'var(--primary-color)', 'title' => 'Pedagang Informal', 'desc' => 'Kaki lima & UMKM', 'type' => 'informal'],
                        ['icon' => 'bxs-star', 'color' => 'var(--primary-color)', 'title' => 'Rating Tertinggi', 'desc' => 'Terbaik & Terpercaya', 'type' => 'top-rated'],
                        ['icon' => 'bxs-map', 'color' => 'var(--primary-color)', 'title' => 'Lokasi Terdekat', 'desc' => 'Ditempat Anda', 'type' => 'nearby'],
                        ['icon' => 'bxs-offer', 'color' => 'var(--primary-color)', 'title' => 'Promo Spesial', 'desc' => 'Diskon & Penawaran', 'type' => 'promo'],
                    ];
                @endphp
                @foreach ($quickAccess as $index => $item)
                    <div class="col-6 {{ $index % 2 === 0 ? 'border-end border-secondary-subtle' : '' }} {{ $index < 2 ? 'border-bottom border-secondary-subtle' : '' }}">
                        <a href="{{ route('quick-access.index', ['type' => $item['type']]) }}" class="text-decoration-none">
                            <div class="quick-access-card text-center p-3 h-100">
                                <div class="icon-wrapper mb-2 mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; background: {{ $item['color'] }};">
                                    <i class="bx {{ $item['icon'] }} fs-4 text-white"></i>
                                </div>
                                <h6 class="small text-dark mb-1 fw-bold text-nowrap">{{ $item['title'] }}</h6>
                                <small class="text-muted text-nowrap">{{ $item['desc'] }}</small>
                            </div>
                        </a>
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
                    $categoryIcons = [
                        'Makanan' => ['icon' => 'bxs-coffee', 'color' => 'text-primary'],
                        'Minuman' => ['icon' => 'bx-drink', 'color' => 'text-primary'],
                        'Reparasi' => ['icon' => 'bx-wrench', 'color' => 'text-primary'],
                        'Pakaian' => ['icon' => 'bxs-t-shirt', 'color' => 'text-primary'],
                        'Elektronik' => ['icon' => 'bx-laptop', 'color' => 'text-primary'],
                        'Kesehatan' => ['icon' => 'bx-health', 'color' => 'text-primary'],
                        'Pendidikan' => ['icon' => 'bx-book', 'color' => 'text-primary'],
                        'Transportasi' => ['icon' => 'bx-car', 'color' => 'text-primary'],
                        'Jasa' => ['icon' => 'bx-briefcase', 'color' => 'text-primary'],
                        'Lainnya' => ['icon' => 'bx-dots-horizontal-rounded', 'color' => 'text-muted'],
                        'default' => ['icon' => 'bx-dots-horizontal-rounded', 'color' => 'text-muted'],
                    ];
                @endphp
                @foreach ($categories as $index => $category)
                    <div class="col-4 col-md-2 {{ $index % 3 !== 2 ? 'border-end border-secondary-subtle' : '' }} {{ $index < count($categories) - (count($categories) % 3 ?: 3) ? 'border-secondary-subtle' : '' }}">
                        <a href="{{ route('category.show', $category) }}" class="text-decoration-none">
                            <div class="category-card p-2">
                                <div class="icon-wrapper mb-2 mx-auto">
                                    @php
                                        $iconData = $categoryIcons[$category->name] ?? $categoryIcons['default'];
                                    @endphp
                                    <i class="bx {{ $iconData['icon'] }} {{ $iconData['color'] }} fs-4"></i>
                                </div>
                                <h6 class="mb-0 small fw-bold text-dark">{{ $category->name }}</h6>
                            </div>
                        </a>
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
                                        <i class="bx bxs-star me-1"></i>{{ number_format($vendor->rating, 1) }}
                                    </span>
                                    <span class="text-muted small">
                                        <i class="bx bx-map-pin me-1"></i>{{ $vendor->distance ?? '—' }} km
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
            <div class="row g-3" id="vendors-container">
                @forelse ($vendors as $vendor)
                    @include('partials.vendor-card', ['vendor' => $vendor])
                @empty
                    <p class="text-center text-muted small">Belum ada vendor yang terdaftar.</p>
                @endforelse
            </div>

            @if($vendors->hasMorePages())
            <div class="d-flex justify-content-center mt-4">
                <button id="load-more-btn" class="btn btn-outline-primary rounded-pill px-4 py-2" data-page="2">
                    <span class="btn-text">Muat Lebih Banyak</span>
                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('styles')
@livewireStyles
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    const vendorsContainer = document.getElementById('vendors-container');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const page = this.getAttribute('data-page');
            const btnText = this.querySelector('.btn-text');
            const spinner = this.querySelector('.spinner-border');
            
            // Show loading state
            btnText.textContent = 'Memuat...';
            spinner.classList.remove('d-none');
            this.disabled = true;
            
            // Make AJAX request
            fetch(`{{ route('api.vendors.load-more') }}?page=${page}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    // Append new vendors to container
                    vendorsContainer.insertAdjacentHTML('beforeend', data.html);
                    
                    // Update page number for next request
                    this.setAttribute('data-page', data.nextPage);
                    
                    // Hide button if no more data
                    if (!data.hasMore) {
                        this.style.display = 'none';
                    }
                }
                
                // Reset button state
                btnText.textContent = 'Muat Lebih Banyak';
                spinner.classList.add('d-none');
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error loading more vendors:', error);
                
                // Reset button state
                btnText.textContent = 'Muat Lebih Banyak';
                spinner.classList.add('d-none');
                this.disabled = false;
                
                // Show error message
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
            });
        });
    }
});
</script>
@endpush
