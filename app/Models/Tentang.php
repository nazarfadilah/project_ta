<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tentang extends Model
{
    protected $table = 'tentang';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'key',
        'key2',
        'value',
    ];
}
