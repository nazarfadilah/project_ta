<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'email_admin';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email_admin',
        'name_admin',
        'password_admin',
        'no_hp_admin',
        'role',
        'gedung_id',
    ];

    protected $hidden = [
        'password_admin',
    ];

    public function profil(): HasMany
    {
        return $this->hasMany(Profil::class, 'email_admin', 'email_admin');
    }

    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class, 'gedung_id', 'id');
    }

    public function gambar_dashboards(): HasMany
    {
        return $this->hasMany(GambarDashboard::class, 'email_admin', 'email_admin');
    }

    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class, 'email_admin', 'email_admin');
    }

    public function peminjaman_transaksis(): HasMany
    {
        return $this->hasMany(PeminjamanTransaksi::class, 'email_admin', 'email_admin');
    }
}



