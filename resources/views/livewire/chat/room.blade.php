<div class="d-flex flex-column vh-100" wire:poll.2s>
    
    <!-- Header -->
    <div class="bg-white border-bottom py-3 px-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('chat.index') }}" class="text-decoration-none text-dark me-3">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            
            @if($error)
                <div class="alert alert-warning w-100 mb-0 py-2" role="alert">
                    <small><i class="bi bi-exclamation-triangle me-2"></i>{{ $error }}</small>
                </div>
            @elseif($partner)
            <div class="d-flex align-items-center">
                @if($userType === 'user')
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($partner->user->name ?? $partner->business_name) }}&background=92B6B1&color=white" 
                         class="rounded-circle me-3" 
                         width="45" 
                         height="45"
                         alt="{{ $partner->user->name ?? $partner->business_name }}">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $partner->user->name ?? $partner->business_name }}</h6>
                        <small class="text-muted">
                            @if($partner->is_online)
                                <span class="text-success">• Online</span>
                            @else
                                <span class="text-muted">• Offline</span>
                            @endif
                        </small>
                    </div>
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($partner->name) }}&background=92B6B1&color=white" 
                         class="rounded-circle me-3" 
                         width="45" 
                         height="45"
                         alt="{{ $partner->name }}">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $partner->name }}</h6>
                        <small class="text-muted">Customer</small>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Error State -->
    @if($error)
        <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-light">
            <div class="text-center text-muted">
                <i class="bi bi-chat-square-text display-1"></i>
                <p class="mt-3">{{ $error }}</p>
                <a href="{{ route('chat.index') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Chat
                </a>
            </div>
        </div>
    @else
        <!-- Messages Area -->
        <div class="flex-grow-1 bg-light p-3 overflow-auto" id="chat-messages">
            <div class="d-flex flex-column">
                @foreach($this->chats as $chat)
                    <div class="mb-3 {{ $chat->sender_type === auth()->user()->role ? 'align-self-end' : 'align-self-start' }}" 
                         style="max-width: 80%;">
                        
                        <div class="{{ $chat->sender_type === auth()->user()->role ? 'bg-primary text-white' : 'bg-white' }} 
                                    rounded-3 px-3 py-2 shadow-sm">
                            <p class="mb-1">{{ $chat->message }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="{{ $chat->sender_type === auth()->user()->role ? 'text-white-50' : 'text-muted' }}">
                                    {{ $chat->created_at->format('H:i') }}
                                </small>
                                @if($chat->sender_type === auth()->user()->role)
                                    <small class="ms-2">
                                        @if($chat->is_read)
                                            <i class="bi bi-check2-all"></i>
                                        @else
                                            <i class="bi bi-check2"></i>
                                        @endif
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(count($this->chats) === 0)
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-quote display-1"></i>
                        <p class="mt-3 mb-0">Mulai percakapan dengan {{ $userType === 'user' ? ($partner->user->name ?? $partner->business_name) : $partner->name }}</p>
                        <small>Kirim pesan pertamamu</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Message Input -->
        <div class="bg-white border-top p-3">
            <div class="d-flex align-items-center gap-2">
                <input type="text" 
                       wire:model="message" 
                       wire:keydown.enter="sendMessage"
                       placeholder="Ketik pesan..." 
                       class="form-control rounded-pill border-0 bg-light"
                       {{ $error ? 'disabled' : '' }}>
                <button wire:click="sendMessage" 
                        wire:loading.attr="disabled"
                        class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px;"
                        {{ $error ? 'disabled' : '' }}>
                    <i class="bi bi-send"></i>
                </button>
            </div>
            
            <!-- Loading State -->
            <div wire:loading wire:target="sendMessage" class="text-center mt-2">
                <small class="text-muted"><i class="bi bi-arrow-clockwise spinner me-2"></i>Mengirim...</small>
            </div>
        </div>
    @endif
</div>

<script>
// Auto scroll to bottom
document.addEventListener('livewire:init', function() {
    Livewire.hook('commit', ({ component, succeed }) => {
        succeed(() => {
            setTimeout(() => {
                const container = document.getElementById('chat-messages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        });
    });
});
</script>