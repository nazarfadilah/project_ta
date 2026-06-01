<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guest;
use App\Models\Berita;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Admin Dashboard - Akses semua data
    public function adminIndex()
    {
        $stats = [
            'users' => User::count(),
            'guests' => 0, // Placeholder untuk tamu
            'buildings' => 0, // Placeholder untuk gedung
        ];

        return view('admin.dashboard', $stats);
    }

    // User Dashboard - Hanya data user yang login
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'guests' => Guest::count(),
            'buildings' => 0,
            'beritas' => Berita::count(),
        ];

        return view('main.index', $stats);
    }

    // Users Dashboard - Users yang sudah login
    public function usersindex()
    {
        $stats = [
            'users' => User::count(),
            'guests' => Guest::count(),
            'buildings' => 0,
            'beritas' => Berita::count(),
        ];

        return view('users.main.index', $stats);
    }
}
