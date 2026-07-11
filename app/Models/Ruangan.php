<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model {
    use HasFactory;
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /*
    public function gedung() {
        return $this->belongsTo(Gedung::class, 'gedung_id', 'id_gedung');
    }
    */

    public function paketRuangans() {
        return $this->hasMany(PaketRuangan::class, 'ruangan_id', 'id_ruangan');
    }

    public function mediaFiles() {
        return $this->hasMany(MediaFile::class, 'ruangan_id', 'id_ruangan');
    }

    public function getAverageRatingAttribute() {
        $paketIds = $this->paketRuangans->pluck('id');
        if ($paketIds->isEmpty()) {
            return 0;
        }
        return Review::whereIn('transaksi_id', function ($query) use ($paketIds) {
            $query->select('id')
                ->from('peminjaman_transaksi')
                ->whereIn('facilityId', $paketIds);
        })->avg('rating') ?: 0;
    }

    public function getReviewsCountAttribute() {
        $paketIds = $this->paketRuangans->pluck('id');
        if ($paketIds->isEmpty()) {
            return 0;
        }
        return Review::whereIn('transaksi_id', function ($query) use ($paketIds) {
            $query->select('id')
                ->from('peminjaman_transaksi')
                ->whereIn('facilityId', $paketIds);
        })->count();
    }
}