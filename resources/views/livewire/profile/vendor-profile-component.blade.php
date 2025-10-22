    {{-- resources/views/livewire/profile/vendor-profile-component.blade.php --}}
    <div>
        <!-- Vendor Profile Header -->
        <div class="card border-0 bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="position-relative">
                        <img src="{{ $vendor->profile_picture ? asset('storage/' . $vendor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($vendor->business_name) . '&background=fff&color=28a745' }}" 
                            class="rounded-circle border border-3 border-white" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div class="ms-3">
                        <h4 class="mb-1">{{ $vendor->business_name }}</h4>
                        <p class="mb-1 opacity-75">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $vendor->address ?? 'Alamat belum diatur' }}
                        </p>
                        <div>
                            <span class="badge bg-light text-success me-2">
                                <i class="bi bi-shop me-1"></i>Vendor
                            </span>
                            <span class="badge bg-warning text-dark">
                                {{ $vendor->type === 'formal' ? 'Formal' : 'Informal' }}
                            </span>
                            @if($vendor->is_rfid)
                            <span class="badge bg-info text-dark">
                                <i class="bi bi-credit-card me-1"></i>RFID
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Vendor Stats -->
                <div class="row text-center mt-3">
                    <div class="col-4">
                        <div class="fw-bold fs-5">{{ $vendor->rating_avg }}</div>
                        <small class="opacity-75">Rating</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-5">150</div>
                        <small class="opacity-75">Pesanan</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-5">95%</div>
                        <small class="opacity-75">Response</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Information Form -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2 text-success"></i>
                    Informasi Bisnis
                </h5>
            </div>
            <div class="card-body">
                @if($successMessage)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $successMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form wire:submit="saveProfile">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Bisnis *</label>
                            <input type="text" class="form-control" wire:model="business_name">
                            @error('business_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Deskripsi Bisnis *</label>
                            <textarea class="form-control" rows="3" wire:model="description" placeholder="Deskripsikan bisnis Anda..."></textarea>
                            @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Alamat Bisnis *</label>
                            <textarea class="form-control" rows="2" wire:model="address" placeholder="Alamat lengkap bisnis..."></textarea>
                            @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Foto Profile Vendor</label>
                            <input type="file" class="form-control" wire:model="profile_picture" accept="image/*">
                            @error('profile_picture') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Catatan Operasional</label>
                            <textarea class="form-control" rows="2" wire:model="operational_notes" placeholder="Jam operasional, hari libur, dll..."></textarea>
                            @error('operational_notes') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>