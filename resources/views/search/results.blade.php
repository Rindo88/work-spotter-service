@extends('layouts.app')

@section('title', 'Hasil Pencarian - Work Spotter')
@section('header-title', 'Hasil Pencarian')

@section('content')
<div class="container-fluid px-0">
    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="flex-grow-1">
                    @livewire('search.search-bar')
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Hasil Pencarian: "{{ $query }}"</h5>
            
            @if($vendors->count() > 0)
                <h6 class="fw-medium mb-2">Vendor</h6>
                <div class="vendor-results mb-4">
                    <div class="row g-3">
                        @foreach($vendors as $vendor)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm h-100">
                                    <a href="{{ route('vendor.show', $vendor->id) }}" class="text-decoration-none text-dark">
                                        <div class="row g-0">
                                            <!-- Vendor Image -->
                                            <div class="col-4">
                                                <div class="vendor-image h-100" style="overflow: hidden;">
                                                    @if($vendor->profile_picture)
                                                        <img src="{{ Storage::url($vendor->profile_picture) }}" alt="{{ $vendor->business_name }}" class="img-fluid h-100 w-100 object-fit-cover rounded-start">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-start">
                                                            <i class="bi bi-shop fs-1 text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Vendor Info -->
                                            <div class="col-8">
                                                <div class="card-body h-100 d-flex flex-column">
                                                    <h6 class="fw-bold mb-1">{{ $vendor->business_name }}</h6>
                                                    <p class="text-muted small mb-2">{{ $vendor->category->name ?? 'Uncategorized' }}</p>
                                                    <p class="small mb-2">{{ Str::limit($vendor->description, 80) }}</p>
                                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-star-fill me-1"></i>{{ number_format($vendor->rating_avg ?? 0, 1) }}
                                                        </span>
                                                        <small class="text-muted">{{ $vendor->review_count ?? 0 }} ulasan</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3">
                        {{ $vendors->appends(['query' => $query])->links() }}
                    </div>
                </div>
            @endif
            
            @if($services->count() > 0)
                <h6 class="fw-medium mb-2">Layanan</h6>
                <div class="service-results">
                    <div class="row g-3">
                        @foreach($services as $service)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm h-100">
                                    <a href="{{ route('vendor.show', $service->vendor_id) }}" class="text-decoration-none text-dark">
                                        <div class="row g-0">
                                            <!-- Service Image -->
                                            <div class="col-4">
                                                <div class="service-image h-100" style="overflow: hidden;">
                                                    @if($service->image)
                                                        <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" class="img-fluid h-100 w-100 object-fit-cover rounded-start">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-start">
                                                            <i class="bi bi-tools fs-1 text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Service Info -->
                                            <div class="col-8">
                                                <div class="card-body h-100 d-flex flex-column">
                                                    <h6 class="fw-bold mb-1">{{ $service->name }}</h6>
                                                    <p class="text-muted small mb-2">{{ $service->vendor->business_name }}</p>
                                                    <p class="small mb-2">{{ Str::limit($service->description, 80) }}</p>
                                                    <div class="mt-auto">
                                                        <span class="badge bg-primary">
                                                            <i class="bi bi-tag-fill me-1"></i>Rp {{ number_format($service->price, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3">
                        {{ $services->appends(['query' => $query])->links() }}
                    </div>
                </div>
            @endif
            
            @if($vendors->count() == 0 && $services->count() == 0)
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h6 class="text-muted">Tidak ada hasil yang ditemukan</h6>
                    <p class="text-muted small">Coba dengan kata kunci lain atau periksa ejaan Anda</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .object-fit-cover {
        object-fit: cover;
    }
    
    @media (max-width: 576px) {
        .vendor-image, .service-image {
            width: 100px !important;
            height: 100px !important;
        }
    }
    
    /* Memastikan layout 1 kolom vertikal untuk mobile */
    @media (max-width: 768px) {
        .container-fluid {
            max-width: 100%;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        
        .card-body {
            padding: 15px;
        }
    }
    
    /* Responsif untuk desktop */
    @media (min-width: 992px) {
        .container-fluid {
            max-width: 960px;
            margin: 0 auto;
        }
    }
</style>
@endpush
@endsection