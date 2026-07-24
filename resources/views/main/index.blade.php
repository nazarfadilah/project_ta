@extends('main.layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid" style="padding-left: 60px; padding-right: 60px; margin-top: 30px; position: relative;">
    <!-- Main Content Row -->
    <div class="row">
        <!-- Kolom Kiri: Welcome Banner & Kartu Statistik -->
        <div class="col-xl-{{ Auth::user()->roleId == 3 ? '8' : '12' }} col-lg-{{ Auth::user()->roleId == 3 ? '8' : '12' }} col-md-12 transition-all-layout" id="mainCardsContainer">
            <!-- Welcome Banner -->
            <div class="welcome-banner" style="background: linear-gradient(135deg, #C9A961 0%, #A48135 100%); border-radius: 16px; color: white; padding: 35px 40px; margin-bottom: 35px; position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(201, 169, 97, 0.2);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 position-relative" style="z-index: 2;">
                    <div class="welcome-content" style="max-width: 78%;">
                        <h1 class="welcome-title" style="font-size: 30px; font-weight: 700; margin-bottom: 12px; letter-spacing: -0.5px;">Selamat Datang, {{ Auth::user()->role->name ?? 'Administrator' }}!</h1>
                        <p class="welcome-subtitle" style="font-size: 15px; opacity: 0.95; line-height: 1.6; font-weight: 400; margin-bottom: 0;">
                            Di portal manajemen SIPRASA (Sistem Informasi Peminjaman Sarana & Prasarana). Sebagai administrator/staff, Anda dapat memantau data transaksi, mengonfirmasi pengajuan dari peminjam, mengelola ketersediaan ruangan, sarana, prasarana, serta menghasilkan laporan performa secara terintegrasi.
                        </p>
                    </div>
                    @if(in_array(Auth::user()->roleId, [1, 2]))
                    <div class="flex-shrink-0 mt-2 mt-md-0">
                        <button class="btn d-flex align-items-center gap-2 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#calendarCollapse" aria-expanded="false" aria-controls="calendarCollapse" id="btnToggleCalendar" style="background: rgba(255, 255, 255, 0.22); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.4); color: #fff; font-weight: 600; font-size: 14px; border-radius: 30px; padding: 10px 20px; transition: all 0.3s;">
                            <i class="fas fa-calendar-alt"></i>
                            <span id="calendarToggleText">Buka Kalender</span>
                        </button>
                    </div>
                    @endif
                </div>
                <style>
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
                </style>
            </div>

            <!-- Header Dashboard dengan Toggle Kalender -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Ringkasan Data</h4>
                </div>
            </div>

            <div class="row">
                <!-- Card Pengguna -->
                @if(in_array(Auth::user()->roleId, [1, 2]))
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    @if(Auth::user()->roleId == 2)
                        <div class="card border-0 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%); min-height: 150px; cursor: default;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between align-items-flex-start">
                                    <div>
                                        <h3 class="card-title fw-bold display-4 mb-2 text-dark">
                                            {{ $users }}
                                        </h3>
                                        <p class="card-text text-dark fw-semibold mb-0">Total Pengguna</p>
                                    </div>
                                    <i class="fas fa-users fa-3x" style="color: rgba(0,0,0,0.1);"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('main.users.index') }}" class="text-decoration-none">
                            <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%); min-height: 150px;">
                                <div class="card-body d-flex flex-column justify-content-between h-100">
                                    <div class="d-flex justify-content-between align-items-flex-start">
                                        <div>
                                            <h3 class="card-title fw-bold display-4 mb-2 text-dark">
                                                {{ $users }}
                                            </h3>
                                            <p class="card-text text-dark fw-semibold mb-0">Kelola Pengguna</p>
                                        </div>
                                        <i class="fas fa-users fa-3x" style="color: rgba(0,0,0,0.1);"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
                @endif

                <!-- Card Tamu -->
                @if(in_array(Auth::user()->roleId, [1, 2, 3]))
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <a href="{{ route('main.tamu.index') }}" class="text-decoration-none">
                        <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #17A2B8 0%, #138496 100%); min-height: 150px;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between align-items-flex-start">
                                    <div>
                                        <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                            {{ $guests }}
                                        </h3>
                                        <p class="card-text text-white fw-semibold mb-0">Kelola Tamu</p>
                                    </div>
                                    <i class="fas fa-user-tie fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <!-- Card Ruangan -->
                @if(in_array(Auth::user()->roleId, [2, 3]))
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <a href="{{ route('main.ruangan.index') }}" class="text-decoration-none">
                        <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #4E73DF 0%, #224ABE 100%); min-height: 150px;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between align-items-flex-start">
                                    <div>
                                        <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                            {{ $rooms }}
                                        </h3>
                                        <p class="card-text text-white fw-semibold mb-0">Kelola Ruangan</p>
                                    </div>
                                    <i class="fas fa-door-open fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <!-- Card Sarana & Prasarana -->
                @if(in_array(Auth::user()->roleId, [2, 3]))
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <a href="{{ route('main.sarana.index') }}" class="text-decoration-none">
                        <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #20C997 0%, #17A2B8 100%); min-height: 150px;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between align-items-flex-start">
                                    <div>
                                        <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                            {{ $saranas }}
                                        </h3>
                                        <p class="card-text text-white fw-semibold mb-0">Kelola Sarana</p>
                                    </div>
                                    <i class="fas fa-tools fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <!-- Card Paket Ruangan -->
                @if(in_array(Auth::user()->roleId, [2, 3]))
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <a href="{{ route('main.paket_ruangan.index') }}" class="text-decoration-none">
                        <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #E83E8C 0%, #D81B60 100%); min-height: 150px;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between align-items-flex-start">
                                    <div>
                                        <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                            {{ $packages }}
                                        </h3>
                                        <p class="card-text text-white fw-semibold mb-0">Kelola Paket Ruangan</p>
                                    </div>
                                    <i class="fas fa-box-open fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <!-- Card Peminjaman Ruangan -->
                @if(in_array(Auth::user()->roleId, [2, 3]))
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <a href="{{ route('main.transaksi.peminjaman.index') }}" class="text-decoration-none">
                        <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #6F42C1 0%, #593196 100%); min-height: 150px;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between align-items-flex-start">
                                    <div>
                                        <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                            {{ $bookings }}
                                        </h3>
                                        <p class="card-text text-white fw-semibold mb-0">Peminjaman Ruangan</p>
                                    </div>
                                    <i class="fas fa-calendar-check fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <!-- Card Berita -->
                @if(in_array(Auth::user()->roleId, [1, 2, 3]))
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <a href="{{ route('main.berita.index') }}" class="text-decoration-none">
                        <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #28A745 0%, #218838 100%); min-height: 150px;">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between align-items-flex-start">
                                    <div>
                                        <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                            {{ $beritas }}
                                        </h3>
                                        <p class="card-text text-white fw-semibold mb-0">Kelola Berita</p>
                                    </div>
                                    <i class="fas fa-newspaper fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Collapsible/Always-Visible Calendar -->
        <div class="col-xl-4 col-lg-4 col-md-12 mb-4 transition-all-layout {{ in_array(Auth::user()->roleId, [1, 2]) ? 'collapse' : '' }}" id="calendarCollapse">
            @if(in_array(Auth::user()->roleId, [1, 2]))
            <!-- Tombol Buka Kalender (hanya sebagai penahan visual jika ada collapse) -->
            @endif

            <!-- Card Kalender -->
            <div class="card border-0 shadow-sm rounded-3 mb-3" style="background: #fff; border: 1px solid #eef2f5 !important; overflow: hidden;">
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
            
            <!-- Card Detail Reservasi & Ketersediaan -->
            <div class="card border-0 shadow-sm rounded-3 bg-light-subtle" style="border: 1px solid #eef2f5 !important; overflow: hidden; min-height: 250px;">
                <div class="card-body p-3 d-flex flex-column" style="height: 100%;">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3" style="font-family: 'Outfit', sans-serif; font-size: 14px;">
                        <i class="fas fa-info-circle text-primary me-2"></i>Jadwal Sewa: <span id="selectedDateLabel" class="text-secondary fw-semibold">Pilih Tanggal</span>
                    </h6>
                    
                    <div class="d-flex flex-column gap-3 overflow-auto flex-grow-1" id="availabilityDetailsContainer" style="max-height: 400px; padding-right: 5px;">
                        <!-- Default Placeholder -->
                        <div class="text-center text-muted py-4 my-auto" id="defaultPlaceholder">
                            <i class="far fa-calendar-check fa-2x mb-2" style="color: #ddd;"></i>
                            <p class="mb-0" style="font-size: 12px;">Pilih tanggal dengan indikator untuk melihat rincian.</p>
                        </div>
                        
                        @if(Auth::user()->roleId == 3)
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
                        @else
                            <!-- Admin & Pimpinan simple container -->
                            <div class="d-flex flex-column gap-2" id="selectedDateReservations">
                                <!-- High-level details render here -->
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Datatables Monitoring -->
    <div class="row mt-4">
        <!-- Datatable Peminjaman Hari Ini (Bisa Check-In) - Khusus Petugas -->
        @if(Auth::user()->roleId == 3)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #007bff; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-calendar-day me-2"></i>Peminjaman Hari Ini (Siap Check-In)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="todayCheckinsTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Nama Guest</th>
                                    <th>Ruangan/Fasilitas</th>
                                    <th>Jam Mulai</th>
                                    <th>Durasi</th>
                                    <th style="width: 120px; text-align: center;">Status</th>
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayCheckins as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $item->kodePeminjaman }}</td>
                                    <td>{{ $item->guest->name ?? 'N/A' }}</td>
                                    <td>{{ $item->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->jamMulai)->format('H:i') }} WIB</td>
                                    <td>{{ $item->durasi }} Jam</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-success px-3 py-2" style="font-size: 12px; font-weight: 600;">APPROVED</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('main.transaksi.peminjaman.show', $item->id) }}" class="btn btn-sm btn-primary px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-sign-in-alt me-1"></i> Check-In
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Datatable Peminjaman Pending -->
        @if(Auth::user()->roleId == 3)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-hourglass-half me-2"></i>Peminjaman/Reservasi Baru (Menunggu Verifikasi)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="pendingBookingsTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Nama Guest</th>
                                    <th>Ruangan/Fasilitas</th>
                                    <th>Tanggal Peminjaman</th>
                                    <th>Durasi</th>
                                    <th style="width: 120px; text-align: center;">Status Approval</th>
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingBookings as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $item->kodePeminjaman }}</td>
                                    <td>{{ $item->guest->name ?? 'N/A' }}</td>
                                    <td>{{ $item->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                    <td>{{ $item->durasi }} Jam</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 12px; font-weight: 600;">PENDING</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('main.transaksi.peminjaman.show', $item->id) }}" class="btn btn-sm btn-primary px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Datatable Sarana Perlu Perbaikan -->
        @if(Auth::user()->roleId == 3)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #dc3545; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-tools me-2"></i>Sarana & Prasarana (Perlu Perbaikan)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="brokenSaranasTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Nama Sarana</th>
                                    <th style="width: 150px; text-align: center;">Kondisi</th>
                                    <th style="width: 120px; text-align: center;">Stok</th>
                                    <th>Tanggal Penerimaan</th>
                                    @if(Auth::user()->roleId != 2)
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brokenSaranas as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->nama }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-danger px-3 py-2" style="font-size: 12px; font-weight: 600;">Perlu Perbaikan</span>
                                    </td>
                                    <td style="text-align: center;" class="fw-bold">{{ $item->stok }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_penerimaan)->format('d F Y') }}</td>
                                    @if(Auth::user()->roleId != 2)
                                    <td style="text-align: center;">
                                        <a href="{{ route('main.sarana.edit', $item->id) }}" class="btn btn-sm btn-warning px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Datatable Berita Draft -->
        @if(Auth::user()->roleId == 1)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #6c757d; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-newspaper me-2"></i>Berita & Artikel (Draft)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="draftBeritasTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Judul Berita</th>
                                    <th>Pembuat</th>
                                    <th>Tanggal Publish</th>
                                    <th style="width: 120px; text-align: center;">Status</th>
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($draftBeritas as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->judul }}</td>
                                    <td>{{ $item->user->name_users ?? $item->user->username ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_publish)->format('d F Y') }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-secondary px-3 py-2 text-uppercase" style="font-size: 12px; font-weight: 600;">{{ $item->status }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if(Auth::user()->roleId == 2)
                                        <a href="{{ route('main.berita.show', $item->id) }}" class="btn btn-sm btn-primary px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        @else
                                        <a href="{{ route('main.berita.edit', $item->id) }}" class="btn btn-sm btn-warning px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .display-4 {
        font-size: 3rem;
        line-height: 1;
    }
</style>
@endsection

@push('styles')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .dataTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    .dataTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    .dataTable tbody tr:hover {
        background-color: #fdf6e3;
    }
    .dataTables_wrapper {
        padding: 12px 0;
    }
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 15px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 6px 10px;
        font-size: 13px;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 15px;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ccc !important;
        border-radius: 5px !important;
        padding: 5px 8px !important;
        font-size: 13px !important;
        margin: 0 8px !important;
        width: 60px !important;
        max-width: none !important;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 13px;
        margin-top: 15px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #C9A961 !important;
        border-color: #C9A961 !important;
        color: #fff !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #d4ba7a !important;
        border-color: #d4ba7a !important;
        color: #fff !important;
    }
</style>
@endpush

@push('scripts')
{{-- DataTables JS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        @if(Auth::user()->roleId == 3)
        $('#todayCheckinsTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada peminjaman hari ini yang siap untuk check-in.",
                emptyTable: "Tidak ada peminjaman hari ini yang siap untuk check-in.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { searchable: false, targets: [0, 6, 7] }
            ]
        });
        @endif

        @if(Auth::user()->roleId == 3)
        $('#pendingBookingsTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data peminjaman/reservasi yang diajukan dan belum diverifikasi",
                emptyTable: "Tidak ada data peminjaman/reservasi yang diajukan dan belum diverifikasi",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { searchable: false, targets: [0, 6, 7] }
            ]
        });
        @endif

        @if(Auth::user()->roleId == 3)
        $('#brokenSaranasTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data sarana yang perlu perbaikan.",
                emptyTable: "Tidak ada data sarana yang perlu perbaikan.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: @if(Auth::user()->roleId == 2) [0] @else [0, 5] @endif },
                { searchable: false, targets: @if(Auth::user()->roleId == 2) [0, 2] @else [0, 2, 5] @endif }
            ]
        });
        @endif

        @if(Auth::user()->roleId == 1)
        $('#draftBeritasTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada berita dengan status draft.",
                emptyTable: "Tidak ada berita dengan status draft.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, 5] },
                { searchable: false, targets: [0, 4, 5] }
            ]
        });
        @endif
    });
</script>
@endpush

@push('styles')
<style>
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
        height: 6px;
    }
    .indicator-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .indicator-dot.pending {
        background-color: #ecc94b; /* Yellow/Gold */
        box-shadow: 0 0 2px rgba(236,201,75,0.8);
    }
    .indicator-dot.approved {
        background-color: #48bb78; /* Green */
        box-shadow: 0 0 2px rgba(72,187,120,0.8);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookings = @json($calendarBookings ?? []);
        const allRooms = @json($allRooms ?? []);
        const userRoleId = {{ Auth::user()->roleId }};
        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth(); // 0-indexed
 
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
 
        // Element Selectors
        const calendarMonthYear = document.getElementById('calendarMonthYear');
        const calendarDaysContainer = document.getElementById('calendarDaysContainer');
        const prevMonthBtn = document.getElementById('prevMonthBtn');
        const nextMonthBtn = document.getElementById('nextMonthBtn');
        const selectedDateLabel = document.getElementById('selectedDateLabel');
        const selectedDateReservations = document.getElementById('selectedDateReservations');
        const btnToggleCalendar = document.getElementById('btnToggleCalendar');
        const calendarToggleText = document.getElementById('calendarToggleText');
 
        // Toggle Button Text and Layout changes
        const calendarCollapse = document.getElementById('calendarCollapse');
        if (calendarCollapse && userRoleId !== 3) {
            calendarCollapse.addEventListener('show.bs.collapse', function () {
                if (calendarToggleText) calendarToggleText.textContent = 'Tutup Kalender';
                if (btnToggleCalendar) {
                    btnToggleCalendar.style.background = 'rgba(0, 0, 0, 0.35)';
                    btnToggleCalendar.style.borderColor = 'rgba(255, 255, 255, 0.5)';
                }
                
                // Shrink stat cards container to col-xl-8
                const mainCardsContainer = document.getElementById('mainCardsContainer');
                if (mainCardsContainer) {
                    mainCardsContainer.classList.remove('col-xl-12', 'col-lg-12');
                    mainCardsContainer.classList.add('col-xl-8', 'col-lg-8');
                }
            });
            calendarCollapse.addEventListener('hide.bs.collapse', function () {
                if (calendarToggleText) calendarToggleText.textContent = 'Buka Kalender';
                if (btnToggleCalendar) {
                    btnToggleCalendar.style.background = 'rgba(255, 255, 255, 0.22)';
                    btnToggleCalendar.style.borderColor = 'rgba(255, 255, 255, 0.4)';
                }
                
                // Expand stat cards container back to col-xl-12
                const mainCardsContainer = document.getElementById('mainCardsContainer');
                if (mainCardsContainer) {
                    mainCardsContainer.classList.remove('col-xl-8', 'col-lg-8');
                    mainCardsContainer.classList.add('col-xl-12', 'col-lg-12');
                }
            });
        }

        function isBookingActiveOnDate(b, dateKey) {
            if (!b.tanggal) return false;
            if (!b.is_harian) {
                return b.tanggal === dateKey;
            }
            const start = new Date(b.tanggal + 'T00:00:00');
            const target = new Date(dateKey + 'T00:00:00');
            const end = new Date(start);
            end.setDate(start.getDate() + b.durasi_val - 1);
            
            return target >= start && target <= end;
        }

        // Initialize Calendar
        renderCalendar(currentYear, currentMonth);

        // Prev Month click
        if (prevMonthBtn) {
            prevMonthBtn.addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentYear, currentMonth);
            });
        }

        // Next Month click
        if (nextMonthBtn) {
            nextMonthBtn.addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentYear, currentMonth);
            });
        }

        function renderCalendar(year, month) {
            // Set Header Text
            if (calendarMonthYear) {
                calendarMonthYear.textContent = `${monthNames[month]} ${year}`;
            }
            
            // Clear Container
            if (calendarDaysContainer) {
                calendarDaysContainer.innerHTML = '';
            }

            // First day of the month (0 = Sunday, 1 = Monday, etc.)
            const firstDayIndex = new Date(year, month, 1).getDay();
            // Total days in current month
            const totalDays = new Date(year, month + 1, 0).getDate();
            // Total days in previous month
            const prevTotalDays = new Date(year, month, 0).getDate();

            // Render Previous Month's trailing days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const day = prevTotalDays - i;
                const prevMonthNum = month === 0 ? 11 : month - 1;
                const prevYearNum = month === 0 ? year - 1 : year;
                const dateKey = formatDateKey(prevYearNum, prevMonthNum, day);
                createDayCell(day, true, dateKey);
            }

            // Render Current Month's days
            const today = new Date();
            for (let day = 1; day <= totalDays; day++) {
                const isToday = today.getDate() === day && today.getMonth() === month && today.getFullYear() === year;
                const dateKey = formatDateKey(year, month, day);
                createDayCell(day, false, dateKey, isToday);
            }

            // Render Next Month's leading days to fill grid (usually 42 cells total)
            const totalCellsRendered = firstDayIndex + totalDays;
            const remainingCells = 42 - totalCellsRendered;
            for (let day = 1; day <= remainingCells; day++) {
                const nextMonthNum = month === 11 ? 0 : month + 1;
                const nextYearNum = month === 11 ? year + 1 : year;
                const dateKey = formatDateKey(nextYearNum, nextMonthNum, day);
                createDayCell(day, true, dateKey);
            }
        }

        function formatDateKey(year, month, day) {
            const m = String(month + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${year}-${m}-${d}`;
        }

        function createDayCell(day, isOtherMonth, dateKey, isToday = false) {
            if (!calendarDaysContainer) return;
            const cell = document.createElement('div');
            cell.className = 'calendar-day-cell';
            if (isOtherMonth) cell.classList.add('other-month');
            if (isToday) cell.classList.add('today');
            cell.dataset.date = dateKey;

            // Day number element
            const numSpan = document.createElement('span');
            numSpan.className = 'day-number';
            numSpan.textContent = day;
            cell.appendChild(numSpan);

            // Indicators container
            const indicatorsDiv = document.createElement('div');
            indicatorsDiv.className = 'indicators';

            // Filter bookings for this day
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
                // Remove selected class from all cells
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

            const defaultPlaceholder = document.getElementById('defaultPlaceholder');
            if (defaultPlaceholder) defaultPlaceholder.style.display = 'none';
            
            if (userRoleId === 1 || userRoleId === 2) {
                if (!selectedDateReservations) return;
                if (dayBookings.length === 0) {
                    selectedDateReservations.innerHTML = `
                        <div class="text-center text-muted py-5">
                            <i class="far fa-calendar fa-3x mb-3" style="color: #ddd;"></i>
                            <p class="mb-0" style="font-size: 13px;">Tidak ada reservasi pada tanggal ini.</p>
                        </div>
                    `;
                    return;
                }

                // Admin or Pimpinan: only show status, not specific guest/room details
                const hasPending = dayBookings.some(b => b.status === 'PENDING');
                const hasApproved = dayBookings.some(b => b.status === 'APPROVED');

                let html = '<div class="d-flex flex-column gap-3">';
                
                if (hasPending) {
                    html += `
                        <div class="card border-0 shadow-sm p-3 position-relative" style="background: #fff; border-left: 4px solid #ecc94b !important;">
                            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3" style="font-size: 10px; font-weight: 700;">Menunggu</span>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 13.5px;">Status Reservasi</h6>
                            <p class="text-secondary mb-0" style="font-size: 12px;">Terdapat reservasi masuk yang diajukan pada tanggal ini.</p>
                        </div>
                    `;
                }

                if (hasApproved) {
                    html += `
                        <div class="card border-0 shadow-sm p-3 position-relative" style="background: #fff; border-left: 4px solid #48bb78 !important;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-3" style="font-size: 10px; font-weight: 700;">Disetujui</span>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 13.5px;">Status Peminjaman</h6>
                            <p class="text-secondary mb-0" style="font-size: 12px;">Terdapat peminjaman disetujui pada tanggal ini.</p>
                        </div>
                    `;
                }

                html += '</div>';
                selectedDateReservations.innerHTML = html;
                return;
            }

            // Render list of bookings (for Petugas/Role 3)
            const sectionBooked = document.getElementById('sectionBooked');
            const selectedDateReservations_petugas = document.getElementById('selectedDateReservations');
            if (selectedDateReservations_petugas) {
                if (dayBookings.length === 0) {
                    selectedDateReservations_petugas.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <p class="mb-0" style="font-size: 12px; font-style: italic;">Tidak ada ruangan terbooking pada tanggal ini.</p>
                        </div>
                    `;
                } else {
                    let html = '<div class="d-flex flex-column gap-3">';
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
                        const detailUrl = "{{ route('main.transaksi.peminjaman.show', ':id') }}".replace(':id', b.id);
                        
                        html += `
                            <div class="card border-0 shadow-sm p-3 position-relative" style="background: #fff; border-left: 4px solid ${isApproved ? '#48bb78' : '#ecc94b'} !important;">
                                <span class="badge ${badgeColor} position-absolute top-0 end-0 m-3" style="font-size: 10px; font-weight: 700;">${statusLabel}</span>
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 13.5px; padding-right: 60px;">${b.guest_name}</h6>
                                <div class="text-secondary" style="font-size: 12px; line-height: 1.5;">
                                    <div class="mb-1"><i class="fas fa-door-open me-1" style="width: 14px;"></i>${b.ruangan}</div>
                                    <div class="mb-2">
                                        ${b.is_harian 
                                            ? `<i class="far fa-calendar-alt me-1" style="width: 14px;"></i>${b.tanggal_range} (${b.durasi})` 
                                            : `<i class="far fa-clock me-1" style="width: 14px;"></i>${b.jam_mulai} - ${b.jam_selesai} WIB (${b.durasi})`
                                        }
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end border-top pt-2 mt-2">
                                    <a href="${detailUrl}" class="btn btn-outline-primary d-flex align-items-center gap-1" style="font-size: 11px; padding: 4px 10px; font-weight: 600; border-radius: 6px;">
                                        <i class="fas fa-eye"></i> Detail Peminjaman
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    selectedDateReservations_petugas.innerHTML = html;
                }
            }
            if (sectionBooked) sectionBooked.style.display = 'block';

            // Render list of available rooms (for Petugas/Role 3)
            const sectionAvailable = document.getElementById('sectionAvailable');
            const selectedDateAvailability = document.getElementById('selectedDateAvailability');
            if (selectedDateAvailability) {
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
                        const bookingUrl = `{{ route('main.transaksi.peminjaman.create') }}?ruangan_id=${r.id_ruangan}&tanggal=${dateKey}`;
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
            }
            if (sectionAvailable) sectionAvailable.style.display = 'block';
        }
    });
</script>
@endpush
