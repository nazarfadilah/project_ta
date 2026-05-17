<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarana extends Model {
    use HasFactory;
    protected $table = 'sarana';
    protected $guarded = [];

    public function detailPeminjamanSaranas() {
        return $this->hasMany(DetailPeminjamanSarana::class, 'sarana_id', 'id');
    }
}