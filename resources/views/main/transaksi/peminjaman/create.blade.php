@extends('main.layout.app')

@section('title', 'Form Tambah Reservasi Tamu')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Back Button & Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold" style="color: #333;">
            <i class="fas fa-calendar-plus me-2" style="color: #C9A961;"></i> Form Tambah Reservasi Tamu
        </h5>
        <a href="{{ route('main.transaksi.peminjaman.index') }}" class="btn btn-secondary btn-sm" style="padding: 6px 15px; font-size: 13px;">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="font-size: 14px;">
        <div class="fw-bold mb-1">
            <i class="fas fa-exclamation-circle me-2"></i>Gagal mengirim formulir. Harap periksa beberapa kesalahan di bawah:
        </div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('main.transaksi.peminjaman.store') }}" method="POST" id="reservasiForm">
        @csrf

        <!-- SECTION 1: VERIFIKASI TAMU (GUEST) -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header text-white" style="background-color: #C9A961; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                    <i class="fas fa-id-card me-2"></i>1. Verifikasi Data Tamu (Guest)
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label for="nik" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Masukkan NIK Tamu *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fas fa-key"></i></span>
                            <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="Contoh: 1234567890123456" minlength="16" maxlength="16" required style="font-size: 14px;">
                            <button class="btn btn-warning text-white fw-semibold" type="button" id="btnCekNik" style="font-size: 13px;">
                                <i class="fas fa-search me-1"></i> Cek NIK
                            </button>
                        </div>
                        @error('nik')
                            <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                        @enderror
                        <span class="form-text text-muted small d-block mt-1">Masukkan 16 digit NIK tamu yang sudah didaftarkan.</span>
                    </div>
                    <div class="col-md-6">
                        <div id="nik_status_container" class="p-3 rounded" style="display: none; font-size: 14px; min-height: 58px;">
                            <div class="d-flex align-items-center h-100" id="nik_status_content">
                                <!-- Status content populated by JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="form_peminjaman_wrapper" style="opacity: 0.5; pointer-events: none;">
            <!-- SECTION 2: PEMILIHAN RUANGAN -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header text-white" style="background-color: #C9A961; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-door-open me-2"></i>2. Pilih Ruangan & Fasilitas
                    </h6>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Pemilihan Ruangan & Filter Kategori -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="filter_kategori" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Filter Kategori Ruangan</label>
                            <select id="filter_kategori" class="form-select" style="font-size: 14px;">
                                <option value="ALL">Semua Kategori</option>
                                <option value="KAMAR">Kamar Penginapan (Standar, VIP, Premium)</option>
                                <option value="AULA">Aula Pertemuan</option>
                                <option value="RUANG_MEETING">Ruang Rapat / Meeting</option>
                                <option value="LAINNYA">Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="ruangan_id" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Pilih Ruangan *</label>
                            <select name="ruangan_id" id="ruangan_id" class="form-select @error('ruangan_id') is-invalid @enderror" required style="font-size: 14px;">
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
                                            <option value="{{ $item->id_ruangan }}" data-tipe="{{ $item->tipe_ruangan }}" {{ old('ruangan_id') == $item->id_ruangan ? 'selected' : '' }}>
                                                {{ $item->nama_ruangan }} - {{ $item->gedung->nama_gedung ?? '-' }} (Lantai {{ $item->lantai ?? '1' }}, Kapasitas: {{ $item->kapasitas }} Orang)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- AJAX LOADER BOX (INFO DETAIL RUANGAN) -->
                    <div id="ajax_details_section" style="display: none;">
                        <div class="p-3 bg-light rounded border-start border-warning border-4 mb-3">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Ruangan</label>
                                    <div class="fw-semibold text-dark" style="font-size: 14px;" id="detail_nama_ruangan">-</div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kategori / Tipe</label>
                                    <div class="fw-semibold text-dark" style="font-size: 14px;" id="detail_tipe_ruangan">-</div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Gedung</label>
                                    <div class="fw-semibold text-dark" style="font-size: 14px;" id="detail_gedung">-</div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kapasitas Maksimal</label>
                                    <div class="fw-semibold text-dark" style="font-size: 14px;" id="detail_kapasitas">-</div>
                                </div>
                            </div>

                            <!-- Booked Dates Warning -->
                            <div class="mt-3" id="booked_dates_container" style="display: none;">
                                <label class="text-danger small fw-semibold text-uppercase d-block mb-2" style="font-size: 11px;">
                                    <i class="fas fa-calendar-times"></i> Jadwal Terbooking (Sudah Dipesan):
                                </label>
                                <div id="booked_dates_list" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SECTION 3: DETAIL TANGGAL & WAKTU -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header text-white" style="background-color: #C9A961; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-calendar-alt"></i> 3. Tanggal, Waktu & Paket Sewa
                    </h6>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Tanggal Mulai Peminjaman *</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" required style="font-size: 14px;">
                            @error('tanggal')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="jam_mulai" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Waktu Mulai *</label>
                            <select name="jam_mulai" id="jam_mulai" class="form-select @error('jam_mulai') is-invalid @enderror" required style="font-size: 14px;">
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
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="paket_id" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Pilih Paket Sewa *</label>
                            <select name="paket_id" id="paket_id" class="form-select @error('paket_id') is-invalid @enderror" required style="font-size: 14px;">
                                <option value="">-- Pilih Paket (Pilih Ruangan Terlebih Dahulu) --</option>
                            </select>
                            @error('paket_id')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>

                    <!-- Estimasi Durasi, Selesai, & Biaya -->
                    <div id="estimasi_section" style="display: none;">
                        <div class="p-3 bg-light rounded border-start border-info border-4 mt-3">
                            <h6 class="fw-bold mb-3" style="color: #B8953F; font-size: 14px;">
                                <i class="fas fa-calculator me-1"></i>Kalkulasi Estimasi Reservasi:
                            </h6>
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Paket Sewa Dipilih:</span>
                                    <span class="fw-bold text-dark" id="est_nama_paket">-</span>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Durasi Sewa:</span>
                                    <span class="fw-bold text-dark" id="est_durasi">-</span>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Waktu Selesai (Sistem):</span>
                                    <span class="fw-bold text-primary" id="est_selesai">-</span>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Estimasi Biaya Ruangan:</span>
                                    <span class="fw-bold text-success fs-5" id="est_harga">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SECTION 4: PEMINJAMAN SARANA PENDUKUNG -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header text-white" style="background-color: #C9A961; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-tools me-2"></i>4. Peminjaman Sarana Tambahan (Opsional)
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if ($errors->has('sarana') || collect($errors->keys())->contains(fn($k) => str_starts_with($k, 'sarana')))
                        <div class="alert alert-danger py-2 px-3 mb-3 small">
                            <i class="fas fa-exclamation-circle me-1"></i> Terjadi kesalahan pada inputan sarana tambahan. Pastikan sarana yang dipilih valid dan jumlah sewa tidak melebihi stok yang tersedia.
                        </div>
                    @endif
                    <div id="sarana_rows_container">
                        <!-- Dinamis sewa sarana akan dirender di sini via JS -->
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold mt-2" id="addSaranaBtn" style="font-size: 13px;">
                        <i class="fas fa-plus me-1"></i> Pinjam Sarana Tambahan
                    </button>
                </div>
            </div>

            <!-- SECTION 5: DATA KEPERLUAN & PESERTA -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header text-white" style="background-color: #C9A961; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-info-circle me-2"></i>5. Detail Kegiatan & Keperluan
                    </h6>
                </div>
                <div class="card-body p-4">
                    
                    <div class="mb-3">
                        <label for="keperluan" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Keperluan / Tujuan Penggunaan *</label>
                        <textarea id="keperluan" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" rows="4" placeholder="Jelaskan keperluan penggunaan ruangan secara jelas (misalnya: Rapat koordinasi dinas, Diklat pengawas sekolah, dsb.)" required minlength="10" maxlength="500" style="font-size: 14px;">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                        <span class="form-text text-muted small d-block mt-1">Minimal 10 karakter, maksimal 500 karakter.</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="estimasi_peserta" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Estimasi Jumlah Peserta *</label>
                            <input type="number" name="estimasi_peserta" id="estimasi_peserta" class="form-control @error('estimasi_peserta') is-invalid @enderror" value="{{ old('estimasi_peserta') }}" min="1" placeholder="Contoh: 30" required style="font-size: 14px;">
                            @error('estimasi_peserta')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                            <span class="form-text text-muted small d-block mt-1" id="estimasi_peserta_hint">Masukkan jumlah perkiraan peserta.</span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Status Approval Baru</label>
                            <input type="text" class="form-control text-success fw-bold bg-light" value="APPROVED (Otomatis)" disabled style="font-size: 14px;">
                            <span class="form-text text-muted small d-block mt-1">Transaksi langsung disetujui karena dibuat oleh petugas.</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4 text-end">
                    <button type="submit" class="btn btn-success btn-lg px-5 fw-bold" id="submitBtn" style="font-size: 15px;">
                        <i class="fas fa-check-circle me-1"></i> Simpan & Setujui Reservasi
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCekNik = document.getElementById('btnCekNik');
    const nikInput = document.getElementById('nik');
    const statusContainer = document.getElementById('nik_status_container');
    const statusContent = document.getElementById('nik_status_content');
    const formWrapper = document.getElementById('form_peminjaman_wrapper');

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

    // 1. AJAX VERIFIKASI NIK TAMU
    function verifikasiNik() {
        const nik = nikInput.value.trim();
        
        if (nik.length !== 16 || isNaN(nik)) {
            showNikStatus('error', '<i class="fas fa-times-circle me-2 text-danger"></i> NIK harus terdiri dari 16 digit angka.');
            disableReservationForm();
            return;
        }

        btnCekNik.disabled = true;
        btnCekNik.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengecek...';

        fetch(`/admin/transaksi/peminjaman/guest/check/${nik}`)
            .then(res => res.json())
            .then(data => {
                btnCekNik.disabled = false;
                btnCekNik.innerHTML = '<i class="fas fa-search me-1"></i> Cek NIK';

                if (data.success) {
                    showNikStatus('success', `<i class="fas fa-check-circle me-2 text-success"></i> Tamu ditemukan: <strong>${data.guest.name}</strong> (${data.guest.gender === 'MALE' ? 'Laki-laki' : 'Perempuan'})`);
                    enableReservationForm();
                } else {
                    showNikStatus('error', `<i class="fas fa-exclamation-circle me-2 text-danger"></i> ${data.message}`);
                    disableReservationForm();
                }
            })
            .catch(err => {
                btnCekNik.disabled = false;
                btnCekNik.innerHTML = '<i class="fas fa-search me-1"></i> Cek NIK';
                console.error(err);
                showNikStatus('error', '<i class="fas fa-times-circle me-2 text-danger"></i> Terjadi kesalahan saat memeriksa NIK.');
                disableReservationForm();
            });
    }

    btnCekNik.addEventListener('click', verifikasiNik);

    // Auto check if typed 16 characters
    nikInput.addEventListener('keyup', function() {
        if (this.value.length === 16) {
            verifikasiNik();
        }
    });

    function showNikStatus(type, htmlContent) {
        statusContainer.style.display = 'block';
        statusContent.innerHTML = htmlContent;

        if (type === 'success') {
            statusContainer.style.backgroundColor = '#d1e7dd';
            statusContainer.style.color = '#0f5132';
            statusContainer.style.border = '1px solid #badbcc';
        } else {
            statusContainer.style.backgroundColor = '#f8d7da';
            statusContainer.style.color = '#842029';
            statusContainer.style.border = '1px solid #f5c2c7';
        }
    }

    function enableReservationForm() {
        formWrapper.style.opacity = '1';
        formWrapper.style.pointerEvents = 'auto';
    }

    function disableReservationForm() {
        formWrapper.style.opacity = '0.5';
        formWrapper.style.pointerEvents = 'none';
    }

    // 2. FILTER KATEGORI RUANGAN
    if (filterKategori) {
        filterKategori.addEventListener('change', function() {
            const val = this.value;
            const optgroups = ruanganSelect.querySelectorAll('optgroup');
            
            ruanganSelect.value = '';
            ajaxSection.style.display = 'none';
            estSection.style.display = 'none';
            paketSelect.innerHTML = '<option value="">-- Pilih Paket (Pilih Ruangan Terlebih Dahulu) --</option>';
            
            optgroups.forEach(group => {
                const childOpts = group.querySelectorAll('option');
                let showGroup = false;

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

    // 3. DETEKSI PERUBAHAN RUANGAN TERPILIH (AJAX LOADER)
    function handleRoomChange(roomId) {
        if (!roomId) {
            ajaxSection.style.display = 'none';
            estSection.style.display = 'none';
            paketSelect.innerHTML = '<option value="">-- Pilih Paket (Pilih Ruangan Terlebih Dahulu) --</option>';
            return;
        }

        const ajaxUrl = `/admin/transaksi/peminjaman/ruangan/${roomId}/details`;
        
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
                        badge.className = 'badge bg-danger text-white border px-2 py-1 mb-1 me-1';
                        badge.innerHTML = `<i class="fas fa-lock me-1"></i> ${range.label}`;
                        bookedList.appendChild(badge);
                    });
                    bookedContainer.style.display = 'block';
                } else {
                    bookedContainer.style.display = 'none';
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
                
                estimasiPesertaInput.max = data.kapasitas;
                estimasiPesertaHint.textContent = `Maksimal kapasitas ruangan: ${data.kapasitas} orang.`;

                // Re-calculate calculations if package or time has been pre-selected
                calculateEstimasi();
            })
            .catch(err => {
                console.error("Gagal memuat detail ruangan via AJAX", err);
                alert("Gagal memuat detail data ruangan.");
            });
    }

    if (ruanganSelect) {
        ruanganSelect.addEventListener('change', function() {
            handleRoomChange(this.value);
        });
    }

    // 4. KALKULASI ESTIMASI DURASI & BIAYA
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

        const estSelesaiLabel = `${dayName}, ${dateNum} ${monthName} ${year} pukul ${timeStr} WIB`;

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

    // 5. VERIFIKASI OVERLAPPING TANGGAL
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

    // 6. PEMINJAMAN SARANA TAMBAHAN SECARA DINAMIS
    const saranaContainer = document.getElementById('sarana_rows_container');
    const addSaranaBtn = document.getElementById('addSaranaBtn');

    addSaranaBtn.addEventListener('click', function() {
        saranaCounter++;
        
        const row = document.createElement('div');
        row.className = 'p-3 bg-light rounded border border-secondary border-opacity-10 mb-3';
        row.id = `sarana_row_${saranaCounter}`;

        let selectOptions = '<option value="">-- Pilih Sarana --</option>';
        saranaList.forEach(s => {
            selectOptions += `<option value="${s.id}" data-stok="${s.stok}">[Stok: ${s.stok} unit] ${s.nama} (${s.kondisi})</option>`;
        });

        row.innerHTML = `
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Pilih Sarana *</label>
                    <select name="sarana[${saranaCounter}][sarana_id]" class="form-select sarana-id-select" required style="font-size: 14px;">
                        ${selectOptions}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Jumlah Sewa *</label>
                    <input type="number" name="sarana[${saranaCounter}][jumlah]" class="form-control sarana-jumlah-input" min="1" placeholder="Jumlah sewa" required disabled style="font-size: 14px;">
                    <span class="form-text text-muted small sarana-stok-hint">Pilih sarana terlebih dahulu.</span>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSaranaRow(${saranaCounter})" style="font-size: 13px;">
                        <i class="fas fa-trash me-1"></i> Hapus
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
                hint.textContent = `Tersedia: ${maxStok} unit.`;
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

    // 7. VALIDASI KAPASITAS PESERTA
    estimasiPesertaInput.addEventListener('change', function() {
        const val = parseInt(this.value || 0);
        if (val > selectedRoomMaxCapacity) {
            this.value = selectedRoomMaxCapacity;
        }
    });
});
</script>
@endpush
