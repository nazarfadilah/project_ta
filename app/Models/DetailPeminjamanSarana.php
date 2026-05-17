<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanSarana extends Model {
    use HasFactory;
    protected $table = 'detail_peminjaman_sarana';
    protected $guarded = [];

    public function sarana() {
        return $this->belongsTo(Sarana::class, 'sarana_id', 'id');
    }

    public function peminjamanTransaksi() {
        return $this->belongsTo(PeminjamanTransaksi::class, 'peminjaman_id', 'id');
    }
}