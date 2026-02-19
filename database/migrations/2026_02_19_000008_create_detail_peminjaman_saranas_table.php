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
        Schema::create('detail_peminjaman_sarana', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('sarana_id');
            $table->unsignedBigInteger('peminjaman_id');
            $table->string('jumlah', 5);
            $table->timestamps();
            $table->foreign('sarana_id')->references('id')->on('sarana')->onDelete('restrict');
            $table->foreign('peminjaman_id')->references('id')->on('peminjaman_transaksi')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman_sarana');
    }
};
