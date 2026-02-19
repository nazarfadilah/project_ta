<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class PeminjamanTransaksi extends Model
{
    protected $table = 'peminjaman_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email_users',
        'ruangan_id',
        'nama_kegiatan',
        'tgl_peminjaman',
        'tgl_pengembalian',
        'waktu_mulai',
        'waktu_selesai',
        'status_peminjaman',
        'status_sarana',
        'keterangan',
        'email_admin',
    ];

    protected $casts = [
        'tgl_peminjaman' => 'date',
        'tgl_pengembalian' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email_users', 'email_users');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'email_admin', 'email_admin');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id');
    }

    public function detail_peminjaman_saranas(): HasMany
    {
        return $this->hasMany(DetailPeminjamanSarana::class, 'peminjaman_id', 'id');
    }

    // ===== SCOPES =====

    /**
     * Scope untuk filter berdasarkan status peminjaman
     */
    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status_peminjaman', $status);
    }

    /**
     * Scope untuk filter peminjaman yang sedang aktif (Disetujui dan belum dikembalikan)
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status_peminjaman', 'Disetujui')
                     ->whereNull('tgl_pengembalian');
    }

    /**
     * Scope untuk filter berdasarkan tanggal range
     */
    public function scopeByTanggalRange(Builder $query, $dari, $sampai)
    {
        return $query->whereBetween('tgl_peminjaman', [$dari, $sampai]);
    }

    /**
     * Scope untuk filter berdasarkan gedung
     */
    public function scopeByGedung(Builder $query, $gedungId)
    {
        return $query->whereHas('ruangan', function ($q) use ($gedungId) {
            $q->where('gedung_id', $gedungId);
        });
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser(Builder $query, $emailUsers)
    {
        return $query->where('email_users', $emailUsers);
    }

    /**
     * Scope untuk filter peminjaman yang memiliki konflik tanggal
     */
    public function scopeWithDateConflict(Builder $query, $tanggalAwal, $tanggalAkhir, $ruanganId)
    {
        return $query->where('ruangan_id', $ruanganId)
                     ->where(function ($q) use ($tanggalAwal, $tanggalAkhir) {
                         $q->whereBetween('tgl_peminjaman', [$tanggalAwal, $tanggalAkhir])
                           ->orWhereBetween('tgl_pengembalian', [$tanggalAwal, $tanggalAkhir])
                           ->orWhere(function ($q2) use ($tanggalAwal, $tanggalAkhir) {
                               $q2->where('tgl_peminjaman', '<=', $tanggalAwal)
                                  ->where(function ($q3) use ($tanggalAkhir) {
                                      $q3->whereNull('tgl_pengembalian')
                                         ->orWhere('tgl_pengembalian', '>=', $tanggalAkhir);
                                  });
                           });
                     })
                     ->where(function ($q) {
                         $q->where('status_peminjaman', 'Disetujui')
                           ->orWhere('status_peminjaman', 'Diajukan');
                     });
    }
}

