<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model {
    use HasFactory;
    protected $table = 'guest';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function peminjamanTransaksis() {
        return $this->hasMany(PeminjamanTransaksi::class, 'guestId', 'id');
    }
}