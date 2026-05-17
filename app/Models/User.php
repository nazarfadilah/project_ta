<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'email',
        'password',
        'roleId',
        'phone',
        'guestId',
        'status',
        'lastLoginAt',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'lastLoginAt' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roleId', 'id');
    }

    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class, 'userId', 'id');
    }
}
