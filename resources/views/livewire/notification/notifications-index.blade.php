<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Semua Notifikasi</h5>
        <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-secondary">
            Tandai Semua Dibaca
        </button>
    </div>

    @forelse ($notifications as $notif)
        @php $data = $notif->data; @endphp
        <div class="card mb-2 border-0 shadow-sm {{ $notif->read_at ? 'bg-light' : '' }}">
            <div class="card-body py-2">
                <div class="fw-semibold">{{ $data['title'] ?? 'Pemberitahuan' }}</div>
                <div class="text-muted small">{{ $data['message'] ?? '' }}</div>
                <div class="text-secondary small">
                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted text-center mt-5">Belum ada notifikasi</p>
    @endforelse
</div>
