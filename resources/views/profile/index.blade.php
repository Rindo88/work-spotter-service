{{-- resources/views/profile/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya - Work Spotter')

@section('content')
<div class="container-fluid px-3 py-2">
    <!-- Tampilkan berdasarkan role yang aktif -->
    @if(session('current_profile_role') === 'vendor' && Auth::user()->vendor)
        <!-- Vendor Profile View -->
        <livewire:profile.vendor-profile-view />
    @else
        <!-- User Profile View -->
        <livewire:profile.user-profile-view />
    @endif
</div>
@endsection