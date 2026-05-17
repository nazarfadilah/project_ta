<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketRuangan extends Model {
    use HasFactory;
    protected $table = 'paket_ruangan';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function ruangan() {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id_ruangan');
    }

    public function peminjamanTransaksis() {
        return $this->hasMany(PeminjamanTransaksi::class, 'facilityId', 'id');
    }
}