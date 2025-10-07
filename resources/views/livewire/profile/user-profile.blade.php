{{-- resources/views/livewire/profile/user-profile.blade.php --}}
<div>
    <!-- Profile Header -->
    <div class="card border-0 bg-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <img src="{{ $user ? ($user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=fff&color=92B6B1') : 'https://ui-avatars.com/api/?name=User&background=fff&color=92B6B1' }}" 
                         class="rounded-circle border border-3 border-white" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <div class="ms-3">
                    <h4 class="mb-1">{{ $user->name ?? 'User' }}</h4>
                    <p class="mb-1 opacity-75">
                        <i class="bi bi-envelope me-1"></i>
                        {{ $user->email ?? 'Email tidak tersedia' }}
                    </p>
                    <span class="badge bg-light text-primary">
                        <i class="bi bi-person me-1"></i>Pengguna
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-person me-2 text-primary"></i>
                Informasi Profile
            </h5>
        </div>
        <div class="card-body">
            @if($successMessage)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $successMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form wire:submit="saveProfile">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" wire:model="name">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" wire:model="email">
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" wire:model="phone">
                        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Foto Profile</label>
                        <input type="file" class="form-control" wire:model="profile_picture" accept="image/*">
                        @error('profile_picture') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Become Vendor Section -->
    @if($user && !$user->vendor)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            <i class="bi bi-shop fs-1 text-primary mb-3"></i>
            <h5>Jadi Vendor?</h5>
            <p class="text-muted mb-3">Daftarkan bisnis Anda dan mulai terima pesanan dari customer</p>
            <a href="{{ route('vendor.register') }}" class="btn btn-primary">
                Daftar sebagai Vendor
            </a>
        </div>
    </div>
    @endif
</div>