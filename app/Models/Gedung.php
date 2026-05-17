<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model {
    use HasFactory;
    protected $table = 'gedung';
    protected $primaryKey = 'id_gedung';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function ruangans() {
        return $this->hasMany(Ruangan::class, 'gedung_id', 'id_gedung');
    }
}