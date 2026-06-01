<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Ruangan;
use App\Models\PaketRuangan;
use App\Models\Guest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UsersReservasiController extends Controller
{
    /**
     * Show the form for creating a new reservasi with pre-filled ruangan data
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Periksa kelengkapan profil tamu dan nomor telepon user
        $isIncomplete = !$user->guestId 
            || !$user->phone 
            || !$user->guest 
            || empty($user->guest->nik) 
            || empty($user->guest->name)
            || empty($user->guest->address) 
            || $user->guest->address === '-';

        if ($isIncomplete) {
            return redirect()->route('users.profil.edit')
                ->with('error', 'Silakan lengkapi profil dan nomor telepon Anda terlebih dahulu sebelum melakukan reservasi.');
        }

        $ruanganId = $request->input('ruangan_id');
        $namaRuangan = $request->input('nama_ruangan');
        $tipeRuangan = $request->input('tipe_ruangan');
        $kapasitas = $request->input('kapasitas');
        $gedungId = $request->input('gedung_id');
        
        $ruangan = Ruangan::findOrFail($ruanganId);
        
        return view('users.main.reservasi.form', compact(
            'ruangan',
            'ruanganId',
            'namaRuangan',
            'tipeRuangan',
            'kapasitas',
            'gedungId'
        ));
    }

    /**
     * Store a newly created reservasi
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Periksa kelengkapan profil tamu dan nomor telepon user
        $isIncomplete = !$user->guestId 
            || !$user->phone 
            || !$user->guest 
            || empty($user->guest->nik) 
            || empty($user->guest->name)
            || empty($user->guest->address) 
            || $user->guest->address === '-';

        if ($isIncomplete) {
            return redirect()->route('users.profil.edit')
                ->with('error', 'Silakan lengkapi profil dan nomor telepon Anda terlebih dahulu sebelum melakukan reservasi.');
        }

        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id_ruangan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keperluan' => 'required|string|max:500',
            'estimasi_peserta' => 'required|integer|min:1',
            'kontak_person' => 'required|string',
            'no_telepon' => 'required|string',
        ]);
        
        $guestId = $user->guestId;

        // 2. Find package for this room
        $paket = PaketRuangan::where('ruangan_id', $validated['ruangan_id'])->first();
        if (!$paket) {
            $paket = PaketRuangan::create([
                'ruangan_id' => $validated['ruangan_id'],
                'nama_paket' => 'Sewa Standar',
                'durasi' => 24,
                'harga' => 100000.00,
                'status' => 'ACTIVE'
            ]);
        }

        // 3. Compute duration in hours
        $days = Carbon::parse($validated['tanggal_selesai'])->diffInDays(Carbon::parse($validated['tanggal_mulai']));
        $durationHours = ($days == 0) ? 24 : ($days * 24);

        // 4. Create PeminjamanTransaksi
        $peminjaman = PeminjamanTransaksi::create([
            'kodePeminjaman' => 'PJM/' . date('Ymd') . '/' . sprintf('%04d', rand(1, 9999)),
            'guestId' => $guestId,
            'facilityId' => $paket->id,
            'tanggal' => $validated['tanggal_mulai'],
            'jamMulai' => $validated['tanggal_mulai'] . ' 08:00:00',
            'durasi' => $durationHours,
            'statusPeminjaman' => 'RESERVASI',
            'statusApproval' => 'PENDING',
            'keterangan' => $validated['keperluan'],
            'biayaTambahan' => 0.00
        ]);

        // 5. Create unpaid Invoice
        Invoice::create([
            'peminjamanId' => $peminjaman->id,
            'kodeInvoice' => 'INV/' . date('Ymd') . '/' . sprintf('%04d', rand(1, 9999)),
            'totalBayar' => $paket->harga,
            'statusBayar' => 'UNPAID',
        ]);

        return redirect()->route('users.main.reservasi.index')
                       ->with('success', 'Reservasi berhasil dibuat. Mohon tunggu persetujuan admin.');
    }

    /**
     * Display a listing of user's reservasi
     */
    public function index()
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        if (!$guestId) {
            $reservasis = collect();
            return view('users.main.reservasi.index', compact('reservasis'));
        }

        $peminjamans = PeminjamanTransaksi::where('guestId', $guestId)
            ->with(['paketRuangan.ruangan.gedung'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Compatibility mapping layer to match Reservasi schema expected by Blade views
        $reservasis = $peminjamans->map(function ($item) {
            $item->id = $item->id;
            $item->ruangan = $item->paketRuangan->ruangan ?? new Ruangan();
            $item->tanggal_mulai = $item->tanggal;
            $item->tanggal_selesai = Carbon::parse($item->tanggal)->addHours($item->durasi)->format('Y-m-d');
            $item->estimasi_peserta = $item->paketRuangan->ruangan->kapasitas ?? 1;
            
            // Map approval statuses: PENDING, APPROVED, REJECTED
            // If statusPeminjaman is SELESAI, map to COMPLETED status
            $item->status = ($item->statusPeminjaman === 'SELESAI') ? 'COMPLETED' : $item->statusApproval;
            
            return $item;
        });

        return view('users.main.reservasi.index', compact('reservasis'));
    }

    /**
     * Display the specified reservasi
     */
    public function show($id)
    {
        $user = auth()->user();
        $guestId = $user->guestId;
        
        $peminjaman = PeminjamanTransaksi::where('guestId', $guestId)
            ->with(['paketRuangan.ruangan.gedung', 'paketRuangan.ruangan.mediaFiles'])
            ->findOrFail($id);
            
        // Compatibility mapping layer to match Reservasi schema expected by Blade views
        $reservasi = $peminjaman;
        $reservasi->ruangan = $peminjaman->paketRuangan->ruangan ?? new Ruangan();
        $reservasi->tanggal_mulai = $peminjaman->tanggal;
        $reservasi->tanggal_selesai = Carbon::parse($peminjaman->tanggal)->addHours($peminjaman->durasi)->format('Y-m-d');
        $reservasi->estimasi_peserta = $peminjaman->paketRuangan->ruangan->kapasitas ?? 1;
        $reservasi->status = ($peminjaman->statusPeminjaman === 'SELESAI') ? 'COMPLETED' : $peminjaman->statusApproval;
        $reservasi->keperluan = $peminjaman->keterangan;
        $reservasi->kontak_person = $peminjaman->guest->name ?? $user->username;
        $reservasi->no_telepon = $user->phone ?? '-';

        return view('users.main.reservasi.show', compact('reservasi'));
    }
}
