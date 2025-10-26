{{-- resources/views/profile/feedback.blade.php --}}
@extends('layouts.app')

@section('title', 'Berikan Masukan - Work Spotter')
@section('header-title', 'Berikan Masukan')
    
@section('content')
<div class="container-fluid px-3 py-2">
    <livewire:profile.feedback-form />
</div>
@endsection