{{-- resources/views/vendor/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Vendor - Work Spotter')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-success">Dashboard Vendor</h2>
                    <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('vendor.profile') }}" class="btn btn-outline-success">
                        <i class="bx bx-store me-2"></i>Profil Vendor
                    </a>
                    <a href="{{ route('vendor.services') }}" class="btn btn-outline-primary">
                        <i class="bx bx-list-ul me-2"></i>Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Pesanan</h6>
                            <h3 class="fw-bold text-primary">156</h3>
                            <span class="badge bg-success">+12%</span>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bx bx-check-circle text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Pendapatan</h6>
                            <h3 class="fw-bold text-success">Rp 15.2Jt</h3>
                            <span class="badge bg-success">+8%</span>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bx bx-dollar text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Rating</h6>
                            <h3 class="fw-bold text-warning">4.8</h3>
                            <span class="badge bg-success">+0.2</span>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bx bx-star text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Ulasan</h6>
                            <h3 class="fw-bold text-info">89</h3>
                            <span class="badge bg-success">+5</span>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bx bx-heart text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Pesanan Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Customer</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-001</td>
                                    <td>John Doe</td>
                                    <td>Service A</td>
                                    <td><span class="badge bg-success">Selesai</span></td>
                                    <td>12 Mar 2024</td>
                                </tr>
                                <tr>
                                    <td>#ORD-002</td>
                                    <td>Jane Smith</td>
                                    <td>Service B</td>
                                    <td><span class="badge bg-warning">Proses</span></td>
                                    <td>11 Mar 2024</td>
                                </tr>
                                <tr>
                                    <td>#ORD-003</td>
                                    <td>Bob Johnson</td>
                                    <td>Service C</td>
                                    <td><span class="badge bg-primary">Pending</span></td>
                                    <td>10 Mar 2024</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Tindakan Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('vendor.services') }}" class="btn btn-primary btn-lg text-start">
                            <i class="bx bx-plus-circle me-2"></i>
                            Tambah Layanan Baru
                        </a>
                        <a href="{{ route('vendor.schedule') }}" class="btn btn-warning btn-lg text-start">
                            <i class="bx bx-time me-2"></i>
                            Atur Jadwal
                        </a>
                        <a href="{{ route('vendor.profile') }}" class="btn btn-success btn-lg text-start">
                            <i class="bx bx-edit me-2"></i>
                            Edit Profil Vendor
                        </a>
                        <a href="#" class="btn btn-info btn-lg text-start">
                            <i class="bx bx-trending-up me-2"></i>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection