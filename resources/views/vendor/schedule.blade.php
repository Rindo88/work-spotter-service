{{-- resources/views/vendor/schedule.blade.php --}}
@extends('layouts.app')

@section('title', 'Jadwal - Work Spotter')
@section('header-title', 'Jadwal Operasional')
@section('content')
<div class="container-fluid px-3 py-2">
    <livewire:vendor.schedule-manager />
</div>
@endsection