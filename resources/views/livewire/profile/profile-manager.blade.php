{{-- resources/views/livewire/profile/profile-manager.blade.php --}}
<div>
    <!-- Role Switcher - Hanya tampil jika user punya vendor profile -->
    @if($hasVendorProfile)
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

    <!-- Tab Navigation -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-2">
            <div class="d-flex bg-light rounded-3 p-1">
                <button type="button" 
                        class="btn flex-fill {{ $activeTab === 'profile' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                        wire:click="switchTab('profile')">
                    <i class="bi bi-person me-2"></i>
                    <span>Profile</span>
                </button>
                
                @if($currentRole === 'vendor' && $hasVendorProfile)
                <button type="button" 
                        class="btn flex-fill {{ $activeTab === 'vendor_dashboard' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                        wire:click="switchTab('vendor_dashboard')">
                    <i class="bi bi-grid me-2"></i>
                    <span>Dashboard</span>
                </button>
                @endif
                
                <button type="button" 
                        class="btn flex-fill {{ $activeTab === 'settings' ? 'btn-primary' : 'btn-light' }} rounded-2 py-2"
                        wire:click="switchTab('settings')">
                    <i class="bi bi-gear me-2"></i>
                    <span>Pengaturan</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Dynamic Content -->
    <div>
        @if($activeTab === 'profile')
            @if($currentRole === 'vendor' && $hasVendorProfile)
                <livewire:profile.vendor-profile-component />
            @else
                <livewire:profile.user-profile />
            @endif
        @elseif($activeTab === 'vendor_dashboard' && $currentRole === 'vendor' && $hasVendorProfile)
            <livewire:profile.vendor-dashboard />
        @elseif($activeTab === 'settings')
            <livewire:profile.profile-settings />
        @endif
    </div>
</div>