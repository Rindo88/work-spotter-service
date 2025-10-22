{{-- resources/views/livewire/profile/vendor-dashboard.blade.php --}}
<div>
    <!-- Dashboard Header -->
    <div class="card border-0 bg-success text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Dashboard Vendor</h4>
                    <p class="mb-0 opacity-75">Kelola bisnis Anda di satu tempat</p>
                </div>
                <div class="text-end">
                    <div class="fw-bold fs-3">4.8</div>
                    <small class="opacity-75">Rating</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="fw-bold fs-4 text-primary">24</div>
                    <small class="text-muted">Pesanan Hari Ini</small>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="fw-bold fs-4 text-success">Rp 2.4Jt</div>
                    <small class="text-muted">Pendapatan Bulan Ini</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Navigation -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-2">
            <div class="d-flex flex-wrap bg-light rounded-3 p-1">
                <button type="button" 
                        class="btn flex-fill {{ $activeSection === 'overview' ? 'btn-success' : 'btn-light' }} rounded-2 py-2 m-1"
                        wire:click="switchSection('overview')">
                    <i class="bi bi-speedometer2 me-2"></i>
                    <span>Overview</span>
                </button>
                <button type="button" 
                        class="btn flex-fill {{ $activeSection === 'services' ? 'btn-success' : 'btn-light' }} rounded-2 py-2 m-1"
                        wire:click="switchSection('services')">
                    <i class="bi bi-list-task me-2"></i>
                    <span>Layanan</span>
                </button>
                <button type="button" 
                        class="btn flex-fill {{ $activeSection === 'schedule' ? 'btn-success' : 'btn-light' }} rounded-2 py-2 m-1"
                        wire:click="switchSection('schedule')">
                    <i class="bi bi-clock me-2"></i>
                    <span>Jadwal</span>
                </button>
                <button type="button" 
                        class="btn flex-fill {{ $activeSection === 'analytics' ? 'btn-success' : 'btn-light' }} rounded-2 py-2 m-1"
                        wire:click="switchSection('analytics')">
                    <i class="bi bi-graph-up me-2"></i>
                    <span>Analitik</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Dynamic Content -->
    <div>
        @if($activeSection === 'overview')
            <!-- Overview Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Ringkasan Bisnis</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded p-2 me-3">
                                    <i class="bi bi-calendar-check text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">156</div>
                                    <small class="text-muted">Pesanan Bulan Ini</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded p-2 me-3">
                                    <i class="bi bi-currency-dollar text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Rp 15.2Jt</div>
                                    <small class="text-muted">Total Pendapatan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded p-2 me-3">
                                    <i class="bi bi-star text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">4.8</div>
                                    <small class="text-muted">Rating</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded p-2 me-3">
                                    <i class="bi bi-people text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">89%</div>
                                    <small class="text-muted">Kepuasan Pelanggan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($activeSection === 'services')
            <!-- Services Management -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelola Layanan</h5>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="bi bi-plus me-1"></i>Tambah Layanan
                    </button>
                </div>
                <div class="card-body">
                    @if(count($services) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Layanan</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Durasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $index => $service)
                                    <tr>
                                        <td>{{ $service['name'] }}</td>
                                        <td>{{ Str::limit($service['description'], 50) }}</td>
                                        <td>Rp {{ number_format($service['price'], 0, ',', '.') }}</td>
                                        <td>{{ $service['duration'] }} menit</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    wire:click="removeService({{ $index }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                            <p class="text-muted">Belum ada layanan yang ditambahkan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Add Service Modal -->
            <div class="modal fade" id="addServiceModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Layanan Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit="addService">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Nama Layanan</label>
                                        <input type="text" class="form-control" wire:model="newService.name">
                                        @error('newService.name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" rows="3" wire:model="newService.description"></textarea>
                                        @error('newService.description') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="col-6">
                                        <label class="form-label">Harga (Rp)</label>
                                        <input type="number" class="form-control" wire:model="newService.price">
                                        @error('newService.price') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="col-6">
                                        <label class="form-label">Durasi (menit)</label>
                                        <input type="number" class="form-control" wire:model="newService.duration">
                                        @error('newService.duration') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Simpan Layanan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($activeSection === 'schedule')
            <!-- Schedule Management -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Jadwal Operasional</h5>
                </div>
                <div class="card-body">
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
                    
                    @foreach($days as $key => $day)
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label">{{ $day }}</label>
                        </div>
                        <div class="col-4">
                            <input type="time" class="form-control" 
                                   wire:model.live="schedule.{{ $key }}.open"
                                   wire:change="updateSchedule('{{ $key }}', $event.target.value, $schedule['{{ $key }}']['close'] ?? '')">
                        </div>
                        <div class="col-4">
                            <input type="time" class="form-control"
                                   wire:model.live="schedule.{{ $key }}.close"
                                   wire:change="updateSchedule('{{ $key }}', $schedule['{{ $key }}']['open'] ?? '', $event.target.value)">
                        </div>
                        <div class="col-1">
                            @if(($schedule[$key]['closed'] ?? false) || (empty($schedule[$key]['open'] ?? '') && empty($schedule[$key]['close'] ?? '')))
                                <span class="badge bg-danger">Tutup</span>
                            @else
                                <span class="badge bg-success">Buka</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        @elseif($activeSection === 'analytics')
            <!-- Analytics Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Analitik Bisnis</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="bi bi-graph-up-arrow fs-1 text-muted mb-3"></i>
                        <h6 class="text-muted">Fitur Analitik Akan Segera Hadir</h6>
                        <p class="text-muted small">Kami sedang mengembangkan fitur analitik yang lebih lengkap untuk membantu Anda menganalisis performa bisnis.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>