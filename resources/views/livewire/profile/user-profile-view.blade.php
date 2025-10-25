{{-- resources/views/livewire/profile/user-profile-view.blade.php --}}
<div>
    <!-- Profile Header -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="position-relative">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=92B6B1&color=fff' }}"
                        class="rounded-circle border border-3 border-primary"
                        style="width: 60px; height: 60px; object-fit: cover;">
                </div>
                <div class="ms-3 flex-grow-1">
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="bx bx-envelope me-1"></i>{{ $user->email }}
                    </p>
                </div>
            </div>

            <!-- Role Badge -->
            <div class="d-flex align-items-center">
                <i class="bx bx-user me-2 text-primary"></i>
                <span class="badge bg-primary">Pengguna</span>
                @if ($isVendor)
                    <span class="badge bg-success ms-2">Vendor</span>
                @endif
            </div>
        </div>
        @if ($user && $user->hasVerifiedEmail())
            <span class="badge bg-success">
                <i class="bx bx-check-circle me-1"></i>Email Terverifikasi
            </span>
        @else
            <span class="badge bg-warning text-dark">
                <i class="bx bx-error me-1"></i>Email Belum Terverifikasi
            </span>
        @endif
    </div>

    <!-- Email Verification Alert -->
    @if ($user && !$user->hasVerifiedEmail())
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bxs-error me-2 fs-5"></i>
                <div class="flex-grow-1">
                    <strong>Email belum terverifikasi!</strong> Verifikasi email Anda untuk mengakses semua fitur.
                </div>
                @if (!$emailVerificationSent)
                    <button type="button" class="btn btn-sm btn-warning" wire:click="sendEmailVerification">
                        Kirim Verifikasi
                    </button>
                @else
                    <span class="badge bg-success">Terkirim!</span>
                @endif
            </div>
        </div>
    @endif

    @if ($emailVerificationSent)
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="bx bx-info-circle me-2"></i>
            Email verifikasi telah dikirim. Silakan cek inbox email Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($successMessage)
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            {{ $successMessage }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Role Toggle Switch (Hanya jika user sudah vendor) -->
    @if ($isVendor)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-2">
                <div class="bg-light rounded-3 p-1">
                    <div class="d-flex">
                        <button type="button"
                            class="btn flex-fill {{ session('current_profile_role', 'user') === 'user' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                            onclick="switchRole('user')">
                            <i class="bx bx-user me-1"></i>
                            <span class="small">Pengguna</span>
                        </button>
                        <button type="button"
                            class="btn flex-fill {{ session('current_profile_role') === 'vendor' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                            onclick="switchRole('vendor')">
                            <i class="bx bx-store me-1"></i>
                            <span class="small">Vendor</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Menu Navigasi User (TETAP LENGKAP) -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <!-- General Information -->
            <a href="{{ route('profile.general') }}"
                class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-user text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Informasi Umum</h6>
                        <small class="text-muted">Nama, email, telepon</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>

            <!-- Security -->
            <a href="{{ route('profile.security') }}"
                class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-shield text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Keamanan</h6>
                        <small class="text-muted">Password, privasi</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>

            <!-- Favorites -->
            <a href="{{ route('profile.favorites') }}"
                class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-bookmark text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Favorit</h6>
                        <small class="text-muted">Vendor & layanan tersimpan</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>

            <!-- Help Center -->
            <a href="{{ route('profile.help') }}"
                class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-help-circle text-info"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Pusat Bantuan</h6>
                        <small class="text-muted">Bantuan & dukungan</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>

            <!-- Share Feedback -->
            <a href="{{ route('profile.feedback') }}"
                class="d-flex justify-content-between align-items-center p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-chat text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Berikan Masukan</h6>
                        <small class="text-muted">Bagikan pengalaman Anda</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Become Vendor Section -->
    @if ($user && !$user->vendor)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <i class="bx bx-store fs-1 text-primary mb-3"></i>
                <h5>Jadi Vendor?</h5>
                <p class="text-muted mb-3">Daftarkan bisnis Anda dan mulai terima pesanan dari customer</p>
                <a href="{{ route('vendor.register') }}" class="btn btn-primary">
                    Daftar sebagai Vendor
                </a>
            </div>
        </div>
    @endif


    <!-- Logout Button -->
    <div class="text-center mt-4 mb-5">
        <button wire:click="logout" wire:confirm="Yakin ingin keluar dari akun ini?"
            class="btn btn-light text-danger-emphasis px-4 py-2 rounded-3 shadow-sm border-0 logout-btn">
            <i class="bx bx-log-out me-1"></i> Keluar
        </button>
    </div>
</div>

@push('styles')
    <style>
        .logout-btn {
            background-color: #ffffff;
            /* putih lembut */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }

        .logout-btn:hover {
            background-color: #ffe6e6;
            /* merah muda lembut */
            color: #ff4d4d !important;
            box-shadow: 0 3px 8px rgba(255, 77, 77, 0.2);
        }

        .logout-btn:active {
            background-color: #ffd6d6;
            /* sedikit lebih gelap saat diklik */
            transform: scale(0.98);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function switchRole(role) {
            fetch('{{ route('profile.switch-role') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        role: role
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endpush
