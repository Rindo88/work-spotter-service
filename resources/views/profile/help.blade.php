{{-- resources/views/profile/help.blade.php --}}
@extends('layouts.app')

@section('title', 'Pusat Bantuan - Work Spotter')

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('profile') }}" class="text-decoration-none text-dark me-3">
            <i class="bx bx-left-arrow-alt fs-5"></i>
        </a>
        <h5 class="fw-bold mb-0">Pusat Bantuan</h5>
    </div>

    <!-- Help Categories -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <a href="#" class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-help-circle text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Panduan Pengguna</h6>
                        <small class="text-muted">Cara menggunakan aplikasi</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-credit-card text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Pembayaran</h6>
                        <small class="text-muted">Metode pembayaran & transaksi</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex justify-content-between align-items-center p-3 border-bottom text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-shield-check text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Keamanan Akun</h6>
                        <small class="text-muted">Proteksi data & privasi</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex justify-content-between align-items-center p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bx bx-phone text-info"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Hubungi Kami</h6>
                        <small class="text-muted">Layanan pelanggan 24/7</small>
                    </div>
                </div>
                <i class="bx bx-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
            <i class="bx bx-headphone display-4 text-primary mb-3"></i>
            <h5 class="fw-semibold">Butuh Bantuan?</h5>
            <p class="text-muted small mb-3">Tim support kami siap membantu Anda</p>
            <a href="tel:+628123456789" class="btn btn-primary me-2">
                <i class="bx bx-phone me-2"></i>Telepon
            </a>
            <a href="mailto:support@workspotter.com" class="btn btn-outline-primary">
                <i class="bx bx-envelope me-2"></i>Email
            </a>
        </div>
    </div>
</div>
@endsection