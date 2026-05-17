<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    use HasFactory;
    protected $table = 'invoice';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function peminjamanTransaksi() {
        return $this->belongsTo(PeminjamanTransaksi::class, 'peminjamanId', 'id');
    }
}