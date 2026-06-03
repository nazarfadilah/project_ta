<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarDashboard extends Model
{
    protected $table = 'gambar_dashboard';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'posisi',
        'path',
        'waktu_upload',
    ];

    protected $casts = [
        'posisi' => 'integer',
        'waktu_upload' => 'datetime',
    ];
}
