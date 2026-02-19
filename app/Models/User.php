<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'email_users';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email_users',
        'name_users',
        'password_users',
        'no_hp_users',
    ];

    protected $hidden = [
        'password_users',
    ];

    public function profil(): HasMany
    {
        return $this->hasMany(Profil::class, 'email_users', 'email_users');
    }

    public function peminjaman_transaksis(): HasMany
    {
        return $this->hasMany(PeminjamanTransaksi::class, 'email_users', 'email_users');
    }
}
