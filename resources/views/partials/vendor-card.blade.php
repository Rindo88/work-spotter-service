<div class="col-6 col-md-4 col-lg-3 col-xl-2">
    <a href="{{ route('vendor.show', $vendor) }}" class="text-decoration-none text-dark">
        <div class="vendor-grid-card h-100">
            <img src="{{ $vendor->image_url ?? 'https://placehold.co/300x200' }}"
                class="w-100 rounded-3 mb-2" style="height: 140px; object-fit: cover;">
            <h6 class="fw-bold mb-1 text-truncate">{{ $vendor->business_name }}</h6>
            <p class="text-muted small mb-1 text-truncate">{{ $vendor->category->name ?? '-' }}</p>
            <div class="d-flex justify-content-between align-items-center small">
                <span class="badge bg-success">
                    <i class="bx bxs-star me-1"></i>{{ number_format($vendor->rating, 1) }}
                </span>
                <span class="text-muted">
                    <i class="bx bx-map-pin me-1"></i>{{ $vendor->distance ?? '—' }} km
                </span>
            </div>
        </div>
    </a>
</div>