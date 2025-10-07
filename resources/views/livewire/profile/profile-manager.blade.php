{{-- resources/views/livewire/profile/profile-manager.blade.php --}}
<div>
    <!-- Role Switcher - Hanya tampil jika user punya vendor profile -->
    @if($hasVendorProfile) {{-- Sekarang menggunakan property component --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-2">
            <div class="d-flex bg-light rounded-3 p-1">
                <button type="button" 
                        class="btn flex-fill {{ $currentRole === 'user' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                        wire:click="switchRole('user')">
                    <i class="bi bi-person me-2"></i>
                    <span>Pengguna</span>
                </button>
                <button type="button" 
                        class="btn flex-fill {{ $currentRole === 'vendor' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                        wire:click="switchRole('vendor')">
                    <i class="bi bi-shop me-2"></i>
                    <span>Vendor</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Dynamic Content -->
    <div>
        @if($currentRole === 'vendor' && $hasVendorProfile)
            <livewire:profile.vendor-profile-component />
        @else
            <livewire:profile.user-profile />
        @endif
    </div>
</div>