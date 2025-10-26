{{-- resources/views/profile/favorites.blade.php --}}
@extends('layouts.app')
@section('title', 'Favorit - Work Spotter')
@section('header-title', 'Favorit')

@section('content')
<div class="container-fluid px-3 py-2">
    @if($vendorFavorites->count() > 0 || $serviceFavorites->count() > 0)
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-3" id="favoritesTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="vendors-tab" data-bs-toggle="tab" data-bs-target="#vendors" type="button" role="tab">
                    Vendor ({{ $vendorFavorites->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">
                    Layanan ({{ $serviceFavorites->count() }})
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="favoritesTabContent">
            <!-- Vendor Favorites -->
            <div class="tab-pane fade show active" id="vendors" role="tabpanel">
                @foreach($vendorFavorites as $vendor)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ $vendor->logo ?? asset('assets/images/default-vendor.jpg') }}" 
                                     class="rounded-circle me-3" 
                                     width="50" 
                                     height="50" 
                                     alt="{{ $vendor->name }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $vendor->name }}</h6>
                                    <p class="text-muted small mb-1">
                                        <i class="bx bx-map-pin"></i>
                                        {{ $vendor->address }}
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark small">
                                            <i class="bx bx-star text-warning"></i>
                                            {{ $vendor->rating ?? '4.5' }}
                                        </span>
                                        <span class="badge bg-light text-dark small ms-2">
                                            <i class="bx bx-heart text-danger"></i>
                                            {{ $vendor->favorites_count }}
                                        </span>
                                    </div>
                                </div>
                                <button class="btn btn-outline-danger btn-sm unfavorite-vendor" 
                                        data-vendor-id="{{ $vendor->id }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Service Favorites -->
            <div class="tab-pane fade" id="services" role="tabpanel">
                @foreach($serviceFavorites as $service)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ $service->image ?? asset('assets/images/default-service.jpg') }}" 
                                     class="rounded me-3" 
                                     width="50" 
                                     height="50" 
                                     alt="{{ $service->name }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $service->name }}</h6>
                                    <p class="text-muted small mb-1">
                                        <i class="bx bx-store"></i>
                                        {{ $service->vendor->name }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-primary fw-bold">
                                            Rp {{ number_format($service->price, 0, ',', '.') }}
                                        </span>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-light text-dark small">
                                                <i class="bx bx-star text-warning"></i>
                                                {{ $service->rating ?? '4.5' }}
                                            </span>
                                            <span class="badge bg-light text-dark small ms-2">
                                                <i class="bx bx-heart text-danger"></i>
                                                {{ $service->favorites_count }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-outline-danger btn-sm unfavorite-service ms-2" 
                                        data-service-id="{{ $service->id }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="bx bx-bookmark display-1 text-muted mb-3"></i>
                    <h6 class="text-muted">Belum ada favorit</h6>
                    <p class="text-muted small">Vendor dan layanan yang Anda simpan akan muncul di sini</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                        <i class="bx bx-search me-2"></i>
                        Jelajahi Vendor
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Unfavorite Vendor
    document.querySelectorAll('.unfavorite-vendor').forEach(button => {
        button.addEventListener('click', function() {
            const vendorId = this.dataset.vendorId;
            unfavoriteVendor(vendorId, this);
        });
    });

    // Unfavorite Service
    document.querySelectorAll('.unfavorite-service').forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.dataset.serviceId;
            unfavoriteService(serviceId, this);
        });
    });

    function unfavoriteVendor(vendorId, button) {
        if (!confirm('Hapus vendor dari favorit?')) return;

        fetch(`/vendors/${vendorId}/unfavorite`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            button.closest('.card').remove();
            updateTabCounts();
            showToast(data.message);
        })
        .catch(error => console.error('Error:', error));
    }

    function unfavoriteService(serviceId, button) {
        if (!confirm('Hapus layanan dari favorit?')) return;

        fetch(`/services/${serviceId}/unfavorite`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            button.closest('.card').remove();
            updateTabCounts();
            showToast(data.message);
        })
        .catch(error => console.error('Error:', error));
    }

    function updateTabCounts() {
        // Update counts setelah menghapus
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    function showToast(message) {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>
@endpush