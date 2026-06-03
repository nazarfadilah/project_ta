<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guest;
use App\Models\Berita;
use App\Models\Gedung;
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
            'buildings' => Gedung::count(),
            'beritas' => Berita::count(),
            'rooms' => Ruangan::count(),
            'saranas' => Sarana::count(),
            'packages' => PaketRuangan::count(),
            'bookings' => PeminjamanTransaksi::count(),
        ];

        $pendingBookings = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung')
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
            $todayCheckins = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung')
                ->where('tanggal', date('Y-m-d'))
                ->where('statusApproval', 'APPROVED')
                ->where('statusPeminjaman', 'RESERVASI')
                ->orderBy('jamMulai', 'asc')
                ->get();
        }

        return view('main.index', array_merge($stats, [
            'pendingBookings' => $pendingBookings,
            'brokenSaranas' => $brokenSaranas,
            'draftBeritas' => $draftBeritas,
            'todayCheckins' => $todayCheckins,
        ]));
    }

    // User Dashboard - Hanya data user yang login
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'guests' => Guest::count(),
            'buildings' => Gedung::count(),
            'beritas' => Berita::count(),
        ];

        return view('main.index', $stats);
    }

    // Users Dashboard - Users yang sudah login
    public function usersindex()
    {
        $stats = [
            'buildings' => Gedung::count(),
            'rooms' => Ruangan::count(),
            'saranas' => Sarana::count(),
        ];

        return view('users.main.index', $stats);
    }
}

