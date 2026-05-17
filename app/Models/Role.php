<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    use HasFactory;
    protected $table = 'role';
    protected $guarded = [];
    protected $casts = [
        'permissions' => 'array'
    ];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function users() {
        return $this->hasMany(User::class, 'roleId', 'id');
    }
}