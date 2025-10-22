@extends('layouts.app')

@section('title', $category->name . ' - Work Spotter')
@section('header-title', $category->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Category Header -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h4 class="fw-bold mb-2">{{ $category->name }}</h4>
            <p class="text-muted">{{ $category->description }}</p>
        </div>
    </div>

        <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Filter</h5>
            <form action="{{ route('category.show', $category) }}" method="GET">
                <div class="row g-2">
                    <!-- Rating Filter -->
                    <div class="col-6 col-md-3">
                        <label class="form-label fw-medium small">Rating</label>
                        <select name="rating" class="form-select form-select-sm">
                            <option value="">Semua Rating</option>
                            <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4+ Bintang</option>
                            <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3+ Bintang</option>
                            <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>2+ Bintang</option>
                        </select>
                    </div>

                    <!-- Vendor Type Filter -->
                    <div class="col-6 col-md-3">
                        <label class="form-label fw-medium small">Tipe Vendor</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="" {{ !request('type') ? 'selected' : '' }}>Semua</option>
                            <option value="formal" {{ request('type') == 'formal' ? 'selected' : '' }}>Formal</option>
                            <option value="informal" {{ request('type') == 'informal' ? 'selected' : '' }}>Informal</option>
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div class="col-6 col-md-3">
                        <label class="form-label fw-medium small">Lokasi</label>
                        <button type="button" id="nearestLocationBtn" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-geo-alt me-1"></i> Gunakan Lokasi Saya
                        </button>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                    <div class="col-6 col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Vendors List -->
    <div class="row g-3">
        @forelse($vendors as $vendor)
        <div class="col-12">
            <div class="card h-100 border-0 shadow-sm">
                <div class="row g-0">
                    <div class="col-4">
                        <div class="position-relative h-100">
                            @if($vendor->profile_picture)
                            <img src="{{ $vendor->profile_picture }}" class="img-fluid h-100 w-100 object-fit-cover rounded-start" alt="{{ $vendor->business_name }}">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-start">
                                <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                            </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge {{ $vendor->type == 'formal' ? 'bg-primary' : 'bg-success' }}">
                                    {{ $vendor->type == 'formal' ? 'Formal' : 'Informal' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card-body h-100 d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1">{{ $vendor->business_name }}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning me-1">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="fw-medium">{{ number_format($vendor->rating_avg, 1) }}</span>
                                <span class="text-muted ms-1">({{ $vendor->review_count ?? 0 }} ulasan)</span>
                            </div>
                            <p class="card-text text-muted small mb-2">{{ Str::limit($vendor->description, 80) }}</p>
                            <a href="{{ route('vendor.show', $vendor) }}" class="btn btn-sm btn-outline-primary mt-auto">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                Tidak ada vendor yang ditemukan dengan filter yang dipilih.
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $vendors->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('nearestLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                
                // Auto submit form when location is obtained
                document.querySelector('form').submit();
            }, function(error) {
                alert('Tidak dapat mengakses lokasi Anda: ' + error.message);
            });
        } else {
            alert('Geolocation tidak didukung oleh browser Anda');
        }
    });
</script>
@endpush
@endsection