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
            <input
                wire:model="form.password"
                type="password"
                class="form-control form-control-sm"
                placeholder="Masukkan password anda"
                required
            >
            @error('form.password') 
                <div class="text-danger small mt-1">{{ $message }}</div> 
            @enderror
        </div>

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
        <a href="{{ route('register') }}" wire:navigate class="text-decoration-none small" style="color:#92B6B1">
            Belum punya akun? Daftar disini
        </a>
    </div>
</div>
