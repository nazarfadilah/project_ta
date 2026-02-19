<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GambarDashboard extends Model
{
    protected $table = 'gambar_dashboard';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email_admin',
        'posisi',
        'path',
        'waktu_upload',
    ];

    protected $casts = [
        'posisi' => 'boolean',
        'waktu_upload' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'email_admin', 'email_admin');
    }
}
