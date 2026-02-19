<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjamanSarana extends Model
{
    protected $table = 'detail_peminjaman_sarana';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sarana_id',
        'peminjaman_id',
        'jumlah',
    ];

    public function sarana(): BelongsTo
    {
        return $this->belongsTo(Sarana::class, 'sarana_id', 'id');
    }

    public function peminjaman_transaksi(): BelongsTo
    {
        return $this->belongsTo(PeminjamanTransaksi::class, 'peminjaman_id', 'id');
    }
}
