<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use DB;

class Sarana extends Model
{
    protected $table = 'sarana';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'kondisi',
        'tgl_penerimaan',
        'stok',
    ];

    protected $casts = [
        'tgl_penerimaan' => 'date',
    ];

    public function detail_peminjaman_saranas(): HasMany
    {
        return $this->hasMany(DetailPeminjamanSarana::class, 'sarana_id', 'id');
    }

    // ===== SCOPES =====

    /**
     * Scope untuk filter berdasarkan kondisi
     */
    public function scopeByKondisi(Builder $query, $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    /**
     * Scope untuk filter sarana dengan stok tertentu
     */
    public function scopeMinimumStok(Builder $query, $minStok)
    {
        return $query->where('stok', '>=', $minStok);
    }

    /**
     * Scope untuk filter sarana yang mulai habis
     */
    public function scopeLowStock(Builder $query, $threshold = 3)
    {
        return $query->whereRaw('stok <= ?', [$threshold]);
    }

    /**
     * Scope untuk mencari berdasarkan nama
     */
    public function scopeSearch(Builder $query, $keyword)
    {
        return $query->where('nama', 'like', '%' . $keyword . '%');
    }

    // ===== METHODS =====

    /**
     * Hitung stok yang tersedia pada tanggal tertentu
     */
    public function getStokTersedia($tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = now()->format('Y-m-d');
        }

        $stokDipinjam = DetailPeminjamanSarana::query()
            ->join('peminjaman_transaksi', 'detail_peminjaman_saranas.peminjaman_id', '=', 'peminjaman_transaksi.id')
            ->where('detail_peminjaman_saranas.sarana_id', $this->id)
            ->where('peminjaman_transaksi.tgl_peminjaman', '<=', $tanggal)
            ->where(function ($q) use ($tanggal) {
                $q->whereNull('peminjaman_transaksi.tgl_pengembalian')
                  ->orWhere('peminjaman_transaksi.tgl_pengembalian', '>=', $tanggal);
            })
            ->where(function ($q) {
                $q->where('peminjaman_transaksi.status_peminjaman', '=', 'Disetujui')
                  ->orWhere('peminjaman_transaksi.status_peminjaman', '=', 'Diajukan');
            })
            ->sum('detail_peminjaman_saranas.jumlah') ?? 0;

        return max(0, $this->stok - $stokDipinjam);
    }

    /**
     * Hitung stok yang sedang dipinjam pada tanggal tertentu
     */
    public function getStokDipinjam($tanggal = null)
    {
        if (!$tanggal) {
            $tanggal = now()->format('Y-m-d');
        }

        return DetailPeminjamanSarana::query()
            ->join('peminjaman_transaksi', 'detail_peminjaman_saranas.peminjaman_id', '=', 'peminjaman_transaksi.id')
            ->where('detail_peminjaman_saranas.sarana_id', $this->id)
            ->where('peminjaman_transaksi.tgl_peminjaman', '<=', $tanggal)
            ->where(function ($q) use ($tanggal) {
                $q->whereNull('peminjaman_transaksi.tgl_pengembalian')
                  ->orWhere('peminjaman_transaksi.tgl_pengembalian', '>=', $tanggal);
            })
            ->where(function ($q) {
                $q->where('peminjaman_transaksi.status_peminjaman', '=', 'Disetujui')
                  ->orWhere('peminjaman_transaksi.status_peminjaman', '=', 'Diajukan');
            })
            ->sum('detail_peminjaman_saranas.jumlah') ?? 0;
    }

    /**
     * Check apakah sarana dapat dipinjam dengan jumlah tertentu pada tanggal tertentu
     */
    public function canBeBorrowed($jumlah, $tanggal = null)
    {
        return $this->getStokTersedia($tanggal) >= $jumlah;
    }
}
