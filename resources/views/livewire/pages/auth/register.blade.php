<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Notifications\NewUserRegisteredNotification;

new #[Layout('layouts.auth', ['title' => 'Daftar'])] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // kirim email verifikasi
        if (config('mail.default') !== 'log') {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                logger('Gagal kirim email verifikasi: ' . $e->getMessage());
            }
        }

        // kirim notifikasi ke database
        $user->notify(new NewUserRegisteredNotification($user->name));

        // redirect seperti biasa
        $this->redirect(route('home', absolute: false), navigate: true);
    }

    public function mount(): void
    {
        if (auth()->check()) {
            $this->redirectRoute('home', navigate: true);
        }
    }
};
?>

<div class="card border-0 shadow-sm p-4">
    <div class="text-center mb-4">
        <h4 class="fw-semibold text-dark mb-1">Daftar Akun Baru</h4>
        <p class="text-muted small mb-2">Bergabung dengan Work Spotter sekarang</p>
    </div>

    <form wire:submit="register" class="needs-validation" novalidate>
        <div class="mb-4">
            <label class="form-label fw-semibold text-dark small">Nama Lengkap</label>
            <input wire:model="name" type="text" class="form-control form-control-sm"
                placeholder="Masukkan nama lengkap anda" required autofocus>
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold text-dark small">Email</label>
            <input wire:model="email" type="email" class="form-control form-control-sm"
                placeholder="Masukkan email anda" required>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold text-dark small">Password</label>
            <div class="position-relative">
                <input wire:model="password" type="password" class="form-control form-control-sm"
                    placeholder="Masukkan password anda" required id="password">
                <span class="position-absolute top-50 end-0 translate-middle-y me-2 cursor-pointer"
                    onclick="togglePassword('password', this)">
                    <i class='bx bx-show'></i>
                </span>
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold text-dark small">Konfirmasi Password</label>
            <div class="position-relative">
                <input wire:model="password_confirmation" type="password" class="form-control form-control-sm"
                    placeholder="Masukkan ulang password anda" required id="password_confirmation">
                <span class="position-absolute top-50 end-0 translate-middle-y me-2 cursor-pointer"
                    onclick="togglePassword('password_confirmation', this)">
                    <i class='bx bx-show'></i>
                </span>
            </div>
        </div>

        <script>
            function togglePassword(inputId, iconElement) {
                const input = document.getElementById(inputId);
                const icon = iconElement.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                }
            }
        </script>

        <button type="submit" class="btn btn-sm w-100 text-white fw-semibold" style="background-color:#92B6B1">
            Daftar
        </button>
    </form>

    <div class="text-center mt-4">
        <span class="text-muted small">Sudah punya akun?</span>
        <a href="{{ route('login') }}" wire:navigate class="text-decoration-none small" style="color:#92B6B1">
            Login disini
        </a>
    </div>
</div>
