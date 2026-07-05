<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\DetailPeminjamanSarana;
// use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Sarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // ─── DASHBOARD ────────────────────────────────────────────
    public function dashboard()
    {
        $email = Auth::guard('web')->user()->email_users;

        // Code Lama:
        // $peminjamanTerbaru = PeminjamanTransaksi::where('email_users', $email)->with(['ruangan.gedung'])->orderByDesc('tgl_peminjaman')->limit(6)->get();
        // Code Baru:
        $peminjamanTerbaru = PeminjamanTransaksi::where('email_users', $email)
            ->with(['ruangan'])
            ->orderByDesc('tgl_peminjaman')
            ->limit(6)
            ->get();

        $stats = [
            'total' => PeminjamanTransaksi::where('email_users', $email)->count(),
            'menunggu' => PeminjamanTransaksi::where('email_users', $email)->where('status_peminjaman', 'Diajukan')->count(),
            'disetujui' => PeminjamanTransaksi::where('email_users', $email)->where('status_peminjaman', 'Disetujui')->count(),
        ];

        return view('user.dashboard', compact('peminjamanTerbaru', 'stats'));
    }

    // ─── RIWAYAT ──────────────────────────────────────────────
    public function history()
    {
        $email = Auth::guard('web')->user()->email_users;
        // Code Lama:
        // $peminjamanList = PeminjamanTransaksi::where('email_users', $email)->with(['ruangan.gedung', 'detailSaranas.sarana'])->orderByDesc('tgl_peminjaman')->paginate(10);
        // Code Baru:
        $peminjamanList = PeminjamanTransaksi::where('email_users', $email)
            ->with(['ruangan', 'detailSaranas.sarana'])
            ->orderByDesc('tgl_peminjaman')
            ->paginate(10);

        return view('user.history', compact('peminjamanList'));
    }

    // ─── BOOKING FORM ─────────────────────────────────────────
    public function bookingCreate()
    {
        // Code Lama:
        // $gedungs = Gedung::orderBy('nama')->get();
        // $ruangans = Ruangan::with('gedung')->orderBy('nama_ruangan')->get();
        // Code Baru:
        $gedungs = collect();
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        $saranas = Sarana::where('stok', '>', 0)->orderBy('nama')->get();

        return view('user.booking', compact('gedungs', 'ruangans', 'saranas'));
    }

    // ─── BOOKING STORE ────────────────────────────────────────
    public function bookingStore(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'tgl_peminjaman' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'nama_kegiatan' => 'required|string|max:128',
        ]);

        $email = Auth::guard('web')->user()->email_users;

        // Cek konflik jadwal
        $konflik = PeminjamanTransaksi::where('ruangan_id', $request->ruangan_id)
            ->where('tgl_peminjaman', $request->tgl_peminjaman)
            ->where('status_peminjaman', 'Disetujui')
            ->where(function ($q) use ($request) {
                $q->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_selesai])
                    ->orWhereBetween('waktu_selesai', [$request->waktu_mulai, $request->waktu_selesai]);
            })->exists();

        if ($konflik) {
            return back()->withErrors(['ruangan_id' => 'Ruangan ini sudah dipesan pada jadwal tersebut.'])->withInput();
        }

        $peminjaman = PeminjamanTransaksi::create([
            'email_users' => $email,
            'ruangan_id' => $request->ruangan_id,
            'nama_kegiatan' => $request->nama_kegiatan,
            'tgl_peminjaman' => $request->tgl_peminjaman,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'keterangan' => $request->keterangan,
            'status_peminjaman' => 'Diajukan',
            'status_sarana' => 'Menunggu Verifikasi',
        ]);

        // Detail sarana
        if ($request->sarana) {
            foreach ($request->sarana as $saranaData) {
                if (!empty($saranaData['id'])) {
                    DetailPeminjamanSarana::create([
                        'peminjaman_id' => $peminjaman->id,
                        'sarana_id' => $saranaData['id'],
                        'jumlah' => $saranaData['jumlah'] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('user.history')
            ->with('success', 'Peminjaman berhasil diajukan! Menunggu persetujuan admin.');
    }

}
