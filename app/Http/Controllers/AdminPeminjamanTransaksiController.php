<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Invoice;
use App\Models\User;
use App\Mail\ReservationStatusMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminPeminjamanTransaksiController extends Controller
{
    /**
     * Display a listing of peminjaman/reservasi (Diajukan & Disetujui)
     */
    public function index()
    {
        // Code Lama:
        // $peminjaman = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung', 'user')
        // Code Baru:
        $peminjaman = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan', 'user')
            ->whereIn('statusApproval', ['PENDING', 'APPROVED'])
            ->whereIn('statusPeminjaman', ['RESERVASI', 'CHECK_IN'])
            ->orderBy('createdAt', 'DESC')
            ->get();

        $pageTitle = 'Peminjaman Aktif (Diajukan & Disetujui)';

        return view('main.transaksi.peminjaman.index', compact('peminjaman', 'pageTitle'));
    }

    /**
     * Display a listing of completed and cancelled peminjaman/reservasi (Selesai & Batal)
     */
    public function history()
    {
        // Code Lama:
        // $peminjaman = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung', 'user')
        // Code Baru:
        $peminjaman = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan', 'user')
            ->where(function ($query) {
                $query->whereIn('statusPeminjaman', ['SELESAI', 'BATAL'])
                      ->orWhere('statusApproval', 'REJECTED');
            })
            ->orderBy('createdAt', 'DESC')
            ->get();

        $pageTitle = 'Riwayat Peminjaman (Selesai & Batal)';

        return view('main.transaksi.peminjaman.index', compact('peminjaman', 'pageTitle'));
    }

    /**
     * Show detail form with approve/reject buttons
     */
    public function show($id)
    {
        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        // Code Lama:
        // $peminjaman->load('guest.user', 'paketRuangan.ruangan.gedung', 'user', 'invoice', 'detailSaranas.sarana');
        // Code Baru:
        $peminjaman->load('guest.user', 'paketRuangan.ruangan', 'user', 'invoice', 'detailSaranas.sarana');

        // Check if invoice exists
        $invoice = Invoice::where('peminjamanId', $id)->first();

        return view('main.transaksi.peminjaman.form', compact('peminjaman', 'invoice'));
    }

    /**
     * Approve peminjaman (POST request)
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'catatanApproval' => 'nullable|string|max:1000',
            'biayaTambahan' => 'nullable|numeric|min:0|max:99999999.99',
        ]);

        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        $biayaTambahan = $validated['biayaTambahan'] ?? 0.00;
        
        // Update approval status and biayaTambahan
        $peminjaman->update([
            'statusApproval' => 'APPROVED',
            'catatanApproval' => $validated['catatanApproval'] ?? null,
            'biayaTambahan' => $biayaTambahan,
            'tanggalApproval' => Carbon::now(),
        ]);

        // Sync with invoice
        $invoice = Invoice::where('peminjamanId', $peminjaman->id)->first();
        if ($invoice) {
            $invoice->update([
                'biayaTambahan' => $biayaTambahan,
                'totalHarga' => $invoice->subtotal + $biayaTambahan,
            ]);
        }

        // Kirim email notification
        $user = $peminjaman->user ?? User::where('guestId', $peminjaman->guestId)->first();
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new ReservationStatusMail($peminjaman, 'APPROVED'));
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email status reservasi (approve): ' . $e->getMessage());
            }
        }

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Peminjaman telah disetujui.');
    }

    /**
     * Reject peminjaman (POST request)
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'catatanApproval' => 'required|string|max:1000',
        ]);

        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        
        // Update rejection status
        $peminjaman->update([
            'statusApproval' => 'REJECTED',
            'catatanApproval' => $validated['catatanApproval'],
            'tanggalApproval' => Carbon::now(),
        ]);

        // Kirim email notification
        $user = $peminjaman->user ?? User::where('guestId', $peminjaman->guestId)->first();
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new ReservationStatusMail($peminjaman, 'REJECTED'));
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email status reservasi (reject): ' . $e->getMessage());
            }
        }

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Peminjaman telah ditolak.');
    }

    /**
     * Check-in peminjaman (POST request)
     */
    public function checkIn($id)
    {
        $peminjaman = PeminjamanTransaksi::findOrFail($id);

        if ($peminjaman->statusApproval !== 'APPROVED') {
            return redirect()->back()->with('error', 'Check-in hanya dapat dilakukan untuk peminjaman yang disetujui.');
        }

        $now = Carbon::now();
        $jamMulai = Carbon::parse($peminjaman->jamMulai);
        $minCheckInTime = $jamMulai->copy()->subHour();

        if ($now->lt($minCheckInTime)) {
            return redirect()->back()->with('error', 'Check-in baru bisa dilakukan paling cepat 1 jam sebelum tanggal dan jam mulai peminjaman (mulai pukul ' . $minCheckInTime->format('d M Y H:i') . ' WIB).');
        }

        $peminjaman->update([
            'statusPeminjaman' => 'CHECK_IN',
            'checkIn' => Carbon::now(),
        ]);

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Check-in berhasil diproses. Selamat menggunakan ruangan!');
    }

    /**
     * Check-out peminjaman (POST request)
     */
    public function checkOut(Request $request, $id)
    {
        $validated = $request->validate([
            'kondisiReturn' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_BERAT,HILANG',
            'catatanKerusakan' => 'nullable|string|max:1000',
            'estimasiDamage' => 'nullable|numeric|min:0|max:99999999.99',
            'biayaTambahan' => 'nullable|numeric|min:0|max:99999999.99',
        ]);

        $peminjaman = PeminjamanTransaksi::findOrFail($id);

        if ($peminjaman->statusPeminjaman !== 'CHECK_IN') {
            return redirect()->back()->with('error', 'Check-out hanya dapat dilakukan jika status transaksi sedang CHECK-IN.');
        }

        $peminjaman->update([
            'statusPeminjaman' => 'SELESAI',
            'checkOut' => Carbon::now(),
            'kondisiReturn' => $validated['kondisiReturn'],
            'catatanKerusakan' => $validated['catatanKerusakan'] ?? null,
            'estimasiDamage' => $validated['estimasiDamage'] ?? 0.00,
            'biayaTambahan' => $validated['biayaTambahan'] ?? 0.00,
        ]);

        // Update associated invoice if exists
        $invoice = Invoice::where('peminjamanId', $peminjaman->id)->first();
        if ($invoice) {
            $biayaTambahanTotal = ($validated['biayaTambahan'] ?? 0.00) + ($validated['estimasiDamage'] ?? 0.00);
            $invoice->update([
                'biayaTambahan' => $biayaTambahanTotal,
                'totalHarga' => $invoice->subtotal + $biayaTambahanTotal
            ]);
        }

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Check-out berhasil diproses. Peminjaman telah selesai.');
    }

    /**
     * Show form to create a new reservation
     */
    public function create()
    {
        // Code Lama:
        // $ruangans = \App\Models\Ruangan::with('gedung')->orderBy('nama_ruangan', 'asc')->get();
        // Code Baru:
        $ruangans = \App\Models\Ruangan::orderBy('nama_ruangan', 'asc')->get();
        $saranas = \App\Models\Sarana::orderBy('nama', 'asc')->get();
        return view('main.transaksi.peminjaman.create', compact('ruangans', 'saranas'));
    }

    /**
     * Check guest by NIK (AJAX Helper)
     */
    public function checkGuest($nik)
    {
        $guest = \App\Models\Guest::where('nik', $nik)->first();
        if ($guest) {
            return response()->json([
                'success' => true,
                'guest' => $guest
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Tamu dengan NIK ini belum terdaftar. Silakan daftarkan tamu terlebih dahulu.'
        ]);
    }

    /**
     * Get details of a specific room for AJAX (similar to UsersRuanganController)
     */
    public function getRuanganDetails($id)
    {
        // Code Lama:
        // $ruangan = \App\Models\Ruangan::with(['gedung', 'mediaFiles', 'paketRuangans' => function($q) { $q->where('status', 'ACTIVE'); }])->findOrFail($id);
        // Code Baru:
        $ruangan = \App\Models\Ruangan::with(['mediaFiles', 'paketRuangans' => function($q) {
            $q->where('status', 'ACTIVE');
        }])->findOrFail($id);

        $bookedRanges = PeminjamanTransaksi::whereHas('paketRuangan', function ($query) use ($id) {
                $query->where('ruangan_id', $id);
            })
            ->whereIn('statusApproval', ['APPROVED'])
            ->whereIn('statusPeminjaman', ['RESERVASI', 'CHECK_IN', 'SELESAI'])
            ->where('tanggal', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function($item) {
                $start = Carbon::parse($item->jamMulai);
                $itemIsHarian = ($item->paketRuangan && (stripos($item->paketRuangan->nama_paket, 'hari') !== false || stripos($item->paketRuangan->nama_paket, 'harian') !== false));
                if ($itemIsHarian) {
                    $end = $start->copy()->addDays($item->durasi);
                } else {
                    $end = $start->copy()->addHours($item->durasi);
                }
                return [
                    'start' => $start->format('Y-m-d H:i'),
                    'end' => $end->format('Y-m-d H:i'),
                    'label' => $start->format('d M Y H:i') . ' s/d ' . $end->format('d M Y H:i')
                ];
            });

        return response()->json([
            'id_ruangan' => $ruangan->id_ruangan,
            'nama_ruangan' => $ruangan->nama_ruangan,
            'tipe_ruangan' => str_replace('_', ' ', $ruangan->tipe_ruangan),
            // Code Lama:
            // 'gedung' => $ruangan->gedung->nama_gedung ?? '-',
            // Code Baru:
            'gedung' => '-',
            'kapasitas' => $ruangan->kapasitas,
            'packages' => $ruangan->paketRuangans,
            'photos' => $ruangan->mediaFiles,
            'booked' => $bookedRanges
        ]);
    }

    /**
     * Store new reservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16',
            'ruangan_id' => 'required|exists:ruangan,id_ruangan',
            'paket_id' => 'required|exists:paket_ruangan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'keperluan' => 'required|string|min:10|max:500',
            'estimasi_peserta' => 'required|integer|min:1',
            'sarana' => 'nullable|array',
            'sarana.*.sarana_id' => 'required|exists:sarana,id',
            'sarana.*.jumlah' => 'required|integer|min:1',
            'durasi_hari' => 'nullable|integer|min:1',
        ]);

        $guest = \App\Models\Guest::where('nik', $validated['nik'])->first();
        if (!$guest) {
            return back()->withErrors(['nik' => 'Tamu dengan NIK ini belum terdaftar. Silakan daftarkan tamu terlebih dahulu.'])->withInput();
        }

        $ruangan = \App\Models\Ruangan::findOrFail($validated['ruangan_id']);
        $paket = \App\Models\PaketRuangan::findOrFail($validated['paket_id']);

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
                $saranaModel = \App\Models\Sarana::findOrFail($item['sarana_id']);
                $availableStock = $saranaModel->getAvailableStock($startDateTime, $endDateTime);
                if ($item['jumlah'] > $availableStock) {
                    return back()->withErrors(['sarana' => 'Stok sarana "' . $saranaModel->nama . '" tidak mencukupi untuk jadwal tersebut. Tersedia: ' . $availableStock . ' unit.'])->withInput();
                }
            }
        }

        // Cek jika guest punya user account
        $userAccount = \App\Models\User::where('guestId', $guest->id)->first();
        $userId = $userAccount ? $userAccount->id : null;

        // 5. Simpan transaksi peminjaman baru (Otomatis APPROVED)
        $peminjaman = PeminjamanTransaksi::create([
            'kodePeminjaman' => 'PJM/' . Carbon::parse($validated['tanggal'])->format('Ymd') . '/' . sprintf('%04d', rand(1, 9999)),
            'guestId' => $guest->id,
            'facilityId' => $validated['paket_id'],
            'tanggal' => $validated['tanggal'],
            'jamMulai' => $startDateTime->format('Y-m-d H:i:s'),
            'durasi' => $durasi,
            'statusPeminjaman' => 'RESERVASI',
            'statusApproval' => 'APPROVED',
            'catatanApproval' => 'Dibuat langsung oleh Petugas.',
            'tanggalApproval' => Carbon::now(),
            'keterangan' => $validated['keperluan'],
            'biayaTambahan' => 0.00,
            'userId' => $userId
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
                \App\Models\DetailPeminjamanSarana::create([
                    'sarana_id' => $item['sarana_id'],
                    'peminjaman_id' => $peminjaman->id,
                    'jumlah' => (string)$item['jumlah'],
                ]);
            }
        }

        return redirect()->route('main.transaksi.peminjaman.index')
            ->with('success', 'Reservasi tamu berhasil dibuat dengan status APPROVED.');
    }
}
