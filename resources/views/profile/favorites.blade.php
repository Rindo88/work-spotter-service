{{-- resources/views/profile/favorites.blade.php --}}
@extends('layouts.app')

@section('header-title', 'Favorit')

@section('content')
<div class="container-fluid px-3 py-2">
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="text-center py-4">
                <i class="bx bx-bookmark display-1 text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada favorit</h6>
                <p class="text-muted small">Vendor dan layanan yang Anda simpan akan muncul di sini</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                    <i class="bx bx-search me-2"></i>
                    Jelajahi Vendor
                </a>
            </div>
        </div>
    </div>
</div>
@endsection