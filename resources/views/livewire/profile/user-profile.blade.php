{{-- resources/views/livewire/profile/user-profile.blade.php --}}
<div>
    <!-- Profile Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <img src="{{ $user ? ($user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=667eea&color=fff') : 'https://ui-avatars.com/api/?name=User&background=667eea&color=fff' }}" 
                         class="rounded-circle border border-3 border-primary shadow" 
                         style="width: 80px; height: 80px; object-fit: cover;">
                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                          style="width: 15px; height: 15px;"></span>
                </div>
                <div class="ms-3">
                    <h4 class="mb-1 fw-bold">{{ $user->name ?? 'User' }}</h4>
                    <p class="mb-1 text-muted">
                        <i class="bi bi-envelope me-1"></i>
                        {{ $user->email ?? 'Email tidak tersedia' }}
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">
                            <i class="bi bi-person me-1"></i>Pengguna
                        </span>
                        @if($user && $user->hasVerifiedEmail())
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Email Terverifikasi
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-exclamation-triangle me-1"></i>Email Belum Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Verification Alert -->
    @if($user && !$user->hasVerifiedEmail())
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">
                <strong>Email belum terverifikasi!</strong> Verifikasi email Anda untuk mengakses semua fitur.
            </div>
            @if(!$emailVerificationSent)
            <button type="button" class="btn btn-sm btn-warning" wire:click="sendEmailVerification">
                Kirim Verifikasi
            </button>
            @else
            <span class="badge bg-success">Terkirim!</span>
            @endif
        </div>
    </div>
    @endif

    @if($emailVerificationSent)
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        Email verifikasi telah dikirim. Silakan cek inbox email Anda.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Profile Information Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-person-badge me-2 text-primary"></i>
                Informasi Profile
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit="saveProfile">
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
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Foto Profile</label>
                        <input type="file" class="form-control" wire:model="profile_picture" accept="image/*">
                        <div class="form-text">Ukuran maksimal 1MB. Format: JPG, PNG, GIF</div>
                        @error('profile_picture') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>