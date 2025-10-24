{{-- resources/views/vendor/rfid.blade.php --}}
@extends('layouts.app')

@section('title', 'Kartu RFID - Work Spotter')

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('profile') }}" class="text-decoration-none text-dark me-3">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h5 class="fw-bold mb-0">Kartu RFID</h5>
    </div>
    <livewire:vendor.rfid-manager />
</div>
@endsection