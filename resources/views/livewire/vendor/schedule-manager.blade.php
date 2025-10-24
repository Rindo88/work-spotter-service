{{-- resources/views/livewire/vendor/schedule-manager.blade.php --}}
<div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Atur Jadwal Operasional</h6>
            <p class="text-muted small mb-4">Tentukan jam buka dan tutup untuk setiap hari</p>

            <form wire:submit="saveSchedules">
                @foreach($days as $key => $day)
                <div class="border-bottom pb-3 mb-3">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="form-label fw-semibold">{{ $day }}</label>
                        </div>
                        <div class="col-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       wire:model="schedules.{{ $key }}.is_closed"
                                       wire:click="toggleDayClosed('{{ $key }}')">
                                <label class="form-check-label small">Tutup</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <input type="time" class="form-control form-control-sm" 
                                   wire:model="schedules.{{ $key }}.open_time"
                                   {{ $schedules[$key]['is_closed'] ? 'disabled' : '' }}>
                        </div>
                        <div class="col-2">
                            <input type="time" class="form-control form-control-sm"
                                   wire:model="schedules.{{ $key }}.close_time"
                                   {{ $schedules[$key]['is_closed'] ? 'disabled' : '' }}>
                        </div>
                        <div class="col-1 text-center">
                            @if($schedules[$key]['is_closed'])
                                <span class="badge bg-danger small">Tutup</span>
                            @else
                                <span class="badge bg-success small">Buka</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="bi bi-check-circle me-2"></i>
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Tindakan Cepat</h6>
            <div class="row g-2">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-primary w-100 py-2" 
                            wire:click="setAllOpen">
                        <i class="bi bi-unlock me-2"></i>
                        <small>Buka Semua</small>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 py-2"
                            wire:click="setAllClosed">
                        <i class="bi bi-lock me-2"></i>
                        <small>Tutup Semua</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tambahkan method untuk set all open/closed di component
// app/Livewire/Vendor/ScheduleManager.php
// public function setAllOpen()
// {
//     foreach ($this->days as $key => $day) {
//         $this->schedules[$key]['is_closed'] = false;
//         $this->schedules[$key]['open_time'] = '08:00';
//         $this->schedules[$key]['close_time'] = '17:00';
//     }
// }

// public function setAllClosed()
// {
//     foreach ($this->days as $key => $day) {
//         $this->schedules[$key]['is_closed'] = true;
//         $this->schedules[$key]['open_time'] = '';
//         $this->schedules[$key]['close_time'] = '';
//     }
// }
</script>