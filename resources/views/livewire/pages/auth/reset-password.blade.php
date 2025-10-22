<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth', ['title' => 'Reset Password'])] class extends Component
{
    public string $email = '';
    public string $token = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function resetPassword(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        session()->flash('status', __('Password kamu berhasil direset! Silakan login.'));
        $this->redirectRoute('login', navigate: true);
    }
};
?>

<div class="card border-0 shadow-sm p-4">
    <div class="text-center mb-3">
        <h5 class="fw-semibold text-dark mb-0">Reset Password</h5>
        <p class="text-muted small mb-0">Masukkan email dan password baru kamu.</p>
    </div>

    <form wire:submit="resetPassword" class="needs-validation" novalidate>
        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Email</label>
            <input wire:model="email" type="email" class="form-control form-control-sm" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Password Baru</label>
            <input wire:model="password" type="password" class="form-control form-control-sm" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Konfirmasi Password</label>
            <input wire:model="password_confirmation" type="password" class="form-control form-control-sm" required>
        </div>

        <button type="submit" class="btn btn-sm w-100 text-white fw-semibold" style="background-color:#92B6B1">
            Simpan Password Baru
        </button>
    </form>
</div>
