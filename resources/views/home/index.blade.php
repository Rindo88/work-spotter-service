{{-- resources/views/home/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Work Spotter - Home')
@section('header-title', 'Work Spotter')

@section('content')
<div class="container-fluid px-0">
    <!-- Search Bar & Notification -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex align-items-center gap-2">
                <!-- Search Input -->
                <div class="flex-grow-1 position-relative">
                    <i class="bi bi-search position-absolute top-50 start-3 translate-middle-y text-muted"></i>
                    <input type="text" class="form-control ps-5 rounded-pill" placeholder="Cari layanan atau vendor...">
                </div>
                <!-- Notification Icon -->
                <button class="btn btn-light rounded-circle position-relative">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Banner Slider -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="banner-item" style="height: 200px; background: linear-gradient(135deg, #92B6B1, #6D8B87);">
                            <div class="d-flex align-items-center justify-content-center h-100 text-white p-3">
                                <div class="text-center">
                                    <h5 class="fw-bold mb-2">Temukan Vendor Terbaik</h5>
                                    <p class="mb-0 small">Layanan berkualitas dengan harga terjangkau</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="banner-item" style="height: 200px; background: linear-gradient(135deg, #FF6B6B, #FF8E8E);">
                            <div class="d-flex align-items-center justify-content-center h-100 text-white p-3">
                                <div class="text-center">
                                    <h5 class="fw-bold mb-2">Promo Spesial</h5>
                                    <p class="mb-0 small">Diskon hingga 50% untuk layanan tertentu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Grid -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Akses Cepat</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Pedagang Informal -->
                <div class="col-4">
                    <div class="quick-access-card text-center p-2">
                        <div class="icon-wrapper mb-2 mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745, #20c997);">
                            <i class="bi bi-cart fs-5 text-white"></i>
                        </div>
                        <h6 class="mb-1 small fw-bold">Pedagang Informal</h6>
                        <small class="text-muted">Kaki lima & UMKM</small>
                    </div>
                </div>
                <!-- Rating Tertinggi -->
                <div class="col-4">
                    <div class="quick-access-card text-center p-2">
                        <div class="icon-wrapper mb-2 mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #ffc107, #fd7e14);">
                            <i class="bi bi-star-fill fs-5 text-white"></i>
                        </div>
                        <h6 class="mb-1 small fw-bold">Rating Tertinggi</h6>
                        <small class="text-muted">Terbaik & Terpercaya</small>
                    </div>
                </div>
                <!-- Lokasi Terdekat -->
                <div class="col-4">
                    <div class="quick-access-card text-center p-2">
                        <div class="icon-wrapper mb-2 mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc3545, #e83e8c);">
                            <i class="bi bi-geo-alt-fill fs-5 text-white"></i>
                        </div>
                        <h6 class="mb-1 small fw-bold">Lokasi Terdekat</h6>
                        <small class="text-muted">Ditempat Anda</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Kategori Layanan</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Makanan & Minuman -->
                <div class="col-4">
                    <div class="category-card text-center p-2">
                        <div class="category-icon mb-2">
                            <i class="bi bi-cup-hot-fill fs-2 text-warning"></i>
                        </div>
                        <h6 class="mb-0 small fw-bold">Makanan</h6>
                    </div>
                </div>
                <!-- Minuman -->
                <div class="col-4">
                    <div class="category-card text-center p-2">
                        <div class="category-icon mb-2">
                            <i class="bi bi-cup-straw fs-2 text-success"></i>
                        </div>
                        <h6 class="mb-0 small fw-bold">Minuman</h6>
                    </div>
                </div>
                <!-- Jasa Reparasi -->
                <div class="col-4">
                    <div class="category-card text-center p-2">
                        <div class="category-icon mb-2">
                            <i class="bi bi-tools fs-2 text-primary"></i>
                        </div>
                        <h6 class="mb-0 small fw-bold">Reparasi</h6>
                    </div>
                </div>
                <!-- Fashion -->
                <div class="col-4">
                    <div class="category-card text-center p-2">
                        <div class="category-icon mb-2">
                            <i class="bi bi-bag-fill fs-2 text-info"></i>
                        </div>
                        <h6 class="mb-0 small fw-bold">Fashion</h6>
                    </div>
                </div>
                <!-- Elektronik -->
                <div class="col-4">
                    <div class="category-card text-center p-2">
                        <div class="category-icon mb-2">
                            <i class="bi bi-laptop fs-2 text-secondary"></i>
                        </div>
                        <h6 class="mb-0 small fw-bold">Elektronik</h6>
                    </div>
                </div>
                <!-- Lainnya -->
                <div class="col-4">
                    <div class="category-card text-center p-2">
                        <div class="category-icon mb-2">
                            <i class="bi bi-three-dots fs-2 text-muted"></i>
                        </div>
                        <h6 class="mb-0 small fw-bold">Lainnya</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Vendors -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Rekomendasi</h5>
                <a href="#" class="text-primary text-decoration-none small">Lihat Semua</a>
            </div>
        </div>
        <div class="card-body">
            @for($i = 0; $i < 3; $i++)
            <div class="vendor-card mb-3">
                <div class="d-flex gap-3">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=80&h=80&fit=crop&crop=center" 
                         class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 fw-bold">Warung Nasi Uduk Bu Sri</h6>
                            <span class="badge bg-success">
                                <i class="bi bi-star-fill me-1"></i>4.8
                            </span>
                        </div>
                        <p class="text-muted small mb-2">Nasi Uduk • Soto • Gado-gado</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>
                                <span>1.2 km</span>
                            </div>
                            <div class="d-flex align-items-center text-success small">
                                <i class="bi bi-clock me-1"></i>
                                <span>Buka</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- All Vendors Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Semua Vendor</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @for($i = 0; $i < 6; $i++)
                <div class="col-6">
                    <div class="vendor-grid-card">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=300&h=200&fit=crop&crop=center" 
                             class="rounded-3 w-100 mb-2" style="height: 120px; object-fit: cover;">
                        <div class="vendor-info">
                            <h6 class="fw-bold mb-1">Pizza Hot Corner</h6>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-success small">
                                    <i class="bi bi-star-fill me-1"></i>4.6
                                </span>
                                <span class="text-muted small">
                                    <i class="bi bi-geo-alt me-1"></i>2.1 km
                                </span>
                            </div>
                            <p class="text-muted small mb-2">Pizza • Pasta • Western Food</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark small">Formal</span>
                                <span class="badge bg-success small">Buka</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .quick-access-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        padding: 8px;
    }
    
    .quick-access-card:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa;
    }
    
    .category-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        padding: 12px 8px;
    }
    
    .category-card:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa;
    }
    
    .vendor-card {
        padding: 12px;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .vendor-card:hover {
        background-color: #f8f9fa;
        transform: translateX(4px);
    }
    
    .vendor-grid-card {
        background: white;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
    }
    
    .vendor-grid-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    
    .banner-item {
        position: relative;
        overflow: hidden;
    }
    
    .form-control:focus {
        border-color: #92B6B1;
        box-shadow: 0 0 0 0.2rem rgba(146, 182, 177, 0.25);
    }
    
    .icon-wrapper {
        transition: transform 0.3s ease;
    }
    
    .quick-access-card:hover .icon-wrapper {
        transform: scale(1.1);
    }
</style>
@endpush