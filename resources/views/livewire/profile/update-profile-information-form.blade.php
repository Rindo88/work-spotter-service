<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $profilePhoto;
    public string $profilePhotoUrl = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->profilePhotoUrl = $user->profile_picture ? Storage::url($user->profile_picture) : '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:15'],
            'profilePhoto' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->fill($validated);

        // Handle profile photo upload
        if ($this->profilePhoto) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
            
            $path = $this->profilePhoto->store('profile-photos', 'public');
            $user->profile_picture = $path;
            $this->profilePhotoUrl = Storage::url($path);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Dispatch event untuk update component lain
        $this->dispatch('profile-updated');
        
        // Reset profile photo property
        $this->profilePhoto = null;

        Session::flash('status', 'profile-updated');
    }

    /**
     * Delete the current profile photo.
     */
    public function deleteProfilePhoto(): void
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            Storage::delete($user->profile_picture);
            $user->profile_picture = null;
            $user->save();
            
            $this->profilePhotoUrl = '';
            
            // Dispatch event untuk update component lain
            $this->dispatch('profile-updated');
        }
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div>
    <div class="profile-card">
        <header class="mb-4">
            <h2 class="h5 fw-bold mb-2">Informasi Profil</h2>
            <p class="text-muted mb-0">Perbarui informasi profil dan alamat email akun Anda.</p>
        </header>

        <!-- Profile Photo Section -->
        <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
                @if($profilePhotoUrl)
                    <img src="{{ $profilePhotoUrl }}" class="profile-avatar-lg" alt="Profile Photo">
                @else
                    <div class="profile-avatar-lg d-flex align-items-center justify-content-center bg-primary text-white">
                        {{ strtoupper(substr($name, 0, 1)) }}
                    </div>
                @endif
                
                <label for="profilePhotoInput" class="btn-edit-photo">
                    <i class="bi bi-camera-fill"></i>
                    <input type="file" id="profilePhotoInput" wire:model="profilePhoto" class="d-none" accept="image/*">
                </label>
                
                @if($profilePhotoUrl)
                    <button type="button" wire:click="deleteProfilePhoto" class="btn-delete-photo">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                @endif
            </div>
            
            @error('profilePhoto') 
                <div class="text-danger mt-2">{{ $message }}</div> 
            @enderror
            
            @if($profilePhoto)
                <div class="mt-2">
                    <span class="text-muted">Preview: {{ $profilePhoto->getClientOriginalName() }}</span>
                </div>
            @endif
        </div>

        <form wire:submit="updateProfileInformation" class="mt-3">
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input wire:model="name" type="text" class="form-control" id="name" name="name" required autofocus autocomplete="name">
                @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input wire:model="email" type="email" class="form-control" id="email" name="email" required autocomplete="username">
                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-muted mb-1">
                            Alamat email Anda belum terverifikasi.
                        </p>

                        <button wire:click.prevent="sendVerification" class="btn btn-link p-0 text-primary">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success mt-2">
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                @endif
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input wire:model="phone" type="tel" class="form-control" id="phone" name="phone" placeholder="81234567890" autocomplete="tel">
                </div>
                @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">
                    <span wire:loading wire:target="updateProfileInformation" class="spinner-border spinner-border-sm me-2"></span>
                    Simpan Perubahan
                </button>

                @if (session('status') === 'profile-updated')
                    <span class="text-success">Tersimpan.</span>
                @endif
            </div>
        </form>
    </div>
</div>