{{-- resources/views/livewire/vendor/rfid-manager.blade.php --}}
<div>
    <!-- Alert -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-1">Kartu RFID</h5>
                    <p class="card-text opacity-75 mb-0">Kelola kartu pembayaran digital Anda</p>
                </div>
                <div class="col-auto">
                    <i class="bi bi-credit-card-2-front fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Info -->
    @if($hasActiveRfid)
    <div class="alert alert-success d-flex align-items-center mb-4">
        <i class="bi bi-check-circle-fill fs-5 me-3"></i>
        <div>
            <h6 class="alert-heading mb-1">RFID Aktif</h6>
            <p class="mb-0">Anda memiliki {{ $rfidTags->where('is_active', true)->count() }} kartu RFID aktif</p>
        </div>
    </div>
    @elseif($rfidTags->isNotEmpty())
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
        <div>
            <h6 class="alert-heading mb-1">RFID Nonaktif</h6>
            <p class="mb-0">Semua kartu RFID Anda saat ini tidak aktif</p>
        </div>
    </div>
    @else
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="bi bi-info-circle-fill fs-5 me-3"></i>
        <div>
            <h6 class="alert-heading mb-1">Belum Ada RFID</h6>
            <p class="mb-0">Ajukan kartu RFID untuk memulai pembayaran digital</p>
        </div>
    </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-pills nav-justified mb-4">
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'cards' ? 'active' : '' }}" 
                    wire:click="switchTab('cards')">
                <i class="bi bi-credit-card me-2"></i>
                Kartu Saya
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'requests' ? 'active' : '' }}" 
                    wire:click="switchTab('requests')">
                <i class="bi bi-clock-history me-2"></i>
                Permintaan
            </button>
        </li>
    </ul>

    @if($activeTab === 'cards')
        <!-- RFID Cards -->
        <div class="row g-3">
            @if($rfidTags->count() > 0)
                @foreach($rfidTags as $tag)
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-gradient {{ $tag->is_active ? 'bg-success' : 'bg-secondary' }} text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <i class="bi bi-cpu-fill fs-2 opacity-75"></i>
                                </div>
                                <span class="badge {{ $tag->is_active ? 'bg-light text-dark' : 'bg-dark' }}">
                                    {{ $tag->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <div class="h5 mb-2 font-monospace">
                                    {{ chunk_split($tag->uid, 4, ' ') }}
                                </div>
                                <div class="small opacity-75">
                                    {{ Auth::user()->vendor->business_name }}
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small opacity-75">
                                    WORK SPOTTER
                                </div>
                                <button type="button" class="btn btn-outline-light btn-sm" wire:click="contactSupport">
                                    <i class="bi bi-chat-dots me-1"></i>
                                    Hubungi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card border-0 text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-credit-card display-1 text-muted mb-3"></i>
                            <h5 class="text-muted mb-2">Belum Ada Kartu RFID</h5>
                            <p class="text-muted mb-3">Mulai dengan mengajukan permintaan kartu RFID pertama Anda</p>
                            <button type="button" class="btn btn-primary" wire:click="openRequestModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                Ajukan Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- Requests Section -->
        <div class="row g-3">
            <!-- Active Request -->
            @if($activeRequest)
            <div class="col-12">
                <div class="card shadow-sm border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-hourglass-split text-warning fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Permintaan Aktif</h6>
                                <p class="card-text text-muted mb-2">
                                    Permintaan RFID Anda sedang dalam proses
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Diajukan: {{ $activeRequest->created_at->format('d M Y') }}
                                    </small>
                                    <span class="badge {{ $activeRequest->status_badge['class'] }}">
                                        {{ $activeRequest->status_badge['text'] }}
                                    </span>
                                </div>
                                
                                @if($activeRequest->tracking_number)
                                <div class="mt-2">
                                    <small class="text-muted">No. Resi: {{ $activeRequest->tracking_number }}</small>
                                </div>
                                @endif
                                
                                @if($activeRequest->admin_notes)
                                <div class="mt-2">
                                    <small class="text-info">
                                        <i class="bi bi-info-circle me-1"></i>
                                        {{ $activeRequest->admin_notes }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary w-100" wire:click="contactSupport">
                            <i class="bi bi-chat-dots me-2"></i>
                            Hubungi Admin
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Request History -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">Riwayat Permintaan</h6>
                            @if(!$activeRequest)
                            <button type="button" class="btn btn-primary btn-sm" wire:click="openRequestModal">
                                <i class="bi bi-plus me-1"></i>
                                Ajukan Baru
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($rfidRequests->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($rfidRequests as $request)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">Permintaan #{{ $request->id }}</h6>
                                            <small class="text-muted">
                                                {{ $request->created_at->format('d M Y H:i') }}
                                            </small>
                                            @if($request->tracking_number)
                                            <div class="mt-1">
                                                <small class="text-success">
                                                    <i class="bi bi-truck me-1"></i>
                                                    Resi: {{ $request->tracking_number }}
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                        <span class="badge {{ $request->status_badge['class'] }}">
                                            {{ $request->status_badge['text'] }}
                                        </span>
                                    </div>
                                    @if($request->admin_notes)
                                    <div class="alert alert-info py-2 mb-0">
                                        <small>
                                            <i class="bi bi-info-circle me-1"></i>
                                            {{ $request->admin_notes }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2">Belum Ada Permintaan</h5>
                                <p class="text-muted mb-3">Ajukan permintaan kartu RFID pertama Anda</p>
                                <button type="button" class="btn btn-primary" wire:click="openRequestModal">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Ajukan Permintaan
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Ajukan Kartu RFID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Proses Pengajuan RFID</h6>
                        <p class="mb-2">Setelah mengajukan permintaan:</p>
                        <ol class="mb-0 ps-3">
                            <li>Admin akan memverifikasi permintaan Anda</li>
                            <li>Anda akan dihubungi via chat untuk konfirmasi</li>
                            <li>Kartu RFID akan diproses dan dikirim</li>
                            <li>No. resi pengiriman akan dikirim via chat</li>
                        </ol>
                    </div>
                    
                    <div class="alert alert-warning">
                        <small>
                            <i class="bi bi-info-circle me-2"></i>
                            Pastikan data vendor Anda sudah lengkap sebelum mengajukan permintaan.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="requestRfid" data-bs-dismiss="modal">
                        <i class="bi bi-send me-2"></i>
                        Ajukan Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Hubungi Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-headset display-4 text-primary mb-3"></i>
                        <h6>Butuh Bantuan?</h6>
                        <p class="text-muted">Hubungi admin via chat untuk informasi lebih lanjut mengenai:</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center">
                            <i class="bi bi-info-circle text-primary me-3"></i>
                            <div>
                                <small class="fw-bold">Status Permintaan RFID</small>
                                <br>
                                <small class="text-muted">Cek progress permintaan Anda</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <i class="bi bi-truck text-primary me-3"></i>
                            <div>
                                <small class="fw-bold">Info Pengiriman</small>
                                <br>
                                <small class="text-muted">Konfirmasi no. resi dan estimasi</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <i class="bi bi-gear text-primary me-3"></i>
                            <div>
                                <small class="fw-bold">Bantuan Teknis</small>
                                <br>
                                <small class="text-muted">Masalah aktivasi atau penggunaan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click="startChat">
                        <i class="bi bi-chat-dots me-2"></i>
                        Buka Chat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
document.addEventListener('livewire:initialized', function() {
    // Listen for Livewire events to open modals
    Livewire.on('open-modal', (modalId) => {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    });

    // Listen for Livewire events to close modals
    Livewire.on('close-modal', (modalId) => {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        if (modal) {
            modal.hide();
        }
    });

    // Handle modal hidden event to sync with Livewire state
    const requestModal = document.getElementById('requestModal');
    const contactModal = document.getElementById('contactModal');

    if (requestModal) {
        requestModal.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('closeModals');
        });
    }

    if (contactModal) {
        contactModal.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('closeModals');
        });
    }
});

// Initialize Bootstrap tooltips if any
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
    
@endpush