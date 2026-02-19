<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profil extends Model
{
    protected $table = 'profil';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email_admin',
        'email_users',
        'foto',
        'jenis_kelamin',
        'alamat_users',
        'tanggal_lahir',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'email_admin', 'email_admin');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email_users', 'email_users');
    }
}
