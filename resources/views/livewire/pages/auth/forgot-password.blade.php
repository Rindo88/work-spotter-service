<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth', ['title' => 'Lupa Password'])] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        session()->flash('status', __('Link reset password telah dikirim ke email kamu.'));
        $this->reset('email');
    }
};
?>

<div class="card border-0 shadow-sm p-4">
    <div class="text-center mb-3">
        <h5 class="fw-semibold text-dark mb-0">Lupa Password?</h5>
        <p class="text-muted small mb-0">Masukkan email kamu untuk menerima link reset password.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="needs-validation" novalidate>
        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Email</label>
            <input 
                wire:model="email"
                type="email"
                class="form-control form-control-sm"
                placeholder="Masukkan email anda"
                required
                autofocus
            >
            @error('email') 
                <div class="text-danger small mt-1">{{ $message }}</div> 
            @enderror
        </div>

        <button 
            type="submit"
            class="btn btn-sm w-100 text-white fw-semibold"
            style="background-color:#92B6B1"
        >
            Kirim Link Reset
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" wire:navigate class="text-decoration-none small" style="color:#92B6B1">
            Kembali ke Login
        </a>
    </div>
</div>
