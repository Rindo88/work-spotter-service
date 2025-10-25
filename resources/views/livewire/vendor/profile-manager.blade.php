{{-- resources/views/livewire/vendor/profile-manager.blade.php --}}
<div>
    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @error('general')
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @enderror

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form wire:submit="saveProfile">
                <!-- Profile Picture -->
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <img src="{{ Auth::user()->vendor->profile_picture ? asset('storage/' . Auth::user()->vendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->vendor->business_name) . '&background=28a745&color=fff' }}" 
                             class="rounded-circle border border-3 border-success mb-3" 
                             style="width: 80px; height: 80px; object-fit: cover;" 
                             id="vendorProfileImagePreview">
                        <label for="vendor_profile_picture" class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-1" style="cursor: pointer;">
                            <i class="bi bi-camera fs-6"></i>
                        </label>
                    </div>
                    <input type="file" id="vendor_profile_picture" wire:model="profile_picture" class="d-none" accept="image/*">
                    @error('profile_picture') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Form Fields -->
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Bisnis *</label>
                        <input type="text" class="form-control" wire:model="business_name" placeholder="Masukkan nama bisnis">
                        @error('business_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Kategori *</label>
                        <select class="form-select" wire:model="category_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Bisnis *</label>
                        <textarea class="form-control" rows="3" wire:model="description" placeholder="Deskripsikan bisnis Anda..."></textarea>
                        @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Tipe Vendor</label>
                        <select class="form-select" wire:model="type">
                            <option value="informal">Informal</option>
                            <option value="formal">Formal</option>
                        </select>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Status RFID</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" wire:model="is_rfid" 
                                   {{ Auth::user()->vendor->hasActiveRfid() ? '' : 'disabled' }}>
                            <label class="form-check-label">
                                {{ Auth::user()->vendor->hasActiveRfid() ? 'Aktifkan RFID' : 'Belum ada kartu RFID aktif' }}
                            </label>
                        </div>
                        @if(!Auth::user()->vendor->hasActiveRfid())
                        <small class="text-muted">Ajukan kartu RFID terlebih dahulu</small>
                        @endif
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Catatan Operasional</label>
                        <textarea class="form-control" rows="2" wire:model="operational_notes" placeholder="Jam operasional, hari libur, dll..."></textarea>
                        @error('operational_notes') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', function() {
    const profilePictureInput = document.getElementById('vendor_profile_picture');
    const profileImagePreview = document.getElementById('vendorProfileImagePreview');
    
    profilePictureInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImagePreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>