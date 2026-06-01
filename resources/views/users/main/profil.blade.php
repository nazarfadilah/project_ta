@extends('users.layout.app')

@section('title', 'Profil Saya')

@section('css')
<style>
    .section-header {
        margin-bottom: 30px;
    }

    .section-header h2 {
        color: var(--gold-primary);
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 8px;
    }

    .section-header p {
        color: #666;
        font-size: 14px;
    }

    .profile-card {
        background: #fff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
        overflow: hidden;
    }

    .profile-card-header {
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
        height: 120px;
        position: relative;
    }

    .avatar-wrapper {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #fff;
        padding: 5px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #f3f4f6;
        color: var(--gold-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 800;
        border: 2px solid var(--gold-primary);
        text-transform: uppercase;
    }

    .profile-card-body {
        padding: 70px 24px 30px 24px;
        text-align: center;
    }

    .profile-card-body h4 {
        margin-bottom: 5px;
        font-weight: 700;
        color: #333;
    }

    .profile-card-body .username {
        color: #888;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .profile-status {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .profile-info-list {
        margin-top: 25px;
        text-align: left;
    }

    .profile-info-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        gap: 12px;
    }

    .profile-info-item:last-child {
        border-bottom: none;
    }

    .profile-info-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background-color: rgba(201, 169, 97, 0.1);
        color: var(--gold-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .profile-info-text h6 {
        margin: 0;
        font-size: 12px;
        color: #94a3b8;
    }

    .profile-info-text p {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    .form-card {
        background: #fff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
    }

    .form-section-title {
        color: var(--gold-dark);
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid rgba(201, 169, 97, 0.2);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.15);
    }

    .btn-gold {
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-gold:hover {
        background: linear-gradient(135deg, var(--gold-dark) 0%, #a48135 100%);
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 4px 12px rgba(201, 169, 97, 0.3);
    }

    .alert-success-gold {
        border: none;
        border-left: 4px solid #28a745;
        background-color: #f4faf6;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 25px;
    }

    .alert-error-gold {
        border: none;
        border-left: 4px solid #dc3545;
        background-color: #fdf5f6;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 25px;
    }

    .gender-radio-group {
        display: flex;
        gap: 20px;
        padding-top: 8px;
    }

    .gender-radio-card {
        flex: 1;
        position: relative;
    }

    .gender-radio-card input {
        display: none;
    }

    .gender-radio-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .gender-radio-card input:checked + .gender-radio-label {
        border-color: var(--gold-primary);
        background-color: rgba(201, 169, 97, 0.05);
        color: var(--gold-dark);
    }
</style>
@endsection

@section('content')
<div class="section-header">
    <h2><i class="fas fa-user-circle"></i> Profil Saya</h2>
    <p>Kelola profil pribadi dan pengaturan keamanan akun Anda</p>
</div>

<!-- Alert Notifikasi -->
@if(session('success'))
<div class="alert-success-gold shadow-sm">
    <div style="color: #155724; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-check-circle" style="font-size: 18px;"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert-error-gold shadow-sm">
    <div style="color: #721c24; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-exclamation-circle" style="font-size: 18px;"></i>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="alert-error-gold shadow-sm">
    <div style="color: #721c24; display: flex; align-items: flex-start; gap: 8px;">
        <i class="fas fa-exclamation-circle" style="font-size: 18px; margin-top: 2px;"></i>
        <div>
            <h6 style="margin: 0 0 5px 0; font-weight: 700;">Gagal memperbarui profil:</h6>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<div class="row g-4">
    <!-- Left Column: User Summary Card -->
    <div class="col-lg-4 col-md-5">
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="avatar-wrapper">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
            </div>
            
            <div class="profile-card-body">
                <h4>{{ $guest->name ?? $user->username }}</h4>
                <div class="username">&#64;{{ $user->username }}</div>
                <div class="profile-status">
                    <i class="fas fa-check-shield"></i> Terverifikasi Tamu
                </div>
                
                <div class="profile-info-list">
                    <div class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="profile-info-text">
                            <h6>Alamat Email</h6>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="profile-info-text">
                            <h6>Nomor NIK</h6>
                            <p>{{ $guest->nik ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="profile-info-item">
                        <div class="profile-info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="profile-info-text">
                            <h6>No. Telepon</h6>
                            <p>{{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4 border-0 shadow-sm" style="border-radius: 12px; background: #fffcf5; border: 1px solid rgba(201,169,97,0.15) !important;">
            <div class="card-body p-3">
                <div style="display: flex; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: var(--gold-dark); font-size: 20px; margin-top: 2px;"></i>
                    <div style="font-size: 13px; color: #7f6831; line-height: 1.5;">
                        <strong>Catatan Informasi:</strong><br>
                        Pastikan data NIK dan Nama Lengkap Anda sesuai dengan dokumen identitas resmi (KTP/KTM). Keakuratan data membantu admin memverifikasi pengajuan peminjaman ruangan Anda secara cepat dan akurat.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Profile Edit Form -->
    <div class="col-lg-8 col-md-7">
        <form action="{{ route('users.profil.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-card">
                <!-- Section 1: Personal Data -->
                <div class="form-section-title">
                    <i class="fas fa-id-badge"></i> Data Diri Peminjam
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="nik" class="form-label">NIK / Nomor Identitas</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-right: none; color: #94a3b8;"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $guest->nik ?? '') }}" placeholder="Masukkan 16 digit NIK" style="border-left: none;" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-right: none; color: #94a3b8;"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $guest->name ?? '') }}" placeholder="Nama sesuai identitas" style="border-left: none;" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="gender-radio-group">
                            <div class="gender-radio-card">
                                <input type="radio" id="gender_male" name="gender" value="MALE" {{ old('gender', $guest->gender ?? '') === 'MALE' ? 'checked' : '' }}>
                                <label for="gender_male" class="gender-radio-label">
                                    <i class="fas fa-mars"></i> Pria
                                </label>
                            </div>
                            <div class="gender-radio-card">
                                <input type="radio" id="gender_female" name="gender" value="FEMALE" {{ old('gender', $guest->gender ?? '') === 'FEMALE' ? 'checked' : '' }}>
                                <label for="gender_female" class="gender-radio-label">
                                    <i class="fas fa-venus"></i> Wanita
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="bloodType" class="form-label">Golongan Darah (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-right: none; color: #94a3b8;"><i class="fas fa-tint"></i></span>
                            <select class="form-select" id="bloodType" name="bloodType" style="border-left: none;">
                                <option value="" {{ empty(old('bloodType', $guest->bloodType ?? '')) ? 'selected' : '' }}>-- Pilih Golongan Darah --</option>
                                <option value="A" {{ old('bloodType', $guest->bloodType ?? '') === 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('bloodType', $guest->bloodType ?? '') === 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('bloodType', $guest->bloodType ?? '') === 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('bloodType', $guest->bloodType ?? '') === 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="phone" class="form-label">Nomor Telepon / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-right: none; color: #94a3b8;"><i class="fas fa-phone-alt"></i></span>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Contoh: 08123456789" style="border-left: none;" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap rumah / domisili">{{ old('address', $guest->address ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Catatan / Keterangan (Opsional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan tambahan seperti nama instansi, jurusan, dll">{{ old('notes', $guest->notes ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Section 2: Security & Password -->
                <div class="form-section-title mt-5">
                    <i class="fas fa-shield-alt"></i> Keamanan Akun
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <small class="text-muted d-block mb-3">
                            <i class="fas fa-exclamation-triangle"></i> Biarkan kolom di bawah ini kosong jika Anda tidak ingin mengubah password masuk Anda saat ini.
                        </small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-right: none; color: #94a3b8;"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" style="border-left: none;">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-right: none; color: #94a3b8;"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" style="border-left: none;">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-save"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
