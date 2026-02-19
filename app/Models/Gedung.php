<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gedung extends Model
{
    protected $table = 'gedung';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'kordinat_y',
        'kordinat_x',
        'lokasi',
    ];

    public function ruangans(): HasMany
    {
        return $this->hasMany(Ruangan::class, 'gedung_id', 'id');
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class, 'gedung_id', 'id');
    }
}
