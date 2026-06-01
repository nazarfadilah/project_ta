<?php
namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class UsersRuanganController extends Controller
{
    /**
     * Display a listing of ruangans for users (view-only)
     */
    public function index()
    {
        $ruangans = Ruangan::with('gedung')->get();
        return view('users.main.ruangan.index', compact('ruangans'));
    }

    /**
     * Display the specified ruangan by slug
     */
    public function show($slug)
    {
        // Convert slug to original name for searching
        $namaRuangan = str_replace('-', ' ', $slug);
        
        // Search for ruangan by name
        $ruangan = Ruangan::where('nama_ruangan', 'like', '%' . $namaRuangan . '%')
                          ->with('gedung', 'mediaFiles')
                          ->firstOrFail();
        
        return view('users.main.ruangan.form', compact('ruangan'));
    }
}
