@extends('layouts.vendor-dashboard')

@section('content')
<!-- Welcome Section -->
<div class="section-card mb-4">
    <div class="d-flex align-items-center">
        @if($vendor->profile_picture)
            <img src="{{ Storage::url($vendor->profile_picture) }}" class="profile-avatar me-3" alt="Profile">
        @else
            <div class="profile-avatar me-3">
                {{ strtoupper(substr($vendor->business_name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h3 class="mb-1">Halo, {{ $vendor->business_name }}!</h3>
            <p class="text-muted mb-0">Selamat datang di dashboard vendor Anda</p>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"></div>
        <div class="stat-label">Pengunjung Hari Ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"></div>
        <div class="stat-label">Total Pesanan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"></div>
        <div class="stat-label">Pesanan Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">4.8</div>
        <div class="stat-label">Rating</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="section-card">
    <h4 class="section-title">Aksi Cepat</h4>
    <div class="quick-actions">
        <a href="" class="action-btn">
            <div class="action-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div>Tambah Layanan</div>
        </a>
        <a href="" class="action-btn">
            <div class="action-icon">
                <i class="bi bi-geo-alt"></i>
            </div>
            <div>Update Lokasi</div>
        </a>
        <a href="" class="action-btn">
            <div class="action-icon">
                <i class="bi bi-cart-check"></i>
            </div>
            <div>Lihat Pesanan</div>
        </a>
        <a href="" class="action-btn">
            <div class="action-icon">
                <i class="bi bi-chat-dots"></i>
            </div>
            <div>Balas Chat</div>
        </a>
    </div>
</div>

<!-- Recent Orders -->
<div class="section-card">
    <div class="section-title">
        <span>Pesanan Terbaru</span>
        <a href="" class="text-primary" style="font-size: 0.9rem;">Lihat Semua</a>
    </div>
    
    {{-- @forelse($recentOrders as $order)
    <div class="recent-item">
        <div class="recent-icon">
            <i class="bi bi-cart"></i>
        </div>
        <div class="recent-content">
            <div class="recent-title">{{ $order->customer_name }}</div>
            <div class="recent-time">{{ $order->created_at->diffForHumans() }} • {{ $order->service_name }}</div>
        </div>
        <div class="text-{{ $order->status_color }}">
            {{ $order->status }}
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-3">
        <i class="bi bi-cart-x fs-1"></i>
        <p>Belum ada pesanan</p>
    </div>
    @endforelse
</div> --}}

<!-- Recent Messages -->
<div class="section-card">
    <div class="section-title">
        <span>Pesan Terbaru</span>
        <a href="" class="text-primary" style="font-size: 0.9rem;">Lihat Semua</a>
    </div>
    
    @forelse($recentMessages as $message)
    <div class="recent-item">
        <div class="recent-icon">
            <i class="bi bi-chat"></i>
        </div>
        <div class="recent-content">
            <div class="recent-title">{{ $message->customer_name }}</div>
            <div class="recent-time">{{ $message->created_at->diffForHumans() }}</div>
        </div>
        @if($message->unread)
        <span class="badge bg-primary">Baru</span>
        @endif
    </div>
    @empty
    <div class="text-center text-muted py-3">
        <i class="bi bi-chat-left fs-1"></i>
        <p>Belum ada pesan</p>
    </div>
    @endforelse
</div>

<!-- Business Hours -->
<div class="section-card">
    <h4 class="section-title">Jam Operasional</h4>
    <div class="business-hours">
        @foreach($businessHours as $day => $hours)
        <div class="d-flex justify-content-between py-2 border-bottom">
            <span>{{ $day }}</span>
            <span class="{{ $hours['open'] ? 'text-success' : 'text-danger' }}">
                {{ $hours['open'] ? $hours['open'] . ' - ' . $hours['close'] : 'Tutup' }}
            </span>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-3">
        <a href="" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit Jam Operasi
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        font-weight: bold;
        object-fit: cover;
    }
    
    .business-hours .border-bottom:last-child {
        border-bottom: none !important;
    }
</style>
@endpush