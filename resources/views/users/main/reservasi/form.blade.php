@extends('users.layout.app')

@section('title', 'Form Reservasi Ruangan')

@section('css')
<style>
    .form-header {
        margin-bottom: 30px;
    }

    .form-header h1 {
        color: var(--gold-primary);
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 8px;
    }

    .form-header p {
        color: #666;
        font-size: 14px;
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
        text-decoration: none;
    }

    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
    }

    .card-header h5 {
        color: var(--sidebar-text);
        font-weight: 600;
        margin: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        color: var(--sidebar-text);
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px 12px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.1);
        outline: none;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f5f5f5;
        color: #666;
        cursor: not-allowed;
    }

    .form-text {
        color: #666;
        font-size: 12px;
        margin-top: 6px;
        display: block;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
        display: block;
    }

    .info-box {
        background-color: #e7f3ff;
        border-left: 4px solid #0056b3;
        padding: 16px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .info-box i {
        color: #0056b3;
        margin-right: 8px;
    }

    .info-box p {
        color: #0056b3;
        font-size: 13px;
        margin: 0;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .btn-submit {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px 28px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
    }

    .btn-submit:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-submit:disabled {
        background-color: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .section-divider {
        border-top: 2px solid #f0f0f0;
        margin: 24px 0;
    }

    .ruangan-info {
        background-color: #f9f9f9;
        padding: 16px;
        border-radius: 4px;
        border-left: 4px solid var(--gold-primary);
    }

    .ruangan-info-item {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ruangan-info-item:last-child {
        margin-bottom: 0;
    }

    .ruangan-info-label {
        color: #666;
        font-size: 13px;
        font-weight: 600;
    }

    .ruangan-info-value {
        color: var(--sidebar-text);
        font-size: 14px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<a href="{{ route('users.main.ruangan.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ruangan
</a>

<div class="form-header">
    <h1><i class="fas fa-calendar-check"></i> Form Reservasi Ruangan</h1>
    <p>Isi formulir di bawah untuk melakukan reservasi ruangan</p>
</div>

@if($errors->any())
<div class="card" style="border-left: 4px solid #dc3545;">
    <div class="card-body">
        <div style="color: #dc3545; margin-bottom: 12px;">
            <i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:
        </div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('users.main.reservasi.store') }}" method="POST">
    @csrf

    <!-- Informasi Ruangan Terpilih -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-door-open"></i> Ruangan Terpilih</h5>
        </div>
        <div class="card-body">
            <div class="ruangan-info">
                <div class="ruangan-info-item">
                    <span class="ruangan-info-label">Nama Ruangan</span>
                    <span class="ruangan-info-value">{{ $ruangan->nama_ruangan }}</span>
                </div>
                <div class="ruangan-info-item">
                    <span class="ruangan-info-label">Jenis Ruangan</span>
                    <span class="ruangan-info-value">{{ str_replace('_', ' ', $ruangan->tipe_ruangan) }}</span>
                </div>
                <div class="ruangan-info-item">
                    <span class="ruangan-info-label">Gedung</span>
                    <span class="ruangan-info-value">{{ $ruangan->gedung->nama_gedung ?? '-' }}</span>
                </div>
                <div class="ruangan-info-item">
                    <span class="ruangan-info-label">Kapasitas</span>
                    <span class="ruangan-info-value"><i class="fas fa-users"></i> {{ $ruangan->kapasitas }} orang</span>
                </div>
            </div>

            <!-- Hidden input untuk ruangan_id -->
            <input type="hidden" name="ruangan_id" value="{{ $ruangan->id_ruangan }}">
        </div>
    </div>

    <!-- Form Reservasi -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-file-alt"></i> Informasi Reservasi</h5>
        </div>
        <div class="card-body">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>Pastikan semua data yang Anda isi akurat. Admin akan menghubungi Anda untuk konfirmasi reservasi.</p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai *</label>
                    <input 
                        type="date" 
                        id="tanggal_mulai" 
                        name="tanggal_mulai" 
                        class="form-control" 
                        value="{{ old('tanggal_mulai') }}"
                        required
                    >
                    @error('tanggal_mulai')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai *</label>
                    <input 
                        type="date" 
                        id="tanggal_selesai" 
                        name="tanggal_selesai" 
                        class="form-control" 
                        value="{{ old('tanggal_selesai') }}"
                        required
                    >
                    @error('tanggal_selesai')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="keperluan" class="form-label">Keperluan / Tujuan Penggunaan *</label>
                <textarea 
                    id="keperluan" 
                    name="keperluan" 
                    class="form-control" 
                    rows="4"
                    placeholder="Jelaskan keperluan penggunaan ruangan"
                    required
                >{{ old('keperluan') }}</textarea>
                <span class="form-text">Minimal 10 karakter, maksimal 500 karakter</span>
                @error('keperluan')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="estimasi_peserta" class="form-label">Estimasi Peserta *</label>
                    <input 
                        type="number" 
                        id="estimasi_peserta" 
                        name="estimasi_peserta" 
                        class="form-control" 
                        value="{{ old('estimasi_peserta') }}"
                        min="1"
                        max="{{ $ruangan->kapasitas }}"
                        required
                    >
                    <span class="form-text">Maksimal {{ $ruangan->kapasitas }} orang (kapasitas ruangan)</span>
                    @error('estimasi_peserta')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Data Kontak -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-user"></i> Data Kontak</h5>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="kontak_person" class="form-label">Nama Kontak Person *</label>
                    <input 
                        type="text" 
                        id="kontak_person" 
                        name="kontak_person" 
                        class="form-control" 
                        value="{{ old('kontak_person', auth()->user()->name ?? '') }}"
                        placeholder="Nama lengkap"
                        required
                    >
                    @error('kontak_person')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_telepon" class="form-label">Nomor Telepon *</label>
                    <input 
                        type="tel" 
                        id="no_telepon" 
                        name="no_telepon" 
                        class="form-control" 
                        value="{{ old('no_telepon') }}"
                        placeholder="08xx xxxx xxxx"
                        required
                    >
                    <span class="form-text">Nomor aktif untuk konfirmasi reservasi</span>
                    @error('no_telepon')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Submit -->
    <div class="card">
        <div class="card-body">
            <button type="submit" class="btn-submit">
                <i class="fas fa-check-circle"></i> Ajukan Reservasi
            </button>
        </div>
    </div>
</form>

@endsection

@section('js')
<script>
    // Validasi tanggal
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');
        const estimasiPeserta = document.getElementById('estimasi_peserta');

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        tanggalMulai.min = today;
        tanggalSelesai.min = today;

        // Update tanggal_selesai minimum when tanggal_mulai changes
        tanggalMulai.addEventListener('change', function() {
            tanggalSelesai.min = this.value;
        });

        // Validate estimasi_peserta
        estimasiPeserta.addEventListener('change', function() {
            const max = parseInt(this.max);
            const value = parseInt(this.value);
            
            if (value > max) {
                this.value = max;
                alert('Estimasi peserta tidak boleh melebihi kapasitas ruangan (' + max + ' orang)');
            }
        });
    });
</script>
@endsection
