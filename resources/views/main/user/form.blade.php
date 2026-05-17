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
            <form action="{{ route('main.users.update', $user->email_users) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Pengguna --}}
                <div class="mb-3">
                    <label for="name_users" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Nama Pengguna
                    </label>
                    <input type="text" 
                           class="form-control @error('name_users') is-invalid @enderror" 
                           id="name_users" 
                           name="name_users" 
                           value="{{ old('name_users', $user->name_users) }}" 
                           placeholder="Masukkan nama pengguna"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('name_users')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat Email --}}
                <div class="mb-4">
                    <label for="email_users" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Alamat Email
                    </label>
                    <input type="email" 
                           class="form-control @error('email_users') is-invalid @enderror" 
                           id="email_users" 
                           name="email_users" 
                           value="{{ old('email_users', $user->email_users) }}" 
                           placeholder="Masukkan alamat email"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('email_users')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

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
