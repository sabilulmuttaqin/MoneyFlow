@extends('layouts.nav')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-page">
    {{-- Success Alert --}}
    @if(session('success'))
    <div class="profile-alert success" id="successAlert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="profile-alert error">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <div class="profile-header">
        <h1>Edit Profile</h1>
        <p>Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <div class="profile-grid">
        {{-- Profile Info Card --}}
        <div class="profile-card-section">
            <div class="card-section-header">
                <h2>Informasi Profil</h2>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                @csrf
                @method('PUT')
                
                {{-- Profile Photo --}}
                <div class="photo-upload-section">
                    <div class="current-photo">
                        <img src="{{ $user->profile_photo_url }}" alt="Profile" id="photoPreview">
                    </div>
                    <div class="photo-upload-info">
                        <label for="profile_photo" class="btn-upload">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            Ubah Foto
                        </label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" hidden>
                        <p class="upload-hint">JPG, PNG, GIF atau WEBP. Maks 2MB</p>
                    </div>
                </div>

                {{-- Name Field --}}
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                </div>

                {{-- Email Field (Read-only) --}}
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input readonly" value="{{ $user->email }}" disabled>
                    <p class="field-hint">Email tidak dapat diubah</p>
                </div>

                <button type="submit" class="btn-save">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Password Card --}}
        <div class="profile-card-section">
            <div class="card-section-header">
                <h2>Ubah Password</h2>
            </div>
            
            <form action="{{ route('profile.password') }}" method="POST" class="profile-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn-save secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

// Auto hide success alert
const alert = document.getElementById('successAlert');
if (alert) {
    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-20px)';
        setTimeout(() => alert.remove(), 300);
    }, 3000);
}
</script>
@endsection
