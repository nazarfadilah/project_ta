<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanTransaksi extends Model {
    use HasFactory;
    protected $table = 'peminjaman_transaksi';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $casts = [
        'tanggal' => 'date',
        'jamMulai' => 'datetime',
        'checkIn' => 'datetime',
        'checkOut' => 'datetime',
        'tanggalApproval' => 'datetime',
    ];

    public function guest() {
        return $this->belongsTo(Guest::class, 'guestId', 'id');
    }

    public function paketRuangan() {
        return $this->belongsTo(PaketRuangan::class, 'facilityId', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function invoice() {
        return $this->hasOne(Invoice::class, 'peminjamanId', 'id');
    }

    public function detailSaranas() {
        return $this->hasMany(DetailPeminjamanSarana::class, 'peminjaman_id', 'id');
    }
}