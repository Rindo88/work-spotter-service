@section('header-title', 'Semua Notifikasi')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"></h5>
        <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-secondary">
            Tandai Semua Dibaca
        </button>
    </div>

    @if (!auth()->user()->hasVerifiedEmail())
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <div>
                <strong>Email kamu belum diverifikasi.</strong>
                <br>
                @if ($verificationSent)
                    <span class="text-success small">Email verifikasi sudah dikirim ✅</span>
                @else
                    <span class="small">Silakan kirim verifikasi untuk mengaktifkan akun.</span>
                @endif
            </div>

            <div>
                @if (!$verificationSent)
                    <button wire:click="sendVerificationEmail" wire:loading.attr="disabled"
                        class="btn btn-sm btn-outline-dark">
                        <span wire:loading.remove wire:target="sendVerificationEmail">Verifikasi Sekarang</span>
                        <span wire:loading wire:target="sendVerificationEmail">Mengirim...</span>
                    </button>
                @else
                    <button class="btn btn-sm btn-success" disabled>
                        Terkirim ✅
                    </button>
                @endif
            </div>
        </div>
    @endif

    @forelse ($notifications as $notif)
        @php $data = $notif->data; @endphp
        <a href="{{ route('notifications.detail', $notif->id) }}" class="text-decoration-none text-dark">
            <div class="card mb-2 border-0 shadow-sm {{ $notif->read_at ? 'bg-light' : '' }}">
                <div class="card-body py-2">
                    <div class="fw-semibold">{{ $data['title'] ?? 'Pemberitahuan' }}</div>
                    <div class="text-muted small">{{ $data['message'] ?? '' }}</div>
                    <div class="text-secondary small">
                        {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                    </div>
                </div>
            </div>
        </a>
    @empty
        <p class="text-muted text-center mt-5">Belum ada notifikasi</p>
    @endforelse

</div>
