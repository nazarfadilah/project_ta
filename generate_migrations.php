<?php

$migrations = [
    '2026_05_17_000001_create_gedungs_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('gedung', function (Blueprint $table) {
            $table->integer('id_gedung')->autoIncrement();
            $table->string('nama_gedung', 255);
            $table->string('koordinat', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('gedung');
    }
};
EOT,
    '2026_05_17_000002_create_ruangans_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->integer('id_ruangan')->autoIncrement();
            $table->integer('gedung_id');
            $table->string('nama_ruangan', 255);
            $table->enum('tipe_ruangan', ['KAMAR_STANDAR','KAMAR_VIP','KAMAR_PREMIUM','AULA','RUANG_MEETING','RUANG_LAINNYA'])->default('KAMAR_STANDAR');
            $table->integer('lantai')->nullable();
            $table->integer('kapasitas')->default(1);
            $table->enum('gender_policy', ['MALE_ONLY','FEMALE_ONLY','MIXED'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('gedung_id')->references('id_gedung')->on('gedung')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('ruangan');
    }
};
EOT,
    '2026_05_17_000003_create_paket_ruangans_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('paket_ruangan', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('ruangan_id');
            $table->string('nama_paket', 255);
            $table->integer('durasi')->nullable();
            $table->decimal('harga', 15, 2);
            $table->string('currency', 3)->default('IDR');
            $table->boolean('isExclusive')->default(0);
            $table->enum('status', ['ACTIVE','INACTIVE','MAINTENANCE'])->default('ACTIVE');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('ruangan_id')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('paket_ruangan');
    }
};
EOT,
    '2026_05_17_000004_create_guests_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('guest', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->char('nik', 16)->unique();
            $table->string('name', 255);
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->text('address')->nullable();
            $table->string('bloodType', 5)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('guest');
    }
};
EOT,
    '2026_05_17_000005_create_peminjaman_transaksis_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('peminjaman_transaksi', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('kodePeminjaman', 50)->unique();
            $table->integer('guestId');
            $table->integer('facilityId');
            $table->date('tanggal');
            $table->dateTime('jamMulai');
            $table->dateTime('checkIn')->nullable();
            $table->dateTime('checkOut')->nullable();
            $table->integer('durasi');
            $table->enum('statusPeminjaman', ['RESERVASI','CHECK_IN','CHECK_OUT','BATAL','SELESAI'])->default('RESERVASI');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            $table->enum('statusApproval', ['PENDING','APPROVED','REJECTED'])->default('PENDING');
            $table->text('catatanApproval')->nullable();
            $table->dateTime('tanggalApproval')->nullable();
            $table->decimal('biayaTambahan', 15, 2)->default(0);
            $table->enum('kondisiReturn', ['BAIK','RUSAK_RINGAN','RUSAK_BERAT','HILANG'])->nullable();
            $table->text('catatanKerusakan')->nullable();
            $table->decimal('estimasiDamage', 15, 2)->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('guestId')->references('id')->on('guest');
            $table->foreign('userId')->references('id')->on('users')->onDelete('set null');
            $table->foreign('facilityId')->references('id')->on('paket_ruangan');
        });
    }
    public function down(): void {
        Schema::dropIfExists('peminjaman_transaksi');
    }
};
EOT,
    '2026_05_17_000006_create_invoices_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('invoice', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('noInvoice', 50)->unique();
            $table->integer('peminjamanId')->unique();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('biayaTambahan', 15, 2)->default(0);
            $table->decimal('totalHarga', 15, 2);
            $table->enum('statusInvoice', ['UNPAID','PARTIAL','PAID','OVERDUE'])->default('UNPAID');
            $table->enum('status_pembayaran', ['BELUM_BAYAR','SEBAGIAN','LUNAS'])->default('BELUM_BAYAR');
            $table->dateTime('tglInvoice')->useCurrent();
            $table->dateTime('tglDueDate')->nullable();
            $table->dateTime('tgl_pembayaran')->nullable();
            $table->dateTime('tglPaid')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('peminjamanId')->references('id')->on('peminjaman_transaksi')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('invoice');
    }
};
EOT,
    '2026_05_17_000007_create_saranas_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('sarana', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 128);
            $table->enum('kondisi', ['Baik','Baik Sekali','Normal','Perlu Perbaikan'])->default('Baik');
            $table->date('tgl_penerimaan');
            $table->string('stok', 5);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sarana');
    }
};
EOT,
    '2026_05_17_000008_create_detail_peminjaman_saranas_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('detail_peminjaman_sarana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sarana_id');
            $table->integer('peminjaman_id');
            $table->string('jumlah', 5);
            $table->timestamps();
            
            $table->foreign('sarana_id')->references('id')->on('sarana')->onDelete('cascade');
            $table->foreign('peminjaman_id')->references('id')->on('peminjaman_transaksi')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('detail_peminjaman_sarana');
    }
};
EOT,
    '2026_05_17_000009_create_media_files_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('media_file', function (Blueprint $table) {
            $table->id();
            $table->integer('ruangan_id');
            $table->string('path', 255);
            $table->timestamps();
            
            $table->foreign('ruangan_id')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('media_file');
    }
};
EOT,
    '2026_05_17_000010_create_activity_logs_table.php' => <<<'EOT'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->unsignedBigInteger('userId')->nullable();
            $table->string('action', 100);
            $table->string('tabelNama', 100);
            $table->string('recordId', 100);
            $table->json('detailPerubahan')->nullable();
            $table->string('ipAddress', 50)->nullable();
            $table->timestamp('createdAt')->useCurrent();
            
            $table->foreign('userId')->references('id')->on('users')->onDelete('set null');
        });
    }
    public function down(): void {
        Schema::dropIfExists('activity_log');
    }
};
EOT
];

foreach ($migrations as $file => $content) {
    file_put_contents(__DIR__ . '/database/migrations/' . $file, $content);
}

// Clean up old unordered ones
$oldFiles = glob(__DIR__ . '/database/migrations/2026_05_17_000000_*.php');
foreach($oldFiles as $f) {
    unlink($f);
}

echo "Migrations generated with correct ordering.\n";
