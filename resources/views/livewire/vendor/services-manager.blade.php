{{-- resources/views/livewire/vendor/services-manager.blade.php --}}
<div>
    <!-- Alert akan ditampilkan via Livewire dispatch -->
    <div x-data="{ showAlert: false, alertType: '', alertMessage: '' }" x-show="showAlert" x-transition
        x-on:show-alert.window="showAlert = true; alertType = $event.detail.type; alertMessage = $event.detail.message; setTimeout(() => showAlert = false, 3000)"
        class="position-fixed top-0 start-50 translate-middle-x mt-3 z-1050" style="display: none;">
        <div x-bind:class="{
            'alert-success': alertType === 'success',
            'alert-danger': alertType === 'error',
            'alert-warning': alertType === 'warning'
        }"
            class="alert alert-dismissible fade show">
            <span x-text="alertMessage"></span>
            <button type="button" class="btn-close" x-on:click="showAlert = false"></button>
        </div>
    </div>

    <!-- Header dengan Tombol Tambah -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-semibold mb-1">Kelola Layanan</h6>
                    <p class="text-muted small mb-0">Tambah dan kelola layanan yang Anda tawarkan</p>
                </div>
                <button type="button" class="btn btn-primary" wire:click="openServiceModal">
                    <i class="bi bi-plus me-2"></i>Tambah Layanan
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Layanan -->
    @if (count($services) > 0)
        <div class="row g-3">
            @foreach ($services as $service)
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                @if ($service->image_url)
                                    <img src="{{ asset('storage/' . $service->image_url) }}" class="rounded me-3"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                        style="width: 80px; height: 80px;">
                                        <i class="bi bi-image text-muted fs-4"></i>
                                    </div>
                                @endif

                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">{{ $service->name }}</h6>
                                    <p class="text-success fw-bold mb-1">Rp
                                        {{ number_format($service->price, 0, ',', '.') }}</p>
                                    <p class="text-muted small mb-2">{{ Str::limit($service->description, 100) }}</p>

                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary"
                                            wire:click="openServiceModal({{ $service->id }})">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-outline-danger"
                                            wire:click="deleteService({{ $service->id }})"
                                            wire:confirm="Hapus layanan ini?">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-list-task display-1 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada layanan</h6>
                <p class="text-muted small mb-3">Mulai dengan menambahkan layanan pertama Anda</p>
                <button type="button" class="btn btn-primary" wire:click="openServiceModal">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tambah Layanan Pertama
                </button>
            </div>
        </div>
    @endif

    <!-- Service Modal -->
    @if ($showServiceModal)
        <!-- Backdrop -->
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>

        <!-- Modal -->
        <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog" style="z-index: 1045;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingServiceId ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeServiceModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="saveService">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Nama Layanan *</label>
                                    <input type="text" class="form-control" wire:model="name">
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Harga (Rp) *</label>
                                    <input type="number" class="form-control" wire:model="price">
                                    @error('price')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Deskripsi</label>
                                    <textarea class="form-control" rows="3" wire:model="description"></textarea>
                                    @error('description')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Gambar Layanan</label>
                                    <input type="file" class="form-control" wire:model="image_url" accept="image/*">
                                    @error('image_url')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror

                                    @if ($image_url)
                                        <div class="mt-2">
                                            <img src="{{ $image_url->temporaryUrl() }}" class="img-thumbnail"
                                                style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-100 py-2">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ $editingServiceId ? 'Update Layanan' : 'Simpan Layanan' }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary w-100 mt-2"
                                    wire:click="closeServiceModal">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal {
            background: transparent;
        }
    </style>
@endpush
