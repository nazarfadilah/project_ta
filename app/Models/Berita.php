<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';

    protected $fillable = [
        'userId',
        'judul',
        'slug',
        'isi',
        'gambar',
        'tanggal_publish',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_publish' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
