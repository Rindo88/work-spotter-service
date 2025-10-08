<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatList extends Component
{
    public $users = [];

    public function mount()
    {
        // Ambil semua user kecuali diri sendiri
        $this->users = User::where('id', '!=', Auth::id())->get();
    }

    public function render()
    {
        return view('livewire.chat.chat-list', ['users' => $this->users])->layout('layouts.app');
    }
}