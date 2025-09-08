@extends('layouts.phone')

@section('title', 'Nama Halaman')
@section('header-title', 'Judul Header')

@section('content')
    <!-- Konten halaman Anda di sini -->
    <div class="container">
        <h2>Selamat Datang di Work Spotter</h2>
        <p>Ini adalah konten halaman utama.</p>
    </div>
@endsection

@push('styles')
    <!-- Styles khusus halaman ini -->
    <style>
        .custom-style {
            color: #92B6B1;
        }
    </style>
@endpush

@push('scripts')
    <!-- Scripts khusus halaman ini -->
    <script>
        console.log('Halaman telah dimuat');
    </script>
@endpush