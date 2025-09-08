<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /*
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<!-- resources/views/livewire/profile/delete-user-form.blade.php -->
<div>
    <div class="profile-card">
        <header class="mb-4">
            <h2 class="h5 fw-bold mb-2">Hapus Akun</h2>
            <p class="text-muted mb-0">
                Setelah akun Anda dihapus, semua resource dan data Anda akan dihapus secara permanen. 
                Sebelum menghapus akun Anda, harap unduh data atau informasi yang ingin Anda simpan.
            </p>
        </header>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
            Hapus Akun
        </button>

        <!-- Modal -->
        <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionLabel">Hapus Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">
                            Apakah Anda yakin ingin menghapus akun Anda? Setelah akun dihapus, 
                            semua data akan dihapus secara permanen. Masukkan password Anda untuk mengonfirmasi.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input wire:model="password" type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="current-password">
                            @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" wire:click="deleteUser" wire:loading.attr="disabled" class="btn btn-danger">
                            <span wire:loading.remove>Hapus Akun</span>
                            <span wire:loading>Menghapus...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>