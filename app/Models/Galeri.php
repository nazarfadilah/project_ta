<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'kategori',
        'judul',
        'isi',
        'gambar',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Accessor for kategori column.
     * Maps 'pengapian' (database typo) to 'penginapan'.
     */
    public function getKategoriAttribute($value)
    {
        return $value === 'pengapian' ? 'penginapan' : $value;
    }

    /**
     * Mutator for kategori column.
     * Maps 'penginapan' back to 'pengapian' before saving to database.
     */
    public function setKategoriAttribute($value)
    {
        $this->attributes['kategori'] = $value === 'penginapan' ? 'pengapian' : $value;
    }
}
