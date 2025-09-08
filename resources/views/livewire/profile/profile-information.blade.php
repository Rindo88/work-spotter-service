<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;

new class extends Component
{
    public $user;
    public $stats = [
        'checkins' => 0,
        'reviews' => 0,
        'chats' => 0
    ];

    protected $listeners = ['profile-updated' => '$refresh'];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->user = Auth::user();
        $this->loadStats();
    }

    /**
     * Load user statistics.
     */
    public function loadStats(): void
    {
        // Load counts with fallback to 0 if relationship doesn't exist
        // $this->stats = [
        //     'checkins' => $this->user->checkins()->count(),
        //     'reviews' => $this->user->reviews()->count(),
        //     'chats' => $this->user->chats()->count(),
        // ];
    }

    /**
     * Update the component when profile is updated.
     */
    public function onProfileUpdated(): void
    {
        $this->user->refresh();
        $this->loadStats();
    }
}; ?>

<div>
    <div class="profile-card mb-4">
        <div class="profile-header">
            @if($user->profile_picture)
                <img src="{{ Storage::url($user->profile_picture) }}" class="profile-avatar" alt="Profile Photo">
            @else
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="profile-info">
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                @if($user->phone)
                    <p class="phone-number">{{ $user->phone }}</p>
                @endif
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $stats['checkins'] }}</div>
                <div class="stat-label">Check-in</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['reviews'] }}</div>
                <div class="stat-label">Review</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['chats'] }}</div>
                <div class="stat-label">Chat</div>
            </div>
        </div>
    </div>
</div>