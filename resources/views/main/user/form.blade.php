@extends('main.layout.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Alert Error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 14px;">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0" style="padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Form --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-user-edit me-2"></i>Edit Data Pengguna
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('main.users.update', $user->email) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Pengguna --}}
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Nama Pengguna
                    </label>
                    <input type="text" 
                           class="form-control @error('username') is-invalid @enderror" 
                           id="username" 
                           name="username" 
                           value="{{ old('username', $user->username) }}" 
                           placeholder="Masukkan nama pengguna"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Alamat Email
                    </label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           placeholder="Masukkan alamat email"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status Akun --}}
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Status Akun
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" style="font-size: 14px; padding: 10px 14px;">
                        <option value="ACTIVE" {{ old('status', $user->status) == 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                        <option value="INACTIVE" {{ old('status', $user->status) == 'INACTIVE' ? 'selected' : '' }}>Non Aktif</option>
                        <option value="SUSPENDED" {{ old('status', $user->status) == 'SUSPENDED' ? 'selected' : '' }}>Di Blokir Sementara</option>
                        <option value="SUSPENDED_PERMANENT" {{ old('status', $user->status) == 'SUSPENDED_PERMANENT' ? 'selected' : '' }}>Di Blokir Permanen</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alasan Pemblokiran (Hanya tampil jika status SUSPENDED atau SUSPENDED_PERMANENT) --}}
                <div class="mb-4" id="blocked-reason-wrapper" style="display: none;">
                    <label for="blocked_reason" class="form-label fw-semibold" style="font-size: 13px; color: #dc3545;">
                        Alasan Pemblokiran
                    </label>
                    <textarea class="form-control @error('blocked_reason') is-invalid @enderror" 
                              id="blocked_reason" 
                              name="blocked_reason" 
                              rows="3" 
                              placeholder="Masukkan alasan kenapa akun ini diblokir (alasan ini akan dikirim ke email pengguna)"
                              style="font-size: 14px; padding: 10px 14px;">{{ old('blocked_reason', $user->blocked_reason) }}</textarea>
                    @error('blocked_reason')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const statusSelect = document.getElementById('status');
                        const reasonWrapper = document.getElementById('blocked-reason-wrapper');
                        
                        function toggleReason() {
                            const val = statusSelect.value;
                            if (val === 'SUSPENDED' || val === 'SUSPENDED_PERMANENT') {
                                reasonWrapper.style.display = 'block';
                            } else {
                                reasonWrapper.style.display = 'none';
                            }
                        }

                        statusSelect.addEventListener('change', toggleReason);
                        toggleReason(); // run once on load
                    });
                </script>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm px-4" style="background-color: #C9A961; color: #fff; font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('main.users.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
