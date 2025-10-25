<div class="w-100">
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

    @if($showSuggestions && (count($vendors) > 0 || count($services) > 0))
    <div class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow-lg border">
        @if(count($vendors) > 0)
        <div class="p-2">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Vendor</h3>
            <ul>
                @foreach($vendors as $vendor)
                <li>
                    <a href="{{ route('vendor.show', $vendor->id) }}" class="block px-3 py-2 hover:bg-gray-100 rounded">
                        <div class="flex items-center">
                            @if($vendor->profile_picture)
                            <img src="{{ $vendor->profile_picture }}" alt="{{ $vendor->business_name }}" class="w-8 h-8 rounded-full mr-2">
                            @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 mr-2 flex items-center justify-center">
                                <span class="text-gray-500 text-xs">{{ substr($vendor->business_name, 0, 1) }}</span>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium">{{ $vendor->business_name }}</p>
                                <p class="text-xs text-gray-500">{{ $vendor->category->name }}</p>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(count($services) > 0)
        <div class="p-2 {{ count($vendors) > 0 ? 'border-t' : '' }}">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Layanan</h3>
            <ul>
                @foreach($services as $service)
                <li>
                    <a href="{{ route('vendor.show', $service->vendor_id) }}" class="block px-3 py-2 hover:bg-gray-100 rounded">
                        <div class="flex items-center">
                            @if($service->image_url)
                            <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-8 h-8 rounded mr-2 object-cover">
                            @else
                            <div class="w-8 h-8 rounded bg-gray-200 mr-2 flex items-center justify-center">
                                <span class="text-gray-500 text-xs">S</span>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium">{{ $service->name }}</p>
                                <p class="text-xs text-gray-500">{{ $service->vendor->business_name }}</p>
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