{{-- resources/views/profile/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya - Work Spotter')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Profile Header dengan Toggle Switch -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="position-relative">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=92B6B1&color=fff' }}" 
                         class="rounded-circle border border-3 border-primary" 
                         style="width: 60px; height: 60px; object-fit: cover;">
                </div>
                <div class="ms-3 flex-grow-1">
                    <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-envelope me-1"></i>{{ Auth::user()->email }}
                    </p>
                </div>
            </div>

            <!-- Role Toggle Switch -->
            @if(Auth::user()->vendor)
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
            @endif
        </div>
    </div>

    <!-- Menu Navigasi Profile -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <!-- General Information -->
            <a href="{{ route('profile.general') }}" class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-person text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Informasi Umum</h6>
                        <small class="text-muted">Nama, email, telepon</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <!-- Security -->
            <a href="{{ route('profile.security') }}" class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-shield-lock text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Keamanan</h6>
                        <small class="text-muted">Password, privasi</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <!-- Favorites -->
            <a href="{{ route('profile.favorites') }}" class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-bookmark text-danger"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Favorit</h6>
                        <small class="text-muted">Vendor & layanan tersimpan</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Support Section -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <!-- Help Center -->
            <a href="{{ route('profile.help') }}" class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-question-circle text-info"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Pusat Bantuan</h6>
                        <small class="text-muted">Bantuan & dukungan</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <!-- Share Feedback -->
            <a href="{{ route('profile.feedback') }}" class="d-flex justify-content-between align-items-center p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-chat-left-text text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Berikan Masukan</h6>
                        <small class="text-muted">Bagikan pengalaman Anda</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Vendor Quick Access (Hanya tampil jika role vendor aktif) -->
    @if(session('current_profile_role') === 'vendor' && Auth::user()->vendor)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3 text-success">
                <i class="bi bi-shop me-2"></i>Akses Cepat Vendor
            </h6>
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-success w-100 py-2">
                        <i class="bi bi-speedometer2 me-1"></i>
                        <small>Dashboard</small>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('vendor.profile') }}" class="btn btn-outline-success w-100 py-2">
                        <i class="bi bi-pencil me-1"></i>
                        <small>Edit Profil</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function switchRole(role) {
    fetch('{{ route("profile.switch-role") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ role: role })
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
@endsection