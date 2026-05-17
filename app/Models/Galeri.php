<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'kategori',
        'judul',
        'isi',
        'gambar',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
