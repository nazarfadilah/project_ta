<?php

$models = [
    'ActivityLog' => <<<'EOT'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    use HasFactory;
    protected $table = 'activity_log';
    protected $guarded = [];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
EOT,
    'Gedung' => <<<'EOT'
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
EOT,
    'Ruangan' => <<<'EOT'
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

    public function gedung() {
        return $this->belongsTo(Gedung::class, 'gedung_id', 'id_gedung');
    }

    public function paketRuangans() {
        return $this->hasMany(PaketRuangan::class, 'ruangan_id', 'id_ruangan');
    }

    public function mediaFiles() {
        return $this->hasMany(MediaFile::class, 'ruangan_id', 'id_ruangan');
    }
}
EOT,
    'PaketRuangan' => <<<'EOT'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketRuangan extends Model {
    use HasFactory;
    protected $table = 'paket_ruangan';
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function ruangan() {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id_ruangan');
    }

    public function peminjamanTransaksis() {
        return $this->hasMany(PeminjamanTransaksi::class, 'facilityId', 'id');
    }
}
EOT,
    'Guest' => <<<'EOT'
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
EOT,
    'PeminjamanTransaksi' => <<<'EOT'
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
EOT,
    'Invoice' => <<<'EOT'
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
EOT,
    'Sarana' => <<<'EOT'
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
EOT,
    'DetailPeminjamanSarana' => <<<'EOT'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanSarana extends Model {
    use HasFactory;
    protected $table = 'detail_peminjaman_sarana';
    protected $guarded = [];

    public function sarana() {
        return $this->belongsTo(Sarana::class, 'sarana_id', 'id');
    }

    public function peminjamanTransaksi() {
        return $this->belongsTo(PeminjamanTransaksi::class, 'peminjaman_id', 'id');
    }
}
EOT,
    'MediaFile' => <<<'EOT'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model {
    use HasFactory;
    protected $table = 'media_file';
    protected $guarded = [];

    public function ruangan() {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id_ruangan');
    }
}
EOT,
    'Role' => <<<'EOT'
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
EOT
];

foreach ($models as $name => $content) {
    file_put_contents(__DIR__ . '/app/Models/' . $name . '.php', $content);
}

// Update User Model as well
$userModelPath = __DIR__ . '/app/Models/User.php';
if (file_exists($userModelPath)) {
    $userModelContent = file_get_contents($userModelPath);
    // Add relationships if not present
    if (strpos($userModelContent, 'public function role()') === false) {
        $userModelContent = preg_replace('/}\s*$/', "
    public function role() {
        return \$this->belongsTo(Role::class, 'roleId', 'id');
    }
    public function guest() {
        return \$this->belongsTo(Guest::class, 'guestId', 'id');
    }
    public function activityLogs() {
        return \$this->hasMany(ActivityLog::class, 'userId', 'id');
    }
    public function peminjamanTransaksis() {
        return \$this->hasMany(PeminjamanTransaksi::class, 'userId', 'id');
    }
    public function beritas() {
        return \$this->hasMany(Berita::class, 'user_id', 'id');
    }
}
", $userModelContent);
        file_put_contents($userModelPath, $userModelContent);
    }
}

echo "Models generated with relationships.\n";
