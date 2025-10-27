{{-- resources/views/livewire/components/service-form.blade.php --}}
<div>
    <div class="mb-4">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" 
                   wire:model="hasServices" id="has_services_{{ $componentId }}">
            <label class="form-check-label fw-semibold" for="has_services_{{ $componentId }}">
                <i class="bx bx-list-check me-2"></i>Tambah Layanan/Jasa
            </label>
        </div>
        <small class="text-muted d-block ms-4">Centang untuk menambahkan layanan atau jasa yang ditawarkan</small>

        @if($hasServices)
            <div class="services-container border rounded p-4 bg-white mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="bx bx-cube me-2"></i>Daftar Layanan
                    </h6>
                    <button type="button" class="btn btn-primary btn-sm" wire:click="addService">
                        <i class="bx bx-plus me-1"></i>Tambah Layanan
                    </button>
                </div>

                @foreach($services as $index => $service)
                    <div class="service-card border rounded p-3 mb-3 bg-light">
                        <div class="row g-3">
                            <!-- Service Image -->
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-semibold">Foto Layanan</label>
                                <div class="service-image-upload">
                                    <label for="service_image_{{ $index }}_{{ $componentId }}" 
                                           class="service-image-preview d-block border rounded cursor-pointer">
                                        @if($service['image'])
                                            <img src="{{ $service['image']->temporaryUrl() }}" 
                                                 class="w-100 h-100 object-fit-cover rounded">
                                        @else
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                                <i class="bx bx-camera fs-4 mb-2"></i>
                                                <small>Upload Foto</small>
                                            </div>
                                        @endif
                                    </label>
                                    <input type="file" id="service_image_{{ $index }}_{{ $componentId }}" 
                                           wire:model="services.{{ $index }}.image" 
                                           accept="image/*" class="d-none">
                                </div>
                            </div>

                            <!-- Service Details -->
                            <div class="col-12 col-md-7">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Nama Layanan *</label>
                                        <input type="text" class="form-control" 
                                               wire:model="services.{{ $index }}.name"
                                               placeholder="Contoh: Service AC, Cuci Motor, dll.">
                                        @error("services.{$index}.name")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Harga (Rp) *</label>
                                        <input type="number" class="form-control" 
                                               wire:model="services.{{ $index }}.price"
                                               placeholder="0" min="0">
                                        @error("services.{$index}.price")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Deskripsi</label>
                                        <textarea class="form-control" rows="2"
                                                  wire:model="services.{{ $index }}.description"
                                                  placeholder="Deskripsi singkat layanan..."></textarea>
                                        @error("services.{$index}.description")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Remove Button -->
                            <div class="col-12 col-md-2 d-flex align-items-start justify-content-end">
                                @if(count($services) > 1)
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            wire:click="removeService({{ $index }})">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @error('services')
                    <div class="alert alert-danger mt-2">
                        <small>{{ $message }}</small>
                    </div>
                @enderror

                <div class="alert alert-info mt-3">
                    <small>
                        <i class="bx bx-info-circle"></i> 
                        Isi nama dan harga untuk setiap layanan. Foto bersifat opsional.
                        Hapus layanan yang tidak perlu dengan tombol sampah.
                    </small>
                </div>
            </div>
        @endif
    </div>
</div>

@section('styles')
<style>
    .service-card {
        transition: all 0.3s ease;
        border-left: 4px solid #28a745 !important;
    }
    
    .service-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }
    
    .service-image-preview {
        height: 120px;
        background: #f8f9fa;
        overflow: hidden;
    }
    
    .service-image-preview:hover {
        background: #e9ecef;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
@endsection
