{{-- resources/views/profile/general.blade.php --}}
@extends('layouts.app')

@section('title', 'Informasi Umum - Work Spotter')

@section('header-title', 'Informasi Umum')
@section('content')
<div class="container-fluid px-3 py-2">
    <livewire:profile.general-information />
</div>
@endsection