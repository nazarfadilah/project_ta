<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman_transaksi', function (Blueprint $table) {
            $table->id('id');
            $table->string('email_users', 128);
            $table->unsignedBigInteger('ruangan_id');
            $table->string('nama_kegiatan', 128);
            $table->date('tgl_peminjaman');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->enum('status_peminjaman', ['Diajukan', 'Disetujui', 'Dibatalkan', 'Ditolak'])->default('Diajukan');
            $table->text('keterangan')->nullable();
            $table->enum('status_sarana', ['Menunggu Verifikasi','Disiapkan', 'Siap Pakai'])->default('Menunggu Verifikasi');
            $table->string('email_admin', 128)->nullable();
            $table->date('tgl_verifikasi')->nullable();
            $table->timestamps();
            $table->foreign('email_users')->references('email_users')->on('users')->onDelete('restrict');
            $table->foreign('ruangan_id')->references('id')->on('ruangan')->onDelete('restrict');
            $table->foreign('email_admin')->references('email_admin')->on('admin')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_transaksi');
    }
};
