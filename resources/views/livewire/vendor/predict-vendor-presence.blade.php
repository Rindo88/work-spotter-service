<div>
    {{-- Form --}}
    <div class="mb-3">
        <label class="form-label">Jam Mulai (opsional)</label>
        <input wire:model.defer="start_time" type="time" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Jam Selesai (opsional)</label>
        <input wire:model.defer="end_time" type="time" class="form-control">
    </div>

    <button wire:click="predict" wire:loading.attr="disabled" class="btn btn-primary w-100 mb-3">
        <span wire:loading.remove>🔮 Prediksi Kehadiran</span>
        <span wire:loading>
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            Sedang menganalisis AI...
        </span>
    </button>

    {{-- Loading bar --}}
    <div wire:loading.flex class="justify-content-center my-2">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    {{-- Hasil sukses --}}
    @if (!empty($result) && empty($errorMessage))
        <div class="alert alert-success mt-3">
            <h6 class="fw-bold">Hasil Prediksi untuk {{ $result['vendor'] ?? ($vendor->business_name ?? 'Vendor') }}</h6>
            <p class="mb-1"><strong>Periode:</strong> {{ $result['start_time'] ?? ($start_time ?? 'Sekarang') }} - {{ $result['end_time'] ?? 'Sekarang' }}</p>

            <pre class="bg-light p-2 rounded">
{{ is_array($result['ai_result'] ?? null) ? json_encode($result['ai_result'], JSON_PRETTY_PRINT) : ($result['ai_result'] ?? 'Tidak ada hasil') }}
            </pre>
        </div>
    @endif

    {{-- Error --}}
    @if (!empty($errorMessage))
        <div class="alert alert-danger mt-3">
            ⚠️ {{ $errorMessage }}
        </div>
    @endif
</div>
