@extends('users.layout.app')

@section('title', 'Dashboard Pengguna')

@section('css')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #C9A961 0%, #A48135 100%);
        border-radius: 16px;
        color: white;
        padding: 40px;
        margin-bottom: 35px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(201, 169, 97, 0.2);
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        z-index: 1;
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        right: 10%;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        z-index: 1;
    }

    .welcome-content {
        position: relative;
        z-index: 2;
        max-width: 70%;
    }

    .welcome-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }

    .welcome-subtitle {
        font-size: 16px;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
    }

    .stat-card {
        border: none;
        border-radius: 16px;
        color: white;
        min-height: 160px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        text-decoration: none !important;
        display: block;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        right: -15px;
        bottom: -15px;
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 110px;
        opacity: 0.12;
        transition: all 0.3s ease;
    }

    .card-gedung::after { content: '\f1ad'; }
    .card-ruangan::after { content: '\f52b'; }
    .card-sarana::after { content: '\f7d9'; }

    .stat-card:hover::after {
        transform: scale(1.1) rotate(-10deg);
        opacity: 0.18;
    }

    .stat-body {
        padding: 28px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        z-index: 2;
    }

    .stat-number {
        font-size: 44px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
        letter-spacing: -1px;
    }

    .stat-label {
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    .action-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        padding: 35px;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.03);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        border-color: rgba(201, 169, 97, 0.3);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .action-card:hover .action-icon {
        transform: scale(1.1);
    }

    .icon-profile {
        background-color: rgba(201, 169, 97, 0.12);
        color: #A48135;
    }

    .icon-reservasi {
        background-color: rgba(40, 167, 69, 0.12);
        color: #28a745;
    }

    .icon-ketersediaan {
        background-color: rgba(0, 123, 255, 0.12);
        color: #007bff;
    }

    .action-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .action-desc {
        font-size: 14px;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 25px;
        min-height: 66px;
    }

    .btn-action-card {
        padding: 10px 22px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-profile {
        background-color: #C9A961;
        color: white;
    }

    .btn-profile:hover {
        background-color: #A48135;
        color: white;
        box-shadow: 0 4px 12px rgba(201, 169, 97, 0.3);
    }

    .btn-reservasi {
        background-color: #28a745;
        color: white;
    }

    .btn-reservasi:hover {
        background-color: #218838;
        color: white;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-ketersediaan {
        background-color: #007bff;
        color: white;
    }

    .btn-ketersediaan:hover {
        background-color: #0056b3;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    /* ===== CALENDAR SYSTEM ===== */
    .transition-all-layout {
        transition: all 0.35s ease-in-out;
    }
    .calendar-weekdays-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-weight: 700;
        color: #718096;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .calendar-days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }
    .calendar-day-cell {
        aspect-ratio: 1.1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: 8px 4px;
        border-radius: 8px;
        cursor: pointer;
        position: relative;
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        transition: all 0.2s ease-in-out;
        border: 1px solid transparent;
        background-color: #f8fafc;
    }
    .calendar-day-cell:hover {
        background-color: #edf2f7;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .calendar-day-cell.other-month {
        color: #a0aec0;
        background-color: #fafbfc;
        opacity: 0.5;
    }
    .calendar-day-cell.today {
        background-color: #ebf8ff;
        border-color: #3182ce;
        color: #2b6cb0;
        box-shadow: inset 0 0 0 1px #3182ce;
    }
    .calendar-day-cell.selected {
        background-color: #e2e8f0;
        border-color: #4a5568;
    }
    .calendar-day-cell .day-number {
        line-height: 1;
    }
    .calendar-day-cell .indicators {
        display: flex;
        justify-content: center;
        gap: 3px;
        width: 100%;
    }
    .indicator-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .indicator-dot.pending {
        background-color: #ecc94b;
    }
    .indicator-dot.approved {
        background-color: #48bb78;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" style="padding-left: 30px; padding-right: 30px;">
    
    <!-- Header Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Dashboard Utama</h4>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="welcome-banner mb-4">
        <div class="welcome-content">
            <h1 class="welcome-title">Selamat Datang, {{ Auth::user()->name ?? 'Pengguna' }}!</h1>
            <p class="welcome-subtitle">
                Di portal layanan SIPRASA (Sistem Informasi Peminjaman Sarana & Prasarana). Kami berkomitmen memberikan kemudahan bagi Anda untuk mengelola profil pribadi, menelusuri ketersediaan gedung, ruangan, sarana pendukung, hingga melakukan pengajuan reservasi dengan cepat dan akurat.
            </p>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Card Ruangan -->
        <div class="col-md-6">
            <a href="{{ route('users.main.ruangan.index') }}" class="stat-card card-ruangan" style="background: linear-gradient(135deg, #C9A961 0%, #856404 100%);">
                <div class="stat-body">
                    <div>
                        <div class="stat-number">{{ $rooms }}</div>
                        <div class="stat-label">Jumlah Ruangan</div>
                    </div>
                    <div style="font-size: 13px; opacity: 0.8; font-weight: 500;">
                        Lihat Daftar Ruangan <i class="fas fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Sarana -->
        <div class="col-md-6">
            <a href="{{ route('users.main.sarana.index') }}" class="stat-card card-sarana" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="stat-body">
                    <div>
                        <div class="stat-number">{{ $saranas }}</div>
                        <div class="stat-label">Jumlah Sarana</div>
                    </div>
                    <div style="font-size: 13px; opacity: 0.8; font-weight: 500;">
                        Lihat Daftar Sarana <i class="fas fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Row: Kalender & Ketersediaan Ruangan -->
    <div class="row mb-5">
        <!-- Kolom Kiri: Kalender -->
        <div class="col-xl-5 col-lg-5 col-md-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background: #fff; border: 1px solid #eef2f5 !important; overflow: hidden; min-height: 480px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark" id="calendarMonthYear" style="font-family: 'Outfit', sans-serif; font-size: 15px;"></h6>
                        <div class="d-flex gap-1">
                            <button class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center" id="prevMonthBtn" style="width: 28px; height: 28px; border: 1px solid #dee2e6; padding: 0;">
                                <i class="fas fa-chevron-left text-secondary" style="font-size: 10px;"></i>
                            </button>
                            <button class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center" id="nextMonthBtn" style="width: 28px; height: 28px; border: 1px solid #dee2e6; padding: 0;">
                                <i class="fas fa-chevron-right text-secondary" style="font-size: 10px;"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Weekdays Grid -->
                    <div class="calendar-weekdays-grid mb-2">
                        <div>Min</div>
                        <div>Sen</div>
                        <div>Sel</div>
                        <div>Rab</div>
                        <div>Kam</div>
                        <div>Jum</div>
                        <div>Sab</div>
                    </div>

                    <!-- Days Grid -->
                    <div class="calendar-days-grid" id="calendarDaysContainer">
                        <!-- Days will be loaded here dynamically -->
                    </div>
                    
                    <!-- Petunjuk Indikator -->
                    <div class="mt-3 pt-2 border-top d-flex justify-content-between" style="font-size: 11px;">
                        <span class="d-flex align-items-center">
                            <span class="indicator-dot pending me-1"></span> Diajukan
                        </span>
                        <span class="d-flex align-items-center">
                            <span class="indicator-dot approved me-1"></span> Disetujui
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Ketersediaan (Terbooking di atas, Tersedia di bawah) -->
        <div class="col-xl-7 col-lg-7 col-md-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background: #fff; border: 1px solid #eef2f5 !important; overflow: hidden; min-height: 480px;">
                <div class="card-body p-3 d-flex flex-column" style="height: 100%;">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-family: 'Outfit', sans-serif; font-size: 15px;">
                        <i class="fas fa-info-circle text-primary me-2"></i>Jadwal Sewa: <span id="selectedDateLabel" class="text-secondary fw-semibold">Pilih Tanggal</span>
                    </h6>
                    
                    <div class="d-flex flex-column gap-3 overflow-auto flex-grow-1" id="availabilityDetailsContainer" style="max-height: 400px; padding-right: 5px;">
                        <!-- Default Placeholder -->
                        <div class="text-center text-muted py-5 my-auto" id="defaultPlaceholder">
                            <i class="far fa-calendar-check fa-2x mb-3" style="color: #ddd;"></i>
                            <p class="mb-0" style="font-size: 13px;">Pilih tanggal dengan indikator untuk melihat rincian.</p>
                        </div>
                        
                        <!-- Seksi Terbooking (Booked Rooms) - Stacked Top -->
                        <div id="sectionBooked" style="display: none;">
                            <div class="fw-bold text-secondary mb-2 small text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">
                                <i class="fas fa-user-lock text-danger me-1"></i> Ruangan Terbooking
                            </div>
                            <div class="d-flex flex-column gap-2" id="selectedDateReservations">
                                <!-- Booked rooms render here -->
                            </div>
                        </div>

                        <!-- Seksi Tersedia (Available Rooms) - Stacked Bottom -->
                        <div id="sectionAvailable" style="display: none;">
                            <div class="fw-bold text-secondary mb-2 mt-2 small text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">
                                <i class="fas fa-check-circle text-success me-1"></i> Ruangan Tersedia
                            </div>
                            <div class="d-flex flex-column gap-2" id="selectedDateAvailability">
                                <!-- Available rooms render here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Action: Kelola Profil -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="action-card">
                <div class="action-icon icon-profile">
                    <i class="fas fa-user-gear"></i>
                </div>
                <h4 class="action-title">Kelola Profil Peminjam</h4>
                <p class="action-desc">
                    Lengkapi atau perbarui informasi data diri Anda seperti Nomor NIK, Nama Lengkap, Alamat, Golongan Darah, hingga pengaturan kata sandi baru untuk menjaga keamanan akun Anda.
                </p>
                <div>
                    <a href="{{ route('users.profil.edit') }}" class="btn-action-card btn-profile">
                        <i class="fas fa-sliders me-1"></i> Pengaturan Profil Saya
                    </a>
                </div>
            </div>
        </div>

        <!-- Action: Reservasi Ruangan -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="action-card">
                <div class="action-icon icon-reservasi">
                    <i class="fas fa-calendar-days"></i>
                </div>
                <h4 class="action-title">Mulai Reservasi Ruangan</h4>
                <p class="action-desc">
                    Jelajahi berbagai tipe ruangan yang ditawarkan Asrama Haji, periksa kapasitas peserta, fasilitas yang ada di setiap ruangan, lalu buat pengajuan peminjaman baru secara instan.
                </p>
                <div>
                    <a href="{{ route('users.main.ruangan.index') }}" class="btn-action-card btn-reservasi">
                        <i class="fas fa-plus me-1"></i> Cari & Pesan Ruangan
                    </a>
                </div>
            </div>
        </div>

        <!-- Action: Cek Ketersediaan -->
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="action-card">
                <div class="action-icon icon-ketersediaan">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4 class="action-title">Cek Ketersediaan Ruangan</h4>
                <p class="action-desc">
                    Periksa ketersediaan ruangan yang kosong pada tanggal penyewaan pilihan Anda berdasarkan kategori tertentu untuk merencanakan acara Anda dengan tepat.
                </p>
                <div>
                    <a href="{{ route('users.main.ruangan.ketersediaan') }}" class="btn-action-card btn-ketersediaan">
                        <i class="fas fa-magnifying-glass me-1"></i> Cek Ketersediaan Ruangan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Calendar Script --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reservasi & Rooms data passed from controller
        const bookings = @json($calendarBookings);
        const allRooms = @json($allRooms);

        function isBookingActiveOnDate(b, dateKey) {
            if (!b.tanggal) return false;
            if (!b.is_harian) {
                return b.tanggal === dateKey;
            }
            // Parse start and target dates in local timezone
            const start = new Date(b.tanggal + 'T00:00:00');
            const target = new Date(dateKey + 'T00:00:00');
            const end = new Date(start);
            end.setDate(start.getDate() + b.durasi_val - 1);
            
            return target >= start && target <= end;
        }

        let currentDate = new Date();

        const calendarMonthYear = document.getElementById('calendarMonthYear');
        const calendarDaysContainer = document.getElementById('calendarDaysContainer');
        const prevMonthBtn = document.getElementById('prevMonthBtn');
        const nextMonthBtn = document.getElementById('nextMonthBtn');
        
        const selectedDateLabel = document.getElementById('selectedDateLabel');
        const defaultPlaceholder = document.getElementById('defaultPlaceholder');
        const sectionBooked = document.getElementById('sectionBooked');
        const selectedDateReservations = document.getElementById('selectedDateReservations');
        const sectionAvailable = document.getElementById('sectionAvailable');
        const selectedDateAvailability = document.getElementById('selectedDateAvailability');

        // Initialize Calendar
        renderCalendar();

        prevMonthBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        function renderCalendar() {
            if (!calendarDaysContainer) return;
            calendarDaysContainer.innerHTML = '';

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const firstDayIndex = new Date(year, month, 1).getDay();
            const lastDay = new Date(year, month + 1, 0).getDate();
            const prevLastDay = new Date(year, month, 0).getDate();

            // Set Month Name
            const monthNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            calendarMonthYear.textContent = `${monthNames[month]} ${year}`;

            // Add previous month filler days
            for (let i = firstDayIndex; i > 0; i--) {
                const day = prevLastDay - i + 1;
                const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                createDayCell(day, dateKey, true);
            }

            // Add current month days
            const today = new Date();
            for (let i = 1; i <= lastDay; i++) {
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                const isToday = today.getDate() === i && today.getMonth() === month && today.getFullYear() === year;
                createDayCell(i, dateKey, false, isToday);
            }

            // Add next month filler days to complete calendar grid
            const totalCells = firstDayIndex + lastDay;
            const remainingCells = 42 - totalCells; // 6 rows of 7 days = 42
            for (let i = 1; i <= remainingCells; i++) {
                const dateKey = `${year}-${String(month + 2).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                createDayCell(i, dateKey, true);
            }
        }

        function createDayCell(day, dateKey, isOtherMonth, isToday = false) {
            if (!calendarDaysContainer) return;
            const cell = document.createElement('div');
            cell.className = 'calendar-day-cell';
            if (isOtherMonth) cell.classList.add('other-month');
            if (isToday) cell.classList.add('today');
            cell.dataset.date = dateKey;

            // Day number
            const numSpan = document.createElement('span');
            numSpan.className = 'day-number';
            numSpan.textContent = day;
            cell.appendChild(numSpan);

            // Indicators container
            const indicatorsDiv = document.createElement('div');
            indicatorsDiv.className = 'indicators';

            // Filter bookings for this day using helper that supports multiday daily bookings
            const dayBookings = bookings.filter(b => isBookingActiveOnDate(b, dateKey));
            const hasPending = dayBookings.some(b => b.status === 'PENDING');
            const hasApproved = dayBookings.some(b => b.status === 'APPROVED');

            if (hasPending) {
                const pendingDot = document.createElement('span');
                pendingDot.className = 'indicator-dot pending';
                indicatorsDiv.appendChild(pendingDot);
            }
            if (hasApproved) {
                const approvedDot = document.createElement('span');
                approvedDot.className = 'indicator-dot approved';
                indicatorsDiv.appendChild(approvedDot);
            }

            cell.appendChild(indicatorsDiv);

            // Click Handler
            cell.addEventListener('click', function() {
                document.querySelectorAll('.calendar-day-cell').forEach(c => c.classList.remove('selected'));
                cell.classList.add('selected');
                showDateDetails(dateKey, dayBookings);
            });

            calendarDaysContainer.appendChild(cell);
        }

        function showDateDetails(dateKey, dayBookings) {
            if (!selectedDateLabel) return;
            
            // Format readable date
            const dateObj = new Date(dateKey);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = dateObj.toLocaleDateString('id-ID', options);
            selectedDateLabel.textContent = formattedDate;

            // Hide default placeholder
            if (defaultPlaceholder) defaultPlaceholder.style.display = 'none';

            // 1. Render Booked Section (Top)
            if (dayBookings.length === 0) {
                selectedDateReservations.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <p class="mb-0" style="font-size: 12px; font-style: italic;">Tidak ada ruangan terbooking pada tanggal ini.</p>
                    </div>
                `;
            } else {
                let bookedHtml = '';
                dayBookings.forEach(b => {
                    const isApproved = b.status === 'APPROVED';
                    
                    let badgeColor = '';
                    let statusLabel = '';
                    
                    if (b.status_peminjaman === 'SELESAI') {
                        badgeColor = 'bg-success';
                        statusLabel = 'Selesai';
                    } else if (b.status_peminjaman === 'BATAL') {
                        badgeColor = 'bg-secondary';
                        statusLabel = 'Dibatalkan';
                    } else if (b.status_peminjaman === 'CHECK_IN') {
                        badgeColor = 'bg-primary';
                        statusLabel = 'Sudah Check-In';
                    } else if (b.status_peminjaman === 'CHECK_OUT') {
                        badgeColor = 'bg-success';
                        statusLabel = 'Sudah Check-Out';
                    } else {
                        badgeColor = isApproved ? 'bg-success' : 'bg-warning text-dark';
                        statusLabel = isApproved ? 'Disetujui' : 'Menunggu';
                    }
                    
                    bookedHtml += `
                        <div class="card border-0 shadow-sm p-3 position-relative" style="background: #fff; border-left: 4px solid ${isApproved ? '#48bb78' : '#ecc94b'} !important;">
                            <span class="badge ${badgeColor} position-absolute top-0 end-0 m-3" style="font-size: 10px; font-weight: 700;">${statusLabel}</span>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 13.5px; padding-right: 60px;">${b.guest_name}</h6>
                            <div class="text-secondary" style="font-size: 12px; line-height: 1.5;">
                                <div class="mb-1"><i class="fas fa-door-open me-1" style="width: 14px;"></i>${b.ruangan}</div>
                                <div class="mb-1">
                                    ${b.is_harian 
                                        ? `<i class="far fa-calendar-alt me-1" style="width: 14px;"></i>${b.tanggal_range} (${b.durasi})` 
                                        : `<i class="far fa-clock me-1" style="width: 14px;"></i>${b.jam_mulai} - ${b.jam_selesai} WIB (${b.durasi})`
                                    }
                                </div>
                            </div>
                        </div>
                    `;
                });
                selectedDateReservations.innerHTML = bookedHtml;
            }
            if (sectionBooked) sectionBooked.style.display = 'block';

            // 2. Render Available Section (Bottom)
            // Filter allRooms to find which rooms are NOT in dayBookings
            const bookedRoomIds = dayBookings.map(b => b.ruangan_id);
            const availableRooms = allRooms.filter(r => !bookedRoomIds.includes(r.id_ruangan));

            if (availableRooms.length === 0) {
                selectedDateAvailability.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <p class="mb-0" style="font-size: 12px; font-style: italic;">Semua ruangan telah terbooking pada tanggal ini.</p>
                    </div>
                `;
            } else {
                let availHtml = '';
                availableRooms.forEach(r => {
                    const bookingUrl = `{{ route('users.main.reservasi.create') }}?ruangan_id=${r.id_ruangan}&tanggal=${dateKey}`;
                    availHtml += `
                        <div class="card border-0 shadow-sm p-3 position-relative mb-2" style="background: #fff; border-left: 4px solid #48bb78 !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 13.5px;">${r.nama_ruangan}</h6>
                                    <div class="text-secondary mb-0" style="font-size: 11.5px;">
                                        <i class="fas fa-users me-1"></i> Kapasitas: ${r.kapasitas} Orang
                                    </div>
                                </div>
                                <a href="${bookingUrl}" class="btn btn-sm btn-success fw-semibold" style="font-size: 11px; background-color: #48bb78; border: none; padding: 5px 12px; border-radius: 6px; color: #fff;">
                                    <i class="fas fa-plus me-1"></i>Pesan
                                </a>
                            </div>
                        </div>
                    `;
                });
                selectedDateAvailability.innerHTML = availHtml;
            }
            if (sectionAvailable) sectionAvailable.style.display = 'block';
        }
    });
</script>
@endpush
