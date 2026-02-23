<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email_admin',
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
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'email_admin', 'email_admin');
    }
}
