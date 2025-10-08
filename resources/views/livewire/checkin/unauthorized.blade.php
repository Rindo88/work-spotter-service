{{-- resources/views/livewire/checkin/unauthorized.blade.php --}}
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-shield-exclamation fs-1 text-danger mb-3"></i>
            <h4 class="text-danger mb-3">Akses Ditolak</h4>
            <p class="text-muted mb-4">
                Halaman ini hanya dapat diakses oleh pedagang terdaftar.
                <br>
                Jika Anda pedagang, pastikan akun Anda sudah terverifikasi.
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>