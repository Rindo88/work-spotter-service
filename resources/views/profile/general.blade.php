{{-- resources/views/profile/general.blade.php --}}
@extends('layouts.app')

@section('title', 'Informasi Umum - Work Spotter')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('profile') }}" class="text-decoration-none text-dark me-3">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h5 class="fw-bold mb-0">Informasi Umum</h5>
    </div>

    <livewire:profile.general-information />
</div>
@endsection