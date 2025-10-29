<div class="position-relative" x-data="{ open: false }">
    <!-- Tombol Notifikasi -->
    <button @click="open = !open" class="btn btn-light rounded-circle position-relative">
        <i class="bx bx-bell"></i>
        @auth
            @if ($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $unreadCount }}
                </span>
            @endif
        @endauth
    </button>

    <!-- Dropdown -->
    <div x-show="open" @click.away="open = false" x-transition
        class="position-absolute end-0 mt-2 w-100 bg-white shadow-lg rounded-3" style="z-index: 9999; min-width: 280px;">
        <div class="p-3 border-bottom fw-bold text-dark">Notifikasi</div>

        @guest
            <!-- Untuk Guest Users -->
            <div class="p-3 text-center">
                <p class="text-muted mb-2">Silakan login untuk melihat notifikasi</p>
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
            </div>
        @else
            <!-- Untuk Authenticated Users -->
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
                <a href="{{ route('notifications.detail', $notif->id) }}" class="text-decoration-none">
                <div class="px-3 py-2 border-bottom small {{ $notif->read_at ? 'bg-light' : 'bg-white' }}"
                    wire:click="markAsRead('{{ $notif->id }}')">
                    <div class="fw-semibold text-dark">{{ $data['title'] ?? 'Pemberitahuan' }}</div>
                    <div class="text-muted">{{ $data['message'] ?? '-' }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">
                        {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                    </div>
                </div>
                </a>
            @empty
                <div class="p-3 text-center text-muted small">Belum ada notifikasi</div>
            @endforelse

            <div class="p-2 text-center">
                <a href="{{ route('notifications.index') }}" class="text-primary small text-decoration-none">
                    Lihat semua
                </a>
            </div>
        @endguest
    </div>
</div>