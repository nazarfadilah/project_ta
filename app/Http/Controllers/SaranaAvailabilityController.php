<?php
namespace App\Http\Controllers;

use App\Models\Sarana;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SaranaAvailabilityController extends Controller
{
    /**
     * Cek stok tersedia untuk sarana pada date range tertentu
     * GET /sarana/availability/check
     * Query params:
     * - sarana_id: ID sarana (required)
     * - start_date: Tanggal mulai (format: Y-m-d) (required)
     * - end_date: Tanggal selesai (format: Y-m-d) (required)
     * - jumlah: Jumlah yang akan dipinjam (optional, untuk validasi)
     */
    public function check(Request $request)
    {
        $request->validate([
            'sarana_id' => 'required|exists:sarana,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $sarana = Sarana::findOrFail($request->sarana_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $requestedQty = (int)$request->jumlah ?? 0;

        $available = $sarana->getAvailableStock($startDate, $endDate);
        $overlappingLoans = $sarana->getOverlappingLoans($startDate, $endDate);

        $borrowed = $overlappingLoans->sum('jumlah');
        $canBorrow = $available >= $requestedQty;

        return response()->json([
            'success' => true,
            'sarana' => [
                'id' => $sarana->id,
                'nama' => $sarana->nama,
                'kondisi' => $sarana->kondisi,
            ],
            'stok' => [
                'total' => (int)$sarana->stok,
                'borrowed' => $borrowed,
                'available' => $available,
            ],
            'requested_qty' => $requestedQty,
            'can_borrow' => $canBorrow,
            'date_range' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'duration_days' => $endDate->diffInDays($startDate) + 1,
            ],
            'overlapping_loans' => $overlappingLoans->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'peminjaman_id' => $loan->peminjaman_id,
                    'jumlah' => (int)$loan->jumlah,
                    'tanggal' => $loan->peminjamanTransaksi->tanggal,
                    'jam_mulai' => $loan->peminjamanTransaksi->jamMulai,
                    'durasi_jam' => $loan->peminjamanTransaksi->durasi,
                ];
            })->values(),
            'message' => $canBorrow 
                ? "Stok tersedia: {$available} unit" 
                : "Stok tidak cukup. Tersedia: {$available} unit, diminta: {$requestedQty} unit",
        ]);
    }

    /**
     * List semua sarana dengan stok tersedia untuk date range tertentu
     * GET /sarana/availability/list
     * Query params:
     * - start_date: Tanggal mulai (format: Y-m-d) (required)
     * - end_date: Tanggal selesai (format: Y-m-d) (required)
     * - per_page: Items per halaman (default: 20)
     */
    public function listAvailable(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $perPage = (int)$request->per_page ?? 20;

        $saranas = Sarana::all()->map(function ($sarana) use ($startDate, $endDate) {
            $available = $sarana->getAvailableStock($startDate, $endDate);
            $borrowed = $sarana->getOverlappingLoans($startDate, $endDate)->sum('jumlah');

            return [
                'id' => $sarana->id,
                'nama' => $sarana->nama,
                'kondisi' => $sarana->kondisi,
                'tgl_penerimaan' => $sarana->tgl_penerimaan,
                'stok_total' => (int)$sarana->stok,
                'stok_borrowed' => $borrowed,
                'stok_available' => $available,
                'available_text' => "{$available} dari {$sarana->stok} unit",
            ];
        })->values();

        return response()->json([
            'success' => true,
            'date_range' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'duration_days' => $endDate->diffInDays($startDate) + 1,
            ],
            'total_items' => count($saranas),
            'per_page' => $perPage,
            'data' => $saranas->chunk($perPage)->values(),
        ]);
    }
}
