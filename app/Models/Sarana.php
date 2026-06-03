<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Sarana extends Model {
    use HasFactory;
    protected $table = 'sarana';
    protected $guarded = [];

    public function detailPeminjamanSaranas() {
        return $this->hasMany(DetailPeminjamanSarana::class, 'sarana_id', 'id');
    }

    /**
     * Hitung stok tersedia untuk sarana pada date range tertentu
     * Dengan precision hingga jam (jamMulai + durasi)
     * 
     * @param Carbon|string $startDate Tanggal mulai peminjaman (00:00)
     * @param Carbon|string $endDate Tanggal selesai peminjaman (23:59)
     * @return int Jumlah stok tersedia
     */
    public function getAvailableStock($startDate, $endDate) {
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        // Hitung total jumlah yang sudah dipinjam dalam date range tersebut
        $borrowed = DetailPeminjamanSarana::where('sarana_id', $this->id)
            ->with('peminjamanTransaksi')
            ->get()
            ->filter(function ($detail) use ($startDate, $endDate) {
                $tx = $detail->peminjamanTransaksi;
                if (!$tx) {
                    return false;
                }
                if ($tx->statusApproval === 'REJECTED' || $tx->statusPeminjaman === 'BATAL') {
                    return false;
                }
                
                // Gunakan jamMulai (bukan startOfDay) untuk akurasi jam
                $pemStart = Carbon::parse($detail->peminjamanTransaksi->jamMulai);
                $pemEnd = $pemStart->copy()->addHours($detail->peminjamanTransaksi->durasi ?? 24);
                
                // Cek overlapping: tidak overlap jika pemEnd <= requestStart OR pemStart >= requestEnd
                // Overlap jika: pemEnd > requestStart AND pemStart < requestEnd
                return $pemEnd->greaterThan($startDate) && $pemStart->lessThan($endDate);
            })
            ->sum('jumlah');

        return max(0, (int)$this->stok - $borrowed);
    }

    /**
     * Cek detail peminjaman yang overlap dengan date range
     * Dengan precision hingga jam (jamMulai + durasi)
     * 
     * @param Carbon|string $startDate
     * @param Carbon|string $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOverlappingLoans($startDate, $endDate) {
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        return DetailPeminjamanSarana::where('sarana_id', $this->id)
            ->with('peminjamanTransaksi')
            ->get()
            ->filter(function ($detail) use ($startDate, $endDate) {
                if (!$detail->peminjamanTransaksi) {
                    return false;
                }
                
                // Gunakan jamMulai (bukan startOfDay) untuk akurasi jam
                $pemStart = Carbon::parse($detail->peminjamanTransaksi->jamMulai);
                $pemEnd = $pemStart->copy()->addHours($detail->peminjamanTransaksi->durasi ?? 24);
                
                // Overlap jika: pemEnd > requestStart AND pemStart < requestEnd
                return $pemEnd->greaterThan($startDate) && $pemStart->lessThan($endDate);
            });
    }
}