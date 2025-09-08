<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>
<!-- resources/views/livewire/profile/update-password-form.blade.php -->
<div>
    <div class="profile-card">
        <header class="mb-4">
            <h2 class="h5 fw-bold mb-2">Ubah Password</h2>
            <p class="text-muted mb-0">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
        </header>

        <form wire:submit="updatePassword" class="mt-3">
            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">Password Saat Ini</label>
                <input wire:model="current_password" type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
                @error('current_password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label">Password Baru</label>
                <input wire:model="password" type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
                @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password</label>
                <input wire:model="password_confirmation" type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
                @error('password_confirmation') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">Simpan Password</button>

                @if (session('status') === 'password-updated')
                    <span class="text-success">Tersimpan.</span>
                @endif
            </div>
        </form>
    </div>
</div>