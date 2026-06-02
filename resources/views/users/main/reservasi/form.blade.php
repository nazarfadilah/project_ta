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
        font-size: 30px;
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
        border-radius: 6px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        text-decoration: none;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
        text-decoration: none;
    }

    .card {
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        margin-bottom: 25px;
        background-color: #ffffff;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
        color: #ffffff;
        padding: 18px 24px;
        border-bottom: none;
    }

    .card-header h5 {
        font-weight: 600;
        margin: 0;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 11px 14px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.15);
        outline: none;
    }

    .form-control:disabled,
    .form-control[readonly],
    .form-select:disabled {
        background-color: #f8fafc;
        color: #64748b;
        cursor: not-allowed;
    }

    .form-text {
        color: #64748b;
        font-size: 12px;
        margin-top: 6px;
        display: block;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
        display: block;
        font-weight: 600;
    }

    .info-box {
        background-color: #f0f7ff;
        border-left: 4px solid #0284c7;
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-box i {
        color: #0284c7;
        font-size: 18px;
        margin-top: 2px;
    }

    .info-box p {
        color: #0369a1;
        font-size: 13px;
        margin: 0;
        line-height: 1.5;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        color: white;
        border: none;
        padding: 14px 28px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    }

    .btn-submit:disabled {
        background-color: #cbd5e1;
        cursor: not-allowed;
        transform: none;
    }

    .ruangan-details-card {
        background-color: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid var(--gold-primary);
        padding: 20px;
        margin-bottom: 20px;
    }

    .detail-item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .detail-item {
        margin-bottom: 8px;
    }

    .detail-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .detail-val {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }

    .media-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px;
        margin-top: 15px;
    }

    .media-item {
        border-radius: 6px;
        overflow: hidden;
        aspect-ratio: 4/3;
        background-color: #e2e8f0;
        border: 1px solid #cbd5e1;
    }

    .media-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .booked-badge {
        background-color: #ffe4e6;
        color: #be123c;
        border: 1px solid #fecdd3;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        margin-right: 8px;
    }

    .sarana-row {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 12px;
        position: relative;
    }

    .btn-remove-sarana {
        background: none;
        border: none;
        color: #ef4444;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-remove-sarana:hover {
        color: #dc2626;
        text-decoration: underline;
    }

    .btn-add-sarana {
        background-color: #f1f5f9;
        color: #334155;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-add-sarana:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .estimasi-box {
        background-color: #fcf8f2;
        border: 1px solid rgba(201, 169, 97, 0.25);
        border-radius: 8px;
        padding: 20px;
        margin-top: 15px;
    }

    .estimasi-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed rgba(201, 169, 97, 0.15);
    }

    .estimasi-item:last-child {
        border-bottom: none;
    }

    .estimasi-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .estimasi-val {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }

    .estimasi-val.price {
        color: #b45309;
        font-size: 18px;
    }
</style>
@endsection

@section('content')
<a href="{{ route('users.main.reservasi.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Reservasi Saya
</a>

<div class="form-header">
    <h1><i class="fas fa-calendar-check"></i> Form Reservasi Ruangan</h1>
    <p>Silakan isi formulir di bawah ini untuk mengajukan peminjaman ruangan beserta sarana tambahan.</p>
</div>

@if($errors->any())
<div class="card border-0 mb-4" style="border-left: 4px solid #dc3545 !important;">
    <div class="card-body bg-danger bg-opacity-10 py-3">
        <div style="color: #b91c1c; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <i class="fas fa-exclamation-circle" style="font-size: 18px;"></i>
            <span>Gagal mengirim formulir. Harap periksa beberapa kesalahan di bawah:</span>
        </div>
        <ul style="margin: 0; padding-left: 20px; color: #b91c1c; font-size: 13px; font-weight: 500;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('users.main.reservasi.store') }}" method="POST" id="reservasiForm">
    @csrf

    <!-- SECTION 1: PEMILIHAN RUANGAN -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-door-open"></i> 1. Pilih Ruangan & Fasilitas</h5>
        </div>
        <div class="card-body p-4">
            
            @if($ruanganId)
                <!-- Skenario Pre-selected Room -->
                <div class="form-group mb-3">
                    <label class="form-label">Ruangan Terpilih</label>
                    <input type="text" class="form-control fw-bold" value="{{ $ruangan->nama_ruangan }}" readonly>
                    <input type="hidden" name="ruangan_id" id="ruangan_id" value="{{ $ruangan->id_ruangan }}">
                </div>
            @else
                <!-- Skenario Pilihan Bebas / Direct Access -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="filter_kategori" class="form-label">Filter Kategori Ruangan</label>
                        <select id="filter_kategori" class="form-select">
                            <option value="ALL">Semua Kategori</option>
                            <option value="KAMAR">Kamar Penginapan (Standar, VIP, Premium)</option>
                            <option value="AULA">Aula Pertemuan</option>
                            <option value="RUANG_MEETING">Ruang Rapat / Meeting</option>
                            <option value="LAINNYA">Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label for="ruangan_id" class="form-label">Pilih Ruangan *</label>
                        <select name="ruangan_id" id="ruangan_id" class="form-select @error('ruangan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @php
                                $grouped = $ruangans->groupBy(function($item) {
                                    if (str_contains($item->tipe_ruangan, 'KAMAR')) return 'Kamar Penginapan';
                                    if ($item->tipe_ruangan === 'AULA') return 'Aula Pertemuan';
                                    if ($item->tipe_ruangan === 'RUANG_MEETING') return 'Ruang Rapat';
                                    return 'Lainnya';
                                });
                            @endphp
                            @foreach($grouped as $groupName => $items)
                                <optgroup label="{{ $groupName }}">
                                    @foreach($items as $item)
                                        <option value="{{ $item->id_ruangan }}" data-tipe="{{ $item->tipe_ruangan }}">
                                            {{ $item->nama_ruangan }} - {{ $item->gedung->nama_gedung ?? '-' }} (Lantai {{ $item->lantai ?? '1' }}, Kapasitas: {{ $item->kapasitas }} Orang)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- AJAX LOADER BOX (INFO DETAIL RUANGAN) -->
            <div id="ajax_details_section" style="display: none;">
                <div class="ruangan-details-card">
                    <div class="detail-item-grid">
                        <div class="detail-item">
                            <div class="detail-label">Nama Ruangan</div>
                            <div class="detail-val" id="detail_nama_ruangan">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Kategori / Tipe</div>
                            <div class="detail-val" id="detail_tipe_ruangan">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Gedung</div>
                            <div class="detail-val" id="detail_gedung">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Kapasitas Maksimal</div>
                            <div class="detail-val" id="detail_kapasitas">-</div>
                        </div>
                    </div>

                    <!-- Booked Dates Warning -->
                    <div class="mt-4" id="booked_dates_container" style="display: none;">
                        <div class="detail-label text-danger mb-2"><i class="fas fa-calendar-times"></i> Jadwal Terbooking (Sudah Dipesan):</div>
                        <div id="booked_dates_list"></div>
                    </div>

                    <!-- Room Gallery -->
                    <div id="room_gallery_container" style="display: none;">
                        <div class="detail-label mt-3"><i class="fas fa-images"></i> Galeri Foto Ruangan:</div>
                        <div class="media-gallery" id="room_gallery_list"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION 2: DETAIL PENYEWAAN & WAKTU -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-calendar-days"></i> 2. Tanggal, Waktu & Paket Sewa</h5>
        </div>
        <div class="card-body p-4">
            
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal Mulai Peminjaman *</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" required disabled>
                    @error('tanggal')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="jam_mulai" class="form-label">Waktu Mulai *</label>
                    <select name="jam_mulai" id="jam_mulai" class="form-select @error('jam_mulai') is-invalid @enderror" required disabled>
                        <option value="">-- Pilih Jam Mulai --</option>
                        @for($hour = 7; $hour <= 21; $hour++)
                            @php
                                $timeStr = sprintf('%02d', $hour) . ':00';
                            @endphp
                            <option value="{{ $timeStr }}" {{ old('jam_mulai') === $timeStr ? 'selected' : '' }}>{{ $timeStr }}</option>
                            @php
                                $timeStrHalf = sprintf('%02d', $hour) . ':30';
                            @endphp
                            @if($hour < 21)
                                <option value="{{ $timeStrHalf }}" {{ old('jam_mulai') === $timeStrHalf ? 'selected' : '' }}>{{ $timeStrHalf }}</option>
                            @endif
                        @endfor
                    </select>
                    @error('jam_mulai')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="paket_id" class="form-label">Pilih Paket Sewa *</label>
                    <select name="paket_id" id="paket_id" class="form-select @error('paket_id') is-invalid @enderror" required disabled>
                        <option value="">-- Pilih Paket (Pilih Ruangan Terlebih Dahulu) --</option>
                    </select>
                    @error('paket_id')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Estimasi Durasi, Selesai, & Biaya -->
            <div id="estimasi_section" style="display: none;">
                <div class="estimasi-box">
                    <h6 class="fw-bold mb-3" style="color: var(--gold-dark);"><i class="fas fa-calculator"></i> Kalkulasi Estimasi Reservasi:</h6>
                    <div class="estimasi-item">
                        <span class="estimasi-label">Paket Sewa Dipilih:</span>
                        <span class="estimasi-val" id="est_nama_paket">-</span>
                    </div>
                    <div class="estimasi-item">
                        <span class="estimasi-label">Durasi Sewa:</span>
                        <span class="estimasi-val" id="est_durasi">-</span>
                    </div>
                    <div class="estimasi-item">
                        <span class="estimasi-label">Waktu Selesai (Sistem):</span>
                        <span class="estimasi-val text-primary" id="est_selesai">-</span>
                    </div>
                    <div class="estimasi-item pt-3 border-top mt-2">
                        <span class="estimasi-label fw-bold">Estimasi Biaya Sewa Ruangan:</span>
                        <span class="estimasi-val price" id="est_harga">-</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION 3: SARANA TAMBAHAN (Dinamis) -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-tools"></i> 3. Peminjaman Sarana Tambahan (Opsional)</h5>
        </div>
        <div class="card-body p-4">
            <div class="info-box">
                <i class="fas fa-circle-info"></i>
                <p>
                    Anda dapat meminjam sarana pendukung (seperti kursi lipat, meja, mikrofon, proyektor) yang tersedia. Jumlah peminjaman akan dibatasi oleh sistem sesuai jumlah stok yang ada di gudang.
                </p>
            </div>

            <div id="sarana_rows_container">
                <!-- Dinamis baris sewa sarana akan dirender di sini via JS -->
            </div>

            <button type="button" class="btn-add-sarana" id="addSaranaBtn">
                <i class="fas fa-plus"></i> Pinjam Sarana Tambahan
            </button>
        </div>
    </div>

    <!-- SECTION 4: DATA KONTAK & KEPERLUAN -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-file-invoice"></i> 4. Detail Kegiatan & Data Kontak</h5>
        </div>
        <div class="card-body p-4">
            
            <div class="form-group">
                <label for="keperluan" class="form-label">Keperluan / Tujuan Penggunaan *</label>
                <textarea id="keperluan" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" rows="4" placeholder="Jelaskan keperluan penggunaan ruangan secara jelas (misalnya: Rapat koordinasi dinas, Diklat pengawas sekolah, dsb.)" required minlength="10" maxlength="500">{{ old('keperluan') }}</textarea>
                <span class="form-text">Minimal 10 karakter, maksimal 500 karakter.</span>
                @error('keperluan')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="estimasi_peserta" class="form-label">Estimasi Jumlah Peserta *</label>
                    <input type="number" name="estimasi_peserta" id="estimasi_peserta" class="form-control @error('estimasi_peserta') is-invalid @enderror" value="{{ old('estimasi_peserta') }}" min="1" placeholder="Contoh: 30" required disabled>
                    <span class="form-text" id="estimasi_peserta_hint">Masukkan jumlah perkiraan peserta.</span>
                    @error('estimasi_peserta')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="kontak_person" class="form-label">Nama Penanggung Jawab / Kontak Person *</label>
                    <input type="text" name="kontak_person" id="kontak_person" class="form-control @error('kontak_person') is-invalid @enderror" value="{{ old('kontak_person', auth()->user()->guest->name ?? auth()->user()->name) }}" placeholder="Nama lengkap penanggung jawab" required>
                    @error('kontak_person')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="no_telepon" class="form-label">Nomor WhatsApp / HP Aktif *</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fas fa-phone-alt"></i></span>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon', auth()->user()->phone) }}" placeholder="Contoh: 081234567890" required>
                </div>
                <span class="form-text">Nomor kontak yang dapat dihubungi oleh admin untuk proses verifikasi lanjutan.</span>
                @error('no_telepon')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

        </div>
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="card">
        <div class="card-body p-4 text-end">
            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                <i class="fas fa-check-circle"></i> Kirim Pengajuan Reservasi Ruangan
            </button>
        </div>
    </div>

</form>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterKategori = document.getElementById('filter_kategori');
    const ruanganSelect = document.getElementById('ruangan_id');
    const tanggalInput = document.getElementById('tanggal');
    const jamMulaiSelect = document.getElementById('jam_mulai');
    const paketSelect = document.getElementById('paket_id');
    const estimasiPesertaInput = document.getElementById('estimasi_peserta');
    const estimasiPesertaHint = document.getElementById('estimasi_peserta_hint');
    const submitBtn = document.getElementById('submitBtn');
    
    // Elements for AJAX display
    const ajaxSection = document.getElementById('ajax_details_section');
    const detNama = document.getElementById('detail_nama_ruangan');
    const detTipe = document.getElementById('detail_tipe_ruangan');
    const detGedung = document.getElementById('detail_gedung');
    const detKapasitas = document.getElementById('detail_kapasitas');
    const bookedContainer = document.getElementById('booked_dates_container');
    const bookedList = document.getElementById('booked_dates_list');
    const galleryContainer = document.getElementById('room_gallery_container');
    const galleryList = document.getElementById('room_gallery_list');
    
    // Estimasi Box Elements
    const estSection = document.getElementById('estimasi_section');
    const estNama = document.getElementById('est_nama_paket');
    const estDurasi = document.getElementById('est_durasi');
    const estSelesai = document.getElementById('est_selesai');
    const estHarga = document.getElementById('est_harga');

    // Global references
    let currentRoomId = null;
    let selectedRoomMaxCapacity = 1;
    let activePackages = [];
    let bookedRanges = [];
    let saranaCounter = 0;

    // List of sarana options compiled from PHP
    const saranaList = [
        @foreach($saranas as $s)
        { id: {{ $s->id }}, nama: "{{ $s->nama }}", stok: {{ $s->stok }}, kondisi: "{{ $s->kondisi }}" },
        @endforeach
    ];

    // Set Date min to today
    const todayStr = new Date().toISOString().split('T')[0];
    if (tanggalInput) {
        tanggalInput.min = todayStr;
    }

    // 1. FILTER KATEGORI RUANGAN (Untuk Skenario Bebas)
    if (filterKategori) {
        filterKategori.addEventListener('change', function() {
            const val = this.value;
            const optgroups = ruanganSelect.querySelectorAll('optgroup');
            const options = ruanganSelect.querySelectorAll('option');
            
            ruanganSelect.value = '';
            tanggalInput.disabled = true;
            jamMulaiSelect.disabled = true;
            paketSelect.disabled = true;
            estimasiPesertaInput.disabled = true;
            submitBtn.disabled = true;
            ajaxSection.style.display = 'none';
            estSection.style.display = 'none';
            
            optgroups.forEach(group => {
                const label = group.label.toUpperCase();
                let showGroup = false;

                const childOpts = group.querySelectorAll('option');
                childOpts.forEach(opt => {
                    const tipe = opt.getAttribute('data-tipe');
                    
                    let showOpt = false;
                    if (val === 'ALL') {
                        showOpt = true;
                    } else if (val === 'KAMAR' && tipe.includes('KAMAR')) {
                        showOpt = true;
                    } else if (val === 'AULA' && tipe === 'AULA') {
                        showOpt = true;
                    } else if (val === 'RUANG_MEETING' && tipe === 'RUANG_MEETING') {
                        showOpt = true;
                    } else if (val === 'LAINNYA' && !tipe.includes('KAMAR') && tipe !== 'AULA' && tipe !== 'RUANG_MEETING') {
                        showOpt = true;
                    }

                    if (showOpt) {
                        opt.style.display = '';
                        showGroup = true;
                    } else {
                        opt.style.display = 'none';
                    }
                });

                if (showGroup) {
                    group.style.display = '';
                } else {
                    group.style.display = 'none';
                }
            });
        });
    }

    // 2. DETEKSI PERUBAHAN RUANGAN TERPILIH (AJAX LOADER)
    function handleRoomChange(roomId) {
        if (!roomId) {
            ajaxSection.style.display = 'none';
            tanggalInput.disabled = true;
            jamMulaiSelect.disabled = true;
            paketSelect.disabled = true;
            estimasiPesertaInput.disabled = true;
            submitBtn.disabled = true;
            estSection.style.display = 'none';
            return;
        }

        // Fetch details from AJAX endpoint
        const ajaxUrl = `/users/main/ruangan/${roomId}/details`;
        
        fetch(ajaxUrl)
            .then(res => res.json())
            .then(data => {
                currentRoomId = data.id_ruangan;
                selectedRoomMaxCapacity = data.kapasitas;
                activePackages = data.packages;
                bookedRanges = data.booked;

                // Fill detail cards
                detNama.textContent = data.nama_ruangan;
                detTipe.textContent = data.tipe_ruangan;
                detGedung.textContent = data.gedung;
                detKapasitas.textContent = data.kapasitas + ' orang';
                ajaxSection.style.display = 'block';

                // Fill pre-reservations booked dates
                bookedList.innerHTML = '';
                if (data.booked && data.booked.length > 0) {
                    data.booked.forEach(range => {
                        const badge = document.createElement('span');
                        badge.className = 'booked-badge';
                        badge.innerHTML = `<i class="fas fa-user-lock"></i> ${range.label}`;
                        bookedList.appendChild(badge);
                    });
                    bookedContainer.style.display = 'block';
                } else {
                    bookedContainer.style.display = 'none';
                }

                // Fill room photos gallery
                galleryList.innerHTML = '';
                if (data.photos && data.photos.length > 0) {
                    data.photos.forEach(photo => {
                        const item = document.createElement('div');
                        item.className = 'media-item';
                        item.innerHTML = `<img src="/${photo.path}" alt="Foto Ruangan">`;
                        galleryList.appendChild(item);
                    });
                    galleryContainer.style.display = 'block';
                } else {
                    galleryContainer.style.display = 'none';
                }

                // Populate packages dropdown
                paketSelect.innerHTML = '<option value="">-- Pilih Paket Sewa --</option>';
                if (data.packages && data.packages.length > 0) {
                    data.packages.forEach(pkg => {
                        const opt = document.createElement('option');
                        opt.value = pkg.id;
                        opt.textContent = `${pkg.nama_paket} - Rp ${formatRupiah(pkg.harga)} (${pkg.durasi} Jam)`;
                        paketSelect.appendChild(opt);
                    });
                }
                
                // Enable other inputs
                tanggalInput.disabled = false;
                jamMulaiSelect.disabled = false;
                paketSelect.disabled = false;
                estimasiPesertaInput.disabled = false;
                estimasiPesertaInput.max = data.kapasitas;
                estimasiPesertaHint.textContent = `Maksimal kapasitas ruangan: ${data.kapasitas} orang.`;
                submitBtn.disabled = false;

                // Re-calculate calculations if package or time has been pre-selected
                calculateEstimasi();
            })
            .catch(err => {
                console.error("Gagal memuat detail ruangan via AJAX", err);
                alert("Gagal memuat detail data ruangan.");
            });
    }

    // Bind room selector
    if (ruanganSelect) {
        ruanganSelect.addEventListener('change', function() {
            handleRoomChange(this.value);
        });

        // Trigger on load if pre-selected
        if (ruanganSelect.value) {
            handleRoomChange(ruanganSelect.value);
        }
    } else {
        // Pre-selected scenario where ruangan_id is hidden
        const hiddenRoomId = document.getElementById('ruangan_id');
        if (hiddenRoomId && hiddenRoomId.value) {
            handleRoomChange(hiddenRoomId.value);
        }
    }

    // 3. KALKULASI ESTIMASI DURASI & BIAYA
    function calculateEstimasi() {
        const pId = paketSelect.value;
        const tgl = tanggalInput.value;
        const jam = jamMulaiSelect.value;

        if (!pId || !tgl || !jam) {
            estSection.style.display = 'none';
            return;
        }

        const selectedPkg = activePackages.find(p => p.id == pId);
        if (!selectedPkg) return;

        // Calculate estimated end datetime
        const startDt = new Date(tgl + 'T' + jam);
        const endDt = new Date(startDt.getTime() + selectedPkg.durasi * 60 * 60 * 1000);

        // Format dates
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        const dayName = days[endDt.getDay()];
        const dateNum = String(endDt.getDate()).padStart(2, '0');
        const monthName = months[endDt.getMonth()];
        const year = endDt.getFullYear();
        const timeStr = String(endDt.getHours()).padStart(2, '0') + ':' + String(endDt.getMinutes()).padStart(2, '0');

        const estSelesaiLabel = `${dayName}, ${dateNum} ${monthName} ${year} pukul ${timeStr} WITA`;

        // Render calculations
        estNama.textContent = selectedPkg.nama_paket;
        estDurasi.textContent = selectedPkg.durasi + ' Jam';
        estSelesai.textContent = estSelesaiLabel;
        estHarga.textContent = 'Rp ' + formatRupiah(selectedPkg.harga);
        
        estSection.style.display = 'block';

        // Check if selected datetime overlaps with booked dates
        checkDateOverlap(startDt, endDt);
    }

    // Bind event listeners for calculation
    paketSelect.addEventListener('change', calculateEstimasi);
    tanggalInput.addEventListener('change', calculateEstimasi);
    jamMulaiSelect.addEventListener('change', calculateEstimasi);

    // 4. VERIFIKASI OVERLAPPING TANGGAL
    function checkDateOverlap(requestStart, requestEnd) {
        if (!bookedRanges || bookedRanges.length === 0) return;

        let isOverlap = false;
        let overlapLabel = '';

        for (let i = 0; i < bookedRanges.length; i++) {
            const bookedStart = new Date(bookedRanges[i].start.replace(' ', 'T'));
            const bookedEnd = new Date(bookedRanges[i].end.replace(' ', 'T'));

            // Check overlap: start < bookedEnd AND end > bookedStart
            if (requestStart < bookedEnd && requestEnd > bookedStart) {
                isOverlap = true;
                overlapLabel = bookedRanges[i].label;
                break;
            }
        }

        if (isOverlap) {
            alert(`⚠️ JADWAL BENTROK!\n\nRuangan ini sudah terbooking oleh pengguna lain pada jadwal:\n${overlapLabel}\n\nSilakan tentukan tanggal atau jam mulai lainnya!`);
            tanggalInput.value = '';
            estSection.style.display = 'none';
        }
    }

    // 5. PEMINJAMAN SARANA TAMBAHAN SECARA DINAMIS
    const saranaContainer = document.getElementById('sarana_rows_container');
    const addSaranaBtn = document.getElementById('addSaranaBtn');

    addSaranaBtn.addEventListener('click', function() {
        saranaCounter++;
        
        const row = document.createElement('div');
        row.className = 'sarana-row';
        row.id = `sarana_row_${saranaCounter}`;

        let selectOptions = '<option value="">-- Pilih Sarana --</option>';
        saranaList.forEach(s => {
            selectOptions += `<option value="${s.id}" data-stok="${s.stok}">[Stok: ${s.stok} unit] ${s.nama} (${s.kondisi})</option>`;
        });

        row.innerHTML = `
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Pilih Sarana *</label>
                    <select name="sarana[${saranaCounter}][sarana_id]" class="form-select sarana-id-select" required>
                        ${selectOptions}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Sewa *</label>
                    <input type="number" name="sarana[${saranaCounter}][jumlah]" class="form-control sarana-jumlah-input" min="1" placeholder="Jumlah sewa" required disabled>
                    <span class="form-text sarana-stok-hint">Pilih sarana terlebih dahulu.</span>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn-remove-sarana" onclick="removeSaranaRow(${saranaCounter})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;

        saranaContainer.appendChild(row);

        // Bind event change on the newly created select dropdown
        const select = row.querySelector('.sarana-id-select');
        const jumlahInput = row.querySelector('.sarana-jumlah-input');
        const hint = row.querySelector('.sarana-stok-hint');

        select.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const maxStok = parseInt(opt.getAttribute('data-stok') || 0);

            if (this.value) {
                jumlahInput.disabled = false;
                jumlahInput.max = maxStok;
                jumlahInput.value = 1;
                hint.textContent = `Tersedia di gudang: ${maxStok} unit.`;
            } else {
                jumlahInput.disabled = true;
                jumlahInput.value = '';
                hint.textContent = 'Pilih sarana terlebih dahulu.';
            }
        });

        // Bind validation on quantity input
        jumlahInput.addEventListener('change', function() {
            const max = parseInt(this.max || 0);
            const val = parseInt(this.value || 0);
            if (val > max) {
                alert(`Peringatan: Jumlah sewa melebihi stok yang tersedia (${max} unit)!`);
                this.value = max;
            }
        });
    });

    // Global function to remove dynamic sarana row
    window.removeSaranaRow = function(id) {
        const row = document.getElementById(`sarana_row_${id}`);
        if (row) {
            row.remove();
        }
    };

    // Helper functions
    function formatRupiah(num) {
        return parseFloat(num).toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }

    // 6. VALIDASI KAPASITAS PESERTA
    estimasiPesertaInput.addEventListener('change', function() {
        const val = parseInt(this.value || 0);
        if (val > selectedRoomMaxCapacity) {
            alert(`⚠️ KAPASITAS MELEBIHI BATAS!\n\nJumlah peserta (${val} orang) tidak boleh melebihi kapasitas ruangan terpilih (${selectedRoomMaxCapacity} orang).`);
            this.value = selectedRoomMaxCapacity;
        }
    });
});
</script>
@endsection
