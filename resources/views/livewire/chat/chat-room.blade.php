<div class="d-flex flex-column vh-100">
    
    <!-- Header -->
    <div class="bg-white border-bottom py-3 px-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('chat.index') }}" class="text-decoration-none text-dark me-3">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            
            @if($user)
            <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=92B6B1&color=fff" 
                     class="rounded-circle me-3" width="40" height="40">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Messages Area -->
    <div class="flex-grow-1 bg-light p-3 overflow-auto">
        @foreach($chats as $chat)
            <div class="mb-3 {{ $chat->user_id == auth()->id() ? 'text-end' : 'text-start' }}">
                <div class="{{ $chat->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-white' }} 
                            rounded-3 px-3 py-2 d-inline-block">
                    <p class="mb-1">{{ $chat->message }}</p>
                    <small class="{{ $chat->user_id == auth()->id() ? 'text-light' : 'text-muted' }}">
                        {{ $chat->created_at->format('H:i') }}
                    </small>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input Message -->
    <div class="bg-white border-top p-3">
        <div class="d-flex gap-2">
            <input type="text" 
                   wire:model="message" 
                   wire:keydown.enter="sendMessage"
                   placeholder="Ketik pesan..." 
                   class="form-control">
            <button wire:click="sendMessage" class="btn btn-primary">
                <i class="bi bi-send"></i>
            </button>
        </div>
    </div>
</div>