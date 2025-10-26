<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth', ['title' => 'Login'])]
class extends Component
{
    public LoginForm $form;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->redirectRoute('home', navigate: true);
        }
    }

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
};
?>

<div class="card border-0 shadow-sm p-4">
    <div class="text-center mb-4">
        <h4 class="fw-semibold text-dark mb-1">Masuk ke Akun Anda</h4>
        <p class="text-muted small">Silakan login untuk melanjutkan</p>
    </div>

    <form wire:submit="login" class="needs-validation" novalidate>
        <div class="mb-4">
            <label class="form-label fw-semibold text-dark small">Email</label>
            <input
                wire:model="form.email"
                type="text"
                class="form-control form-control-sm"
                placeholder="Masukkan email anda"
                required
                autofocus
            >
            @error('form.email') 
                <div class="text-danger small mt-1">{{ $message }}</div> 
            @enderror
        </div>

        <div class="mb-2">
            <label class="form-label fw-semibold text-dark small">Password</label>
            <div class="position-relative">
                <input
                    wire:model="form.password"
                    type="password"
                    id="password"
                    class="form-control form-control-sm"
                    placeholder="Masukkan password anda"
                    required
                >
                <button type="button" onclick="togglePassword()" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent">
                    <i id="toggleIcon" class='bx bx-show'></i>
                </button>
            </div>
            @error('form.password') 
                <div class="text-danger small mt-1">{{ $message }}</div> 
            @enderror
        </div>

        <script>
            function togglePassword() {
                const input = document.getElementById('password');
                const icon = document.getElementById('toggleIcon');
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

        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('password.request') }}" wire:navigate class="text-decoration-none text-primary small">
                Lupa Password?
            </a>
        </div>

        <button type="submit" class="btn btn-sm w-100 text-white fw-semibold" style="background-color:#92B6B1">
            Login
        </button>
    </form>

    <div class="text-center mt-3">
        Belum punya akun? <a href="{{ route('register') }}" wire:navigate class="text-decoration-none" style="color:#92B6B1">Daftar disini</a>
    </div>
</div>
