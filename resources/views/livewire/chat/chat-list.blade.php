<div>
    <!-- Header -->
    <div class="bg-white border-bottom p-3">
        <h5 class="mb-0 fw-bold">Chat</h5>
    </div>
    
    <!-- List Users -->
    <div class="list-group">
        @foreach($users as $user)
            <a href="{{ route('chat.room', $user->id) }}" class="list-group-item list-group-item-action border-0">
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=92B6B1&color=fff" 
                         class="rounded-circle me-3" width="50" height="50">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                        <small class="text-muted">Klik untuk chat</small>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>