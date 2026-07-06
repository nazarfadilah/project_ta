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
            $table->integer('durasi')->nullable();
            $table->enum('statusPeminjaman', ['RESERVASI','CHECK_IN','CHECK_OUT','BATAL','SELESAI'])->default('RESERVASI');
            $table->text('keterangan')->nullable();
            $table->text('alasan_pembatalan')->nullable();
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