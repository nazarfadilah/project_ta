<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guest;
use App\Models\Berita;
// use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Sarana;
use App\Models\PaketRuangan;
use App\Models\PeminjamanTransaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Admin Dashboard - Akses semua data
    public function adminIndex()
    {
        $user = auth()->user();

        $stats = [
            'users' => User::count(),
            'guests' => Guest::count(),
            // Code Lama:
            // 'buildings' => Gedung::count(),
            // Code Baru:
            'buildings' => 0,
            'beritas' => ($user && $user->roleId == 3) ? Berita::where('userId', $user->id)->count() : Berita::count(),
            'rooms' => Ruangan::count(),
            'saranas' => Sarana::count(),
            'packages' => PaketRuangan::count(),
            'bookings' => PeminjamanTransaksi::count(),
        ];

        // Code Lama:
        // $pendingBookings = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung')
        // Code Baru:
        $pendingBookings = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan')
            ->where('statusApproval', 'PENDING')
            ->orderBy('createdAt', 'desc')
            ->get();

        $brokenSaranas = Sarana::where('kondisi', 'Perlu Perbaikan')
            ->orderBy('updated_at', 'desc')
            ->get();

        $draftBeritas = Berita::with('user')
            ->where('status', 'draft')
            ->orderBy('updated_at', 'desc')
            ->get();

        $todayCheckins = [];
        if ($user && $user->roleId == 3) {
            // Code Lama:
            // $todayCheckins = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung')
            // Code Baru:
            $todayCheckins = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan')
                ->where('tanggal', date('Y-m-d'))
                ->where('statusApproval', 'APPROVED')
                ->where('statusPeminjaman', 'RESERVASI')
                ->orderBy('jamMulai', 'asc')
                ->get();
        }

        $isAdminOrPimpinan = $user ? in_array($user->roleId, [1, 2]) : true;

        // Code Lama:
        // $calendarBookings = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung')
        // Code Baru:
        $calendarBookings = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan')
            ->whereIn('statusApproval', ['PENDING', 'APPROVED'])
            ->get()
            ->map(function ($booking) use ($isAdminOrPimpinan) {
                return [
                    'id' => $booking->id,
                    'tanggal' => $booking->tanggal ? $booking->tanggal->format('Y-m-d') : null,
                    'status' => $booking->statusApproval,
                    'status_peminjaman' => $booking->statusPeminjaman,
                    'guest_name' => $isAdminOrPimpinan ? null : ($booking->guest->name ?? 'Tamu'),
                    'ruangan' => $isAdminOrPimpinan ? null : ($booking->paketRuangan->ruangan->nama_ruangan ?? 'Ruangan'),
                    // Code Lama:
                    // 'gedung' => $isAdminOrPimpinan ? null : ($booking->paketRuangan->ruangan->gedung->nama_gedung ?? 'Gedung'),
                    // Code Baru:
                    'gedung' => null,
                    'jam_mulai' => $isAdminOrPimpinan ? null : ($booking->jamMulai ? $booking->jamMulai->format('H:i') : null),
                    'durasi' => $isAdminOrPimpinan ? null : ($booking->durasi . ' Jam'),
                ];
            })
            ->filter(function ($booking) {
                return !empty($booking['tanggal']);
            })
            ->values();

        return view('main.index', array_merge($stats, [
            'pendingBookings' => $pendingBookings,
            'brokenSaranas' => $brokenSaranas,
            'draftBeritas' => $draftBeritas,
            'todayCheckins' => $todayCheckins,
            'calendarBookings' => $calendarBookings,
        ]));
    }

    // User Dashboard - Hanya data user yang login
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'guests' => Guest::count(),
            // Code Lama:
            // 'buildings' => Gedung::count(),
            // Code Baru:
            'buildings' => 0,
            'beritas' => Berita::count(),
        ];

        return view('main.index', $stats);
    }

    // Users Dashboard - Users yang sudah login
    public function usersindex()
    {
        $stats = [
            // Code Lama:
            // 'buildings' => Gedung::count(),
            // Code Baru:
            'buildings' => 0,
            'rooms' => Ruangan::count(),
            'saranas' => Sarana::count(),
        ];

        return view('users.main.index', $stats);
    }
}

