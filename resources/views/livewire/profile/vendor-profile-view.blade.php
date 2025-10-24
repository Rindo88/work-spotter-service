{{-- resources/views/livewire/profile/vendor-profile-view.blade.php --}}
<div>
    <!-- Vendor Profile Header -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="position-relative">
                    <img src="{{ $vendor->profile_picture ? asset('storage/' . $vendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->business_name) . '&background=28a745&color=fff' }}"
                        class="rounded-circle border border-3 border-success"
                        style="width: 60px; height: 60px; object-fit: cover;">
                </div>
                <div class="ms-3 flex-grow-1">
                    <h5 class="fw-bold mb-1">{{ $vendor->business_name }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    </p>
                </div>
            </div>

            <!-- Role Badge & Toggle -->
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-shop me-2 text-success"></i>
                    <span class="badge bg-success">Vendor</span>
                    <span class="badge bg-primary ms-2">{{ $vendor->type === 'formal' ? 'Formal' : 'Informal' }}</span>
                    @if ($hasRfid)
                        <span class="badge bg-info ms-2">RFID Active</span>
                    @endif
                </div>
            </div>
        </div>
        <!-- Role Toggle Switch (Hanya jika user sudah vendor) -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-2">
                <div class="bg-light rounded-3 p-1">
                    <div class="d-flex">
                        <button type="button"
                            class="btn flex-fill {{ session('current_profile_role', 'user') === 'user' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                            onclick="switchRole('user')">
                            <i class="bi bi-person me-1"></i>
                            <span class="small">Pengguna</span>
                        </button>
                        <button type="button"
                            class="btn flex-fill {{ session('current_profile_role') === 'vendor' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                            onclick="switchRole('vendor')">
                            <i class="bi bi-shop me-1"></i>
                            <span class="small">Vendor</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Menu Navigasi -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <!-- Vendor Profile -->
            <a href="{{ route('vendor.profile') }}"
                class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-building text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Profil Vendor</h6>
                        <small class="text-muted">Informasi bisnis & profil</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <!-- Services -->
            <a href="{{ route('vendor.services') }}"
                class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-list-task text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Layanan</h6>
                        <small class="text-muted">Kelola layanan & harga</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <!-- Schedule -->
            <a href="{{ route('vendor.schedule') }}"
                class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-clock text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Jadwal</h6>
                        <small class="text-muted">Atur jam operasional</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <!-- RFID -->
            <a href="{{ route('vendor.rfid') }}"
                class="d-flex justify-content-between align-items-center p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-credit-card text-info"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Kartu RFID</h6>
                        <small class="text-muted">Kelola kartu pembayaran</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-3 text-success">
                <i class="bi bi-lightning me-2"></i>Akses Cepat Vendor
            </h6>
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-success w-100 py-2 text-start">
                        <i class="bi bi-speedometer2 me-2"></i>
                        <small>Dashboard</small>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('vendor.profile') }}" class="btn btn-outline-success w-100 py-2 text-start">
                        <i class="bi bi-pencil me-2"></i>
                        <small>Edit Profil</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
