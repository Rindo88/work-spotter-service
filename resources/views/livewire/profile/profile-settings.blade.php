{{-- resources/views/livewire/profile/profile-settings.blade.php --}}
<div>
    <!-- Security Settings -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-shield-lock me-2 text-primary"></i>
                Keamanan Akun
            </h5>
        </div>
        <div class="card-body">
            @if($successMessage)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $successMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errorMessage)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errorMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Change Password -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Ubah Password</h6>
                <form wire:submit="updatePassword">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" wire:model="current_password">
                            @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" wire:model="new_password">
                            @error('new_password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" wire:model="new_password_confirmation">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-key me-2"></i>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

            <hr>

            <!-- Logout Other Devices -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Keluar dari Perangkat Lain</h6>
                <p class="text-muted small mb-3">
                    Keluar dari semua sesi perangkat lain yang sedang aktif.
                </p>
                <form wire:submit="logoutOtherDevices">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" wire:model="current_password">
                            @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-phone me-2"></i>
                            Keluar dari Perangkat Lain
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 text-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Tindakan Akun
            </h5>
        </div>
        <div class="card-body">
            <!-- Logout -->
            <div class="d-grid mb-3">
                <button type="button" class="btn btn-outline-primary" 
                        wire:click="logout" wire:confirm="Apakah Anda yakin ingin keluar?">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Keluar
                </button>
            </div>

            <hr>

            <!-- Delete Account -->
            <div>
                <h6 class="fw-bold text-danger mb-3">Hapus Akun</h6>
                <p class="text-muted small mb-3">
                    Tindakan ini akan menghapus akun Anda secara permanen. Semua data akan dihapus dan tidak dapat dikembalikan.
                </p>
                <form wire:submit="deleteAccount" id="deleteAccountForm">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" wire:model="current_password" 
                                   placeholder="Masukkan password untuk konfirmasi">
                            @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDelete()">
                            <i class="bi bi-trash me-2"></i>
                            Hapus Akun Saya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')) {
        document.getElementById('deleteAccountForm').dispatchEvent(new Event('submit', {bubbles: true}));
    }
}
</script>