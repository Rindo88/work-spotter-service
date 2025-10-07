<div>
    <div class="mb-3">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" wire:model="hasSchedule" 
                   id="has_schedule_{{ $componentId }}"> <!-- Gunakan $componentId -->
            <label class="form-check-label" for="has_schedule_{{ $componentId }}">
                Atur Jadwal Operasional
            </label>
            <div class="input-hint">Centang jika ingin mengatur jadwal khusus</div>
        </div>

        @if($hasSchedule)
            <div class="schedule-container border p-3 rounded bg-light">
                <label class="form-label mb-3">Jadwal Operasional</label>
                
                @php 
                    $days = [
                        'monday' => 'Senin',
                        'tuesday' => 'Selasa', 
                        'wednesday' => 'Rabu',
                        'thursday' => 'Kamis',
                        'friday' => 'Jumat',
                        'saturday' => 'Sabtu',
                        'sunday' => 'Minggu'
                    ];
                @endphp
                
                @foreach($days as $dayKey => $dayLabel)
                    <div class="row mb-2 align-items-center schedule-day-row">
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <label class="form-check-label fw-medium">{{ $dayLabel }}</label>
                        </div>
                        <div class="col-5 col-md-3">
                            <input type="time" class="form-control form-control-sm" 
                                   wire:model="schedules.{{ $dayKey }}.open"
                                   {{ $schedules[$dayKey]['closed'] ?? false ? 'disabled' : '' }}
                                   id="open_{{ $dayKey }}_{{ $componentId }}"> <!-- Gunakan $componentId -->
                            @error("schedules.{$dayKey}.open")
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-5 col-md-3">
                            <input type="time" class="form-control form-control-sm" 
                                   wire:model="schedules.{{ $dayKey }}.close"
                                   {{ $schedules[$dayKey]['closed'] ?? false ? 'disabled' : '' }}
                                   id="close_{{ $dayKey }}_{{ $componentId }}"> <!-- Gunakan $componentId -->
                            @error("schedules.{$dayKey}.close")
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-2 col-md-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" 
                                       wire:model="schedules.{{ $dayKey }}.closed"
                                       id="closed_{{ $dayKey }}_{{ $componentId }}"> <!-- Gunakan $componentId -->
                                <label class="form-check-label small" for="closed_{{ $dayKey }}_{{ $componentId }}">
                                    Tutup
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    @error("schedules.{$dayKey}")
                        <div class="row mb-2">
                            <div class="col-12">
                                <small class="text-danger">{{ $message }}</small>
                            </div>
                        </div>
                    @enderror
                @endforeach
                
                @error('schedules')
                    <div class="alert alert-danger mt-2">
                        <small>{{ $message }}</small>
                    </div>
                @enderror
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> Kosongkan atau centang "Tutup" untuk hari yang tidak beroperasi
                    </small>
                </div>
            </div>
        @endif
    </div>
</div>