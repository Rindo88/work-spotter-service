@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">{{ $title }}</h4>
            <p class="text-muted mb-0 small">{{ $description }}</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="d-flex overflow-auto py-2 px-2 gap-2">
                <a href="{{ route('quick-access.index', ['type' => 'all']) }}" 
                   class="btn {{ $type == 'all' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3">
                    Semua
                </a>
                <a href="{{ route('quick-access.index', ['type' => 'informal']) }}" 
                   class="btn {{ $type == 'informal' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3">
                    Pedagang Informal
                </a>
                <a href="{{ route('quick-access.index', ['type' => 'top-rated']) }}" 
                   class="btn {{ $type == 'top-rated' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3">
                    Rating Tertinggi
                </a>
                <a href="{{ route('quick-access.index', ['type' => 'nearby']) }}" 
                   class="btn {{ $type == 'nearby' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3">
                    Terdekat
                </a>
                <a href="{{ route('quick-access.index', ['type' => 'promo']) }}" 
                   class="btn {{ $type == 'promo' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm rounded-pill px-3">
                    Promo
                </a>
            </div>
        </div>
    </div>

    <!-- Vendor List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                @forelse($vendors as $vendor)
                    <div class="col-12">
                        <a href="{{ route('vendor.show', $vendor) }}" class="text-decoration-none text-dark">
                            <div class="card border-0 shadow-sm vendor-card">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <img src="{{ $vendor->photo_url ?? asset('assets/images/vendor-placeholder.jpg') }}" 
                                             class="img-fluid rounded-start h-100 w-100 object-fit-cover" 
                                             style="max-height: 120px; object-fit: cover;"
                                             alt="{{ $vendor->business_name }}">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-1 text-truncate">{{ $vendor->business_name }}</h6>
                                            <p class="text-muted small mb-1 text-truncate">{{ $vendor->category->name ?? '-' }}</p>
                                            <p class="small mb-2 text-truncate">{{ Str::limit($vendor->description, 60) }}</p>
                                            <div class="d-flex justify-content-between align-items-center small">
                                                <span class="badge bg-success">
                                                    <i class="bi bi-star-fill me-1"></i>{{ number_format($vendor->rating, 1) }}
                                                </span>
                                                <span class="text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $vendor->distance ?? '—' }} km
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-shop display-1 text-muted mb-3"></i>
                        <h6 class="text-muted">Tidak ada vendor ditemukan</h6>
                        <p class="text-muted small mb-0">
                            Coba pilih kategori lain atau ubah filter pencarian
                        </p>
                    </div>
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
.vendor-card {
    transition: all .3s ease;
    border-radius: 12px;
    overflow: hidden;
}
.vendor-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}
.object-fit-cover {
    object-fit: cover;
}
</style>
@endpush