{{-- resources/views/livewire/profile/general-information.blade.php --}}
<div>
    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>nd
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form wire:submit="saveProfile">
                <!-- Profile Picture -->
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=92B6B1&color=fff' }}" 
                             class="rounded-circle border border-3 border-primary mb-3" 
                             style="width: 80px; height: 80px; object-fit: cover;" 
                             id="profileImagePreview">
                        <label for="profile_picture" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1" style="cursor: pointer;">
                            <i class="bi bi-camera fs-6"></i>
                        </label>
                    </div>
                    <input type="file" id="profile_picture" wire:model="profile_picture" class="d-none" accept="image/*">
                    @error('profile_picture') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Form Fields -->
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" wire:model="name" placeholder="Masukkan nama lengkap">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" wire:model="email" placeholder="Masukkan alamat email">
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="tel" class="form-control" wire:model="phone" placeholder="Masukkan nomor telepon">
                        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2">
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
    // Preview image when selected
    const profilePictureInput = document.getElementById('profile_picture');
    const profileImagePreview = document.getElementById('profileImagePreview');
    
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