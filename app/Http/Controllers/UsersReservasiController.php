<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Ruangan;
use App\Models\PaketRuangan;
use App\Models\Guest;
use App\Models\Invoice;
use App\Models\Sarana;
use App\Models\DetailPeminjamanSarana;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UsersReservasiController extends Controller
{
    /**
     * Show the form for creating a new reservasi (supports empty form and pre-selected room)
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
        $tanggal = $request->input('tanggal');
        $ruangan = null;
        
        if ($ruanganId) {
            // Code Lama:
            // $ruangan = Ruangan::with(['gedung', 'paketRuangans', 'mediaFiles'])->findOrFail($ruanganId);
            // Code Baru:
            $ruangan = Ruangan::with(['paketRuangans', 'mediaFiles'])->findOrFail($ruanganId);
        }

        // Fetch all rooms with buildings for room selection dropdown
        // Code Lama:
        // $ruangans = Ruangan::with('gedung')->orderBy('nama_ruangan', 'asc')->get();
        // Code Baru:
        $ruangans = Ruangan::orderBy('nama_ruangan', 'asc')->get();
        
        // Fetch all equipment/sarana with available stock
        $saranas = Sarana::orderBy('nama', 'asc')->get();
        
        return view('users.main.reservasi.form', compact(
            'ruangan',
            'ruangans',
            'saranas',
            'ruanganId',
            'tanggal'
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
            'paket_id' => 'required|exists:paket_ruangan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'keperluan' => 'required|string|min:10|max:500',
            'estimasi_peserta' => 'required|integer|min:1',
            'kontak_person' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'sarana' => 'nullable|array',
            'sarana.*.sarana_id' => 'required|exists:sarana,id',
            'sarana.*.jumlah' => 'required|integer|min:1',
            'durasi_hari' => 'nullable|integer|min:1',
        ]);
        
        $ruangan = Ruangan::findOrFail($validated['ruangan_id']);
        $paket = PaketRuangan::findOrFail($validated['paket_id']);

        // Deteksi apakah paket harian
        $isHarian = false;
        if ($paket->nama_paket && (stripos($paket->nama_paket, 'hari') !== false || stripos($paket->nama_paket, 'harian') !== false)) {
            $isHarian = true;
        }

        // Tentukan durasi transaksi
        if ($isHarian) {
            $durasi = isset($validated['durasi_hari']) ? (int)$validated['durasi_hari'] : 1;
        } else {
            $durasi = $paket->durasi; // dalam jam
        }

        // Total harga paket dikalikan durasi jika harian
        $totalHargaPaket = $isHarian ? ($paket->harga * $durasi) : $paket->harga;

        // 1. Verifikasi kapasitas ruangan
        if ($validated['estimasi_peserta'] > $ruangan->kapasitas) {
            return back()->withErrors(['estimasi_peserta' => 'Estimasi peserta tidak boleh melebihi kapasitas ruangan (' . $ruangan->kapasitas . ' orang).'])->withInput();
        }

        // 2. Hitung waktu mulai dan selesai berdasarkan paket ruangan
        $startDateTime = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam_mulai']);
        if ($isHarian) {
            $endDateTime = $startDateTime->copy()->addDays($durasi);
        } else {
            $endDateTime = $startDateTime->copy()->addHours($durasi);
        }

        // 3. Verifikasi bentrok jadwal dengan pesanan ter-approve milik user lain
        $overlapping = PeminjamanTransaksi::whereHas('paketRuangan', function ($q) use ($validated) {
                $q->where('ruangan_id', $validated['ruangan_id']);
            })
            ->whereIn('statusApproval', ['APPROVED'])
            ->whereIn('statusPeminjaman', ['RESERVASI', 'CHECK_IN', 'SELESAI'])
            ->get()
            ->filter(function ($item) use ($startDateTime, $endDateTime) {
                $itemStart = Carbon::parse($item->jamMulai);
                $itemIsHarian = ($item->paketRuangan && (stripos($item->paketRuangan->nama_paket, 'hari') !== false || stripos($item->paketRuangan->nama_paket, 'harian') !== false));
                if ($itemIsHarian) {
                    $itemEnd = $itemStart->copy()->addDays($item->durasi);
                } else {
                    $itemEnd = $itemStart->copy()->addHours($item->durasi);
                }
                return $itemEnd->greaterThan($startDateTime) && $itemStart->lessThan($endDateTime);
            })
            ->first();

        if ($overlapping) {
            return back()->withErrors(['tanggal' => 'Ruangan ini sudah dipesan oleh pengguna lain untuk rentang waktu tersebut (jadwal terbooking). Silakan pilih tanggal atau jam mulai lainnya.'])->withInput();
        }

        // 4. Verifikasi ketersediaan stok sarana secara dinamis berdasarkan rentang tanggal/jam
        if (!empty($validated['sarana'])) {
            foreach ($validated['sarana'] as $item) {
                $saranaModel = Sarana::findOrFail($item['sarana_id']);
                $availableStock = $saranaModel->getAvailableStock($startDateTime, $endDateTime);
                if ($item['jumlah'] > $availableStock) {
                    return back()->withErrors(['sarana' => 'Stok sarana "' . $saranaModel->nama . '" tidak mencukupi untuk jadwal tersebut. Tersedia: ' . $availableStock . ' unit.'])->withInput();
                }
            }
        }

        $guestId = $user->guestId;

        // 5. Simpan transaksi peminjaman baru
        $peminjaman = PeminjamanTransaksi::create([
            'kodePeminjaman' => 'PJM/' . Carbon::parse($validated['tanggal'])->format('Ymd') . '/' . sprintf('%04d', rand(1, 9999)),
            'guestId' => $guestId,
            'facilityId' => $validated['paket_id'],
            'tanggal' => $validated['tanggal'],
            'jamMulai' => $startDateTime->format('Y-m-d H:i:s'),
            'durasi' => $durasi,
            'statusPeminjaman' => 'RESERVASI',
            'statusApproval' => 'PENDING',
            'keterangan' => $validated['keperluan'],
            'biayaTambahan' => 0.00,
            'userId' => $user->id
        ]);

        // 6. Buat invoice tagihan non-paid
        Invoice::create([
            'noInvoice' => 'INV/' . Carbon::parse($validated['tanggal'])->format('Ymd') . '/' . sprintf('%04d', $peminjaman->id),
            'peminjamanId' => $peminjaman->id,
            'subtotal' => $totalHargaPaket,
            'biayaTambahan' => 0.00,
            'totalHarga' => $totalHargaPaket,
            'statusInvoice' => 'UNPAID',
            'status_pembayaran' => 'BELUM_BAYAR',
            'tglInvoice' => now(),
            'tglDueDate' => now()->addDays(7),
        ]);

        // 7. Simpan detail sewa sarana tambahan jika diinput
        if (!empty($validated['sarana'])) {
            foreach ($validated['sarana'] as $item) {
                DetailPeminjamanSarana::create([
                    'sarana_id' => $item['sarana_id'],
                    'peminjaman_id' => $peminjaman->id,
                    'jumlah' => (string)$item['jumlah'],
                ]);
            }
        }

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

        // Code Lama:
        // $peminjamans = PeminjamanTransaksi::where('guestId', $guestId)->with(['paketRuangan.ruangan.gedung'])->orderBy('tanggal', 'desc')->get();
        // Code Baru:
        $peminjamans = PeminjamanTransaksi::where('guestId', $guestId)
            ->with(['paketRuangan.ruangan'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Compatibility mapping layer to match Reservasi schema expected by Blade views
        $reservasis = $peminjamans->map(function ($item) {
            $item->id = $item->id;
            $item->ruangan = $item->paketRuangan->ruangan ?? new Ruangan();
            $item->tanggal_mulai = $item->tanggal;
            $itemIsHarian = ($item->paketRuangan && (stripos($item->paketRuangan->nama_paket, 'hari') !== false || stripos($item->paketRuangan->nama_paket, 'harian') !== false));
            if ($itemIsHarian) {
                $item->tanggal_selesai = Carbon::parse($item->jamMulai)->addDays($item->durasi)->format('Y-m-d');
            } else {
                $item->tanggal_selesai = Carbon::parse($item->jamMulai)->addHours($item->durasi)->format('Y-m-d');
            }
            $item->estimasi_peserta = $item->paketRuangan->ruangan->kapasitas ?? 1;
            
            // Map approval statuses: PENDING, APPROVED, REJECTED
            // If statusPeminjaman is SELESAI, map to COMPLETED status
            // If statusPeminjaman is BATAL, map to BATAL status
            if ($item->statusPeminjaman === 'SELESAI') {
                $item->status = 'COMPLETED';
            } elseif ($item->statusPeminjaman === 'BATAL') {
                $item->status = 'BATAL';
            } else {
                $item->status = $item->statusApproval;
            }
            
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
        
        // Code Lama:
        // $peminjaman = PeminjamanTransaksi::where('guestId', $guestId)->with(['paketRuangan.ruangan.gedung', 'paketRuangan.ruangan.mediaFiles', 'detailSaranas.sarana'])->findOrFail($id);
        // Code Baru:
        $peminjaman = PeminjamanTransaksi::where('guestId', $guestId)
            ->with(['paketRuangan.ruangan.mediaFiles', 'detailSaranas.sarana'])
            ->findOrFail($id);
            
        // Compatibility mapping layer to match Reservasi schema expected by Blade views
        $reservasi = $peminjaman;
        $reservasi->ruangan = $peminjaman->paketRuangan->ruangan ?? new Ruangan();
        $reservasi->tanggal_mulai = $peminjaman->tanggal;
        $pjmIsHarian = ($peminjaman->paketRuangan && (stripos($peminjaman->paketRuangan->nama_paket, 'hari') !== false || stripos($peminjaman->paketRuangan->nama_paket, 'harian') !== false));
        if ($pjmIsHarian) {
            $reservasi->tanggal_selesai = Carbon::parse($peminjaman->jamMulai)->addDays($peminjaman->durasi)->format('Y-m-d');
        } else {
            $reservasi->tanggal_selesai = Carbon::parse($peminjaman->jamMulai)->addHours($peminjaman->durasi)->format('Y-m-d');
        }
        $reservasi->estimasi_peserta = $peminjaman->paketRuangan->ruangan->kapasitas ?? 1;
        if ($peminjaman->statusPeminjaman === 'SELESAI') {
            $reservasi->status = 'COMPLETED';
        } elseif ($peminjaman->statusPeminjaman === 'BATAL') {
            $reservasi->status = 'BATAL';
        } else {
            $reservasi->status = $peminjaman->statusApproval;
        }
        $reservasi->keperluan = $peminjaman->keterangan;
        $reservasi->kontak_person = $peminjaman->guest->name ?? $user->username;
        $reservasi->no_telepon = $user->phone ?? '-';

        return view('users.main.reservasi.show', compact('reservasi'));
    }

    /**
     * Cancel a reservation by the user
     */
    public function cancel(Request $request, $id)
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        if (!$guestId) {
            return back()->with('error', 'Data tamu tidak ditemukan.');
        }

        $peminjaman = PeminjamanTransaksi::where('guestId', $guestId)->findOrFail($id);

        // Hanya bisa batalkan jika statusPeminjaman masih RESERVASI (belum check-in)
        if ($peminjaman->statusPeminjaman !== 'RESERVASI') {
            return back()->with('error', 'Peminjaman tidak dapat dibatalkan karena status sudah berubah.');
        }

        $validated = $request->validate([
            'alasan_pembatalan' => 'nullable|string|max:1000',
        ]);

        $peminjaman->update([
            'statusPeminjaman' => 'BATAL',
            'alasan_pembatalan' => $validated['alasan_pembatalan'] ?? null,
        ]);

        return redirect()->route('users.main.reservasi.index')->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
