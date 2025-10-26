{{-- resources/views/vendor/services.blade.php --}}
@extends('layouts.app')

@section('title', 'Layanan - Work Spotter')
@section('header-title', 'Kelola Layanan')

@section('content')
<div class="container-fluid px-3 py-2">
    <livewire:vendor.services-manager />
</div>
@endsection