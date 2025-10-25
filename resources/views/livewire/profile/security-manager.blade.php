{{-- resources/views/livewire/profile/security-manager.blade.php --}}
<div>
    <!-- Security Alerts -->
    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errorMessage)
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bx bx-error me-2"></i>
        {{ $errorMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Change Password -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bx bx-key me-2 text-primary"></i>
                Ubah Password
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit="updatePassword">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Password Saat Ini</label>
                        <input type="password" class="form-control" wire:model="current_password" 
                               placeholder="Masukkan password saat ini">
                        @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" class="form-control" wire:model="new_password" 
                               placeholder="Masukkan password baru">
                        @error('new_password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" wire:model="new_password_confirmation" 
                               placeholder="Konfirmasi password baru">
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-key me-2"></i>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Session Management -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bx bx-laptop me-2 text-warning"></i>
                Manajemen Sesi
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                Keluar dari semua sesi perangkat lain yang sedang aktif.
            </p>
            <form wire:submit="logoutOtherDevices">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" class="form-control" wire:model="current_password" 
                               placeholder="Masukkan password Anda">
                        @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bx bx-mobile me-2"></i>
                        Keluar dari Perangkat Lain
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bx bx-log-out me-2 text-info"></i>
                Tindakan Cepat
            </h5>
        </div>
        <div class="card-body">
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <button type="button" class="btn btn-outline-primary" 
                        wire:click="logout" 
                        wire:confirm="Apakah Anda yakin ingin keluar?">
                    <i class="bx bx-log-out me-2"></i>
                    Keluar Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card border-0 shadow-sm border-danger">
        <div class="card-header bg-white border-danger py-3">
            <h5 class="mb-0 text-danger fw-bold">
                <i class="bx bx-error me-2"></i>
                Zona Berbahaya
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <h6 class="alert-heading fw-bold">Hapus Akun Permanent</h6>
                <p class="mb-2">
                    Tindakan ini akan menghapus akun Anda secara permanen. Semua data termasuk profil, 
                    riwayat, dan informasi lainnya akan dihapus dan tidak dapat dikembalikan.
                </p>
                <hr>
                <form wire:submit="deleteAccount" id="deleteAccountForm">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" class="form-control" wire:model="current_password" 
                                   placeholder="Masukkan password untuk konfirmasi">
                            @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-danger px-4" 
                                onclick="confirmDelete()">
                            <i class="bx bx-trash me-2"></i>
                            Hapus Akun Saya Permanent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('⚠️ PERINGATAN: Tindakan ini tidak dapat dibatalkan!\n\nSemua data Anda akan dihapus permanent.\n\nApakah Anda yakin ingin menghapus akun?')) {
        // Show additional confirmation
        if (confirm('❗️ KONFIRMASI AKHIR:\n\nIni adalah peringatan terakhir. Akun dan semua data akan dihapus SELAMANYA.\n\nLanjutkan?')) {
            document.getElementById('deleteAccountForm').dispatchEvent(new Event('submit', {bubbles: true}));
        }
    }
}

// Listen for Livewire events
document.addEventListener('livewire:initialized', () => {
    Livewire.on('password-updated', () => {
        // Reset form fields
        Livewire.components.components().forEach(component => {
            if (component.name === 'profile.security-manager') {
                component.set('current_password', '');
                component.set('new_password', '');
                component.set('new_password_confirmation', '');
            }
        });
    });
});
</script>