{{-- resources/views/livewire/components/schedule-form.blade.php --}}
<div>
    <div class="mb-4">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" 
                   wire:model="hasSchedule" id="has_schedule_{{ $componentId }}">
            <label class="form-check-label fw-semibold" for="has_schedule_{{ $componentId }}">
                <i class="bx bx-time me-2"></i>Atur Jadwal Operasional
            </label>
        </div>
        <small class="text-muted d-block ms-4">Centang untuk mengatur jadwal operasional khusus</small>

        @if($hasSchedule)
            <div class="schedule-container border rounded p-4 bg-white mt-3">
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bx bx-calendar me-2"></i>Jadwal Operasional
                </h6>
                
                @foreach($schedules as $dayKey => $schedule)
                    <div class="schedule-day-card border rounded p-3 mb-3 {{ $schedule['is_closed'] ? 'bg-light' : 'bg-white' }}">
                        <div class="row align-items-center">
                            <!-- Day Label -->
                            <div class="col-12 col-md-2 mb-2 mb-md-0">
                                <label class="form-check-label fw-semibold {{ $schedule['is_closed'] ? 'text-muted' : 'text-dark' }}">
                                    {{ $schedule['day_name'] }}
                                </label>
                            </div>
                            
                            <!-- Time Inputs -->
                            <div class="col-12 col-md-6">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="time" class="form-control form-control-sm" 
                                               wire:model="schedules.{{ $dayKey }}.open_time"
                                               {{ $schedule['is_closed'] ? 'disabled' : '' }}
                                               placeholder="Buka"
                                               id="open_{{ $dayKey }}_{{ $componentId }}">
                                        @error("schedules.{$dayKey}.open_time")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <input type="time" class="form-control form-control-sm" 
                                               wire:model="schedules.{{ $dayKey }}.close_time"
                                               {{ $schedule['is_closed'] ? 'disabled' : '' }}
                                               placeholder="Tutup"
                                               id="close_{{ $dayKey }}_{{ $componentId }}">
                                        @error("schedules.{$dayKey}.close_time")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Closed Toggle -->
                            <div class="col-12 col-md-2 text-center">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" 
                                           wire:model="schedules.{{ $dayKey }}.is_closed"
                                           role="switch"
                                           id="closed_{{ $dayKey }}_{{ $componentId }}">
                                    <label class="form-check-label small" for="closed_{{ $dayKey }}_{{ $componentId }}">
                                        Tutup
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Notes -->
                            <div class="col-12 mt-2">
                                <input type="text" class="form-control form-control-sm" 
                                       placeholder="Catatan hari ini (opsional)" 
                                       wire:model="schedules.{{ $dayKey }}.notes"
                                       id="notes_{{ $dayKey }}_{{ $componentId }}">
                                @error("schedules.{$dayKey}.notes")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @error('schedules')
                    <div class="alert alert-danger mt-2">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
                
                <div class="alert alert-info mt-3">
                    <small>
                        <i class="bx bx-info-circle"></i> 
                        Kosongkan waktu atau centang "Tutup" untuk hari yang tidak beroperasi. 
                        Setidaknya satu hari harus memiliki jadwal operasional.
                    </small>
                </div>
            </div>
        @endif
    </div>
</div>

@section('styles')
<style>
    .schedule-day-card {
        transition: all 0.3s ease;
        border-left: 4px solid #007bff !important;
    }
    
    .schedule-day-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .schedule-day-card.bg-light {
        border-left-color: #6c757d !important;
        opacity: 0.7;
    }
</style>
@endsection