{{-- resources/views/profile/feedback.blade.php --}}
@extends('layouts.app')

@section('title', 'Berikan Masukan - Work Spotter')

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('profile') }}" class="text-decoration-none text-dark me-3">
            <i class="bx bx-left-arrow-alt fs-5"></i>
        </a>
        <h5 class="fw-bold mb-0">Berikan Masukan</h5>
    </div>
    <livewire:profile.feedback-form />
</div>
@endsection