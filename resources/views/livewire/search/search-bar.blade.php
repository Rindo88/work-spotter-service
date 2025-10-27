<div class="w-100 position-relative">
    <!-- Search bar -->
    <div class="d-flex align-items-center position-relative">
        <input 
            wire:model.live.debounce.300ms="query" 
            wire:keydown.enter="search"
            type="text" 
            placeholder="Cari vendor atau layanan..." 
            class="form-control rounded-pill py-2 ps-4 pe-5 shadow-sm border-0"
            style="width: 100%;"
        >
        <button 
            wire:click="search"
            class="btn position-absolute end-0 me-2 text-primary bg-transparent border-0"
            style="top: 50%; transform: translateY(-50%);"
        >
            <i class="bx bx-search fs-5"></i>
        </button>
    </div>

    <!-- Suggestions dropdown -->
    @if($showSuggestions && (count($vendors) > 0 || count($services) > 0))
    <div class="position-absolute w-100 mt-1 bg-white rounded shadow border" style="z-index: 1050;">
        
        {{-- Vendor Section --}}
        @if(count($vendors) > 0)
        <div class="p-2">
            <h3 class="small fw-semibold text-secondary mb-1">Vendor</h3>
            <ul class="list-unstyled mb-0">
                @foreach($vendors as $vendor)
                <li>
                    <a href="{{ route('vendor.show', $vendor->id) }}" 
                       class="d-block px-3 py-2 text-decoration-none text-dark rounded hover-bg-light">
                        <div class="d-flex align-items-center">
                            <img src="{{ $vendor->profile_picture ?: asset('images/logo-workspotter.png') }}" 
                                 alt="{{ $vendor->business_name }}" 
                                 class="rounded-circle me-2" 
                                 style="width: 32px; height: 32px; object-fit: cover;">
                            <div>
                                <p class="mb-0 small fw-semibold">{{ $vendor->business_name }}</p>
                                <p class="mb-0 text-muted small">{{ $vendor->category->name }}</p>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Services Section --}}
        @if(count($services) > 0)
        <div class="p-2 @if(count($vendors) > 0) border-top @endif">
            <h3 class="small fw-semibold text-secondary mb-1">Layanan</h3>
            <ul class="list-unstyled mb-0">
                @foreach($services as $service)
                <li>
                    <a href="{{ route('vendor.show', $service->vendor_id) }}" 
                       class="d-block px-3 py-2 text-decoration-none text-dark rounded hover-bg-light">
                        <div class="d-flex align-items-center">
                            @if($service->image_url)
                            <img src="{{ $service->image_url ?: asset('images/logo-workspotter.png') }}" 
                                 alt="{{ $service->name }}" 
                                 class="rounded me-2" 
                                 style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                            <div class="rounded bg-light me-2 d-flex align-items-center justify-content-center" 
                                 style="width: 32px; height: 32px;">
                                <span class="text-muted small">S</span>
                            </div>
                            @endif
                            <div>
                                <p class="mb-0 small fw-semibold">{{ $service->name }}</p>
                                <p class="mb-0 text-muted small">{{ $service->vendor->business_name }}</p>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif
</div>

@push('styles')
<style>
/* efek hover pada hasil pencarian */
.hover-bg-light:hover {
    background-color: #f8f9fa;
}
</style>
@endpush
