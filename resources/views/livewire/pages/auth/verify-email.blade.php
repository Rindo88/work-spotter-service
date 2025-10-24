<?php

use Livewire\Volt\Component;

new class extends Component {
    protected $layout = 'layouts.app'; // set layout di sini

    public function sendVerificationEmail()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('vendor.register');
        }

        auth()->user()->sendEmailVerificationNotification();
        session()->flash('message', 'Email verifikasi telah dikirim ulang!');
    }
};
?>

<html>
<div>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
        <div class="max-w-md w-full bg-white p-6 rounded-2xl shadow">
            <h2 class="text-2xl font-bold mb-4 text-center">Verifikasi Email Anda</h2>

            @if (session('message'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <p class="mb-4 text-gray-600 text-center">
                Kami telah mengirimkan tautan verifikasi ke email Anda:
                <span class="font-semibold">{{ auth()->user()->email }}</span>.
                Silakan cek kotak masuk Anda.
            </p>

            <button
                wire:click="sendVerificationEmail"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                Kirim Ulang Email Verifikasi
            </button>

            <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
                @csrf
                <button type="submit" class="text-gray-500 text-sm hover:text-gray-800">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
</html>