<div>
    
    <!-- Header -->
        @if($this->userType === 'user' && $this->chatPartners->sum('unread_messages_count') > 0)
            <small class="text-center d-block text-muted">
                {{ $this->chatPartners->sum('unread_messages_count') }} pesan belum dibaca
            </small>
        @elseif($this->userType === 'vendor' && $this->chatPartners->sum('unread_messages_count') > 0)
            <small class="text-center d-block text-muted">
                {{ $this->chatPartners->sum('unread_messages_count') }} pesan belum dibaca
            </small>
        @endif

    <!-- Error State -->
    @if($error)
        <div class="alert alert-danger m-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $error }}
            <div class="mt-2">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-danger">Kembali ke Home</a>
            </div>
        </div>
    @endif

    <!-- Chat List -->
    <div class="list-group rounded-0">
        @forelse($this->chatPartners as $partner)
            @php
                $unreadCount = $partner->unread_messages_count ?? 0;
                
                // Tentukan data berdasarkan tipe user
                if ($this->userType === 'user') {
                    // $partner adalah Vendor
                    $name = $partner->user->name ?? $partner->business_name ?? 'Vendor';
                    $avatarName = $partner->user->name ?? $partner->business_name ?? 'V';
                    $route = route('chat.room', ['vendorId' => $partner->id]);
                    $latestMessage = $partner->latest_message;
                } else {
                    // $partner adalah User
                    $name = $partner->name;
                    $avatarName = $partner->name;
                    $route = route('chat.room', ['vendorId' => $partner->id]); // Tetap gunakan vendorId parameter
                    $latestMessage = $partner->latest_message;
                }
            @endphp
            
            <a href="{{ $route }}" 
               class="list-group-item list-group-item-action border-0 py-3">
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($avatarName) }}&background=92B6B1&color=white" 
                         class="rounded-circle me-3" 
                         width="50" 
                         height="50"
                         alt="{{ $name }}">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-1 fw-bold">{{ $name }}</h6>
                            @if($unreadCount > 0)
                                <span class="badge bg-primary rounded-pill">{{ $unreadCount }}</span>
                            @endif
                        </div>
                        
                        @if($latestMessage)
                            <p class="mb-0 text-muted small text-truncate" style="max-width: 200px;">
                                {{ $latestMessage->message }}
                            </p>
                            <small class="text-muted">
                                {{ $latestMessage->created_at->diffForHumans() }}
                            </small>
                        @else
                            <p class="mb-0 text-muted small">Belum ada pesan</p>
                            <small class="text-muted">Mulai percakapan</small>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            @if(!$error)
                <div class="text-center py-5">
                    <i class="bi bi-chat-dots display-1 text-muted"></i>
                    <p class="text-muted mt-3">Belum ada percakapan</p>
                    
                    @if($this->userType === 'user')
                        <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-search me-2"></i>Cari Vendor untuk Chat
                        </a>
                    @else
                        <p class="text-muted small">Menunggu customer menghubungi Anda</p>
                    @endif
                </div>
            @endif
        @endforelse
    </div>
</div>