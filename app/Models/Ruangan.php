<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'gedung_id',
        'nama_ruangan',
    ];

    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class, 'gedung_id', 'id');
    }

    public function peminjaman_transaksis(): HasMany
    {
        return $this->hasMany(PeminjamanTransaksi::class, 'ruangan_id', 'id');
    }
}
