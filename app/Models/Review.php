<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'transaksi_id',
        'rating',
        'komentar',
        'foto_ulasan',
    ];

    /**
     * Get the transaction associated with the review.
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(PeminjamanTransaksi::class, 'transaksi_id', 'id');
    }
}
