@section('title', 'Detail Notifikasi - Work Spotter')
@section('header-title', 'Detail Notifikasi')

<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-semibold mb-2">
                {{ $notification->data['title'] ?? 'Pemberitahuan' }}
            </h5>

            <p class="text-muted mb-3">
                {{ $notification->data['message'] ?? 'Tidak ada detail pesan.' }}
            </p>

            <div class="text-secondary small mt-3">
                Diterima {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
            </div>
        </div>
    </div>

    {{-- 🔹 Bagian Tombol Aksi --}}
    <div class="mt-4 d-flex justify-content-between">
        {{-- Tombol Tandai Sudah Dibaca --}}
        @if (is_null($notification->read_at))
            <button wire:click="markAsRead"
                    wire:loading.attr="disabled"
                    class="btn btn-sm btn-outline-primary">
                <span wire:loading.remove wire:target="markAsRead">Tandai Sudah Dibaca</span>
                <span wire:loading wire:target="markAsRead">Menandai...</span>
            </button>
        @else
            <button class="btn btn-sm btn-success" disabled>
                ✅ Sudah Dibaca
            </button>
        @endif
    </div>
</div>
