{{-- resources/views/livewire/profile/feedback-form.blade.php --}}
<div>
    @if($successMessage)
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form wire:submit="submitFeedback">
                <!-- Rating -->
                <div class="text-center mb-4">
                    <h6 class="fw-semibold mb-3">Bagaimana pengalaman Anda?</h6>
                    <div class="d-flex justify-content-center">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    class="btn btn-link p-1"
                                    wire:click="$set('rating', {{ $i }})">
                                <i class="bx bx{{ $i <= $rating ? 's' : '' }}-star fs-2 {{ $i <= $rating ? 'text-warning' : 'text-muted' }}"></i>
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Message -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Masukan Anda</label>
                    <textarea class="form-control" rows="5" wire:model="message" 
                              placeholder="Bagikan pengalaman, saran, atau kritik Anda..."></textarea>
                    @error('message') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bx bx-send me-2"></i>
                    Kirim Masukan
                </button>
            </form>
        </div>
    </div>

    <!-- Additional Contact -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
            <p class="text-muted small mb-3">
                Atau hubungi kami langsung untuk bantuan lebih lanjut
            </p>
            <div class="d-grid gap-2">
                <a href="mailto:feedback@workspotter.com" class="btn btn-outline-primary">
                    <i class="bx bx-envelope me-2"></i>Email Feedback
                </a>
            </div>
        </div>
    </div>
</div>