<!-- resources/views/profile/layout.blade.php -->
@extends('layouts.phone')

@section('title', 'Profil Pengguna')
@section('header-title', 'Profil Saya')

@section('content')
<div class="container-fluid px-0">
    <!-- Profile Header -->
    @livewire('profile.profile-information')

    <!-- Update Profile Information -->
    @livewire('profile.update-profile-information-form')

    <!-- Update Password -->
    @livewire('profile.update-password-form')

    <!-- Delete Account -->
    @livewire('profile.delete-user-form')
</div>
@endsection

@push('styles')
<style>
   .profile-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.profile-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #92B6B1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
    margin-right: 16px;
    object-fit: cover;
}

.profile-avatar-lg {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.profile-info h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.profile-info p {
    margin: 4px 0 0;
    color: #666;
    font-size: 0.9rem;
}

.profile-info .phone-number {
    color: #92B6B1;
    font-weight: 500;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.stat-item {
    text-align: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 600;
    color: #92B6B1;
}

.stat-label {
    font-size: 0.75rem;
    color: #666;
}

/* Profile Photo Edit Styles */
.position-relative {
    position: relative;
    display: inline-block;
}

.btn-edit-photo {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 32px;
    height: 32px;
    background: #92B6B1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    border: 2px solid white;
    transition: all 0.2s ease;
}

.btn-edit-photo:hover {
    background: #7fa19c;
    transform: scale(1.1);
}

.btn-delete-photo {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 24px;
    height: 24px;
    background: #dc3545;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    border: 2px solid white;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}

.btn-delete-photo:hover {
    background: #bb2d3b;
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script>
    // Preview image before upload
    document.addEventListener('livewire:initialized', () => {
        const profilePhotoInput = document.getElementById('profilePhotoInput');
        
        profilePhotoInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // You can add preview functionality here if needed
                    console.log('File selected:', this.files[0].name);
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush