<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tentang extends Model
{
    protected $table = 'tentang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_instansi',
        'kordinat_x',
        'kordinat_y',
        'no_hp',
        'kantor',
        'email',
        'logo_instansi',
        'foto_instansi',
        'link_google_maps',
    ];
}
