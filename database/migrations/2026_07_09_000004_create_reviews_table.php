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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('transaksi_id')->unique(); // matching type 'integer' of peminjaman_transaksi.id
            $table->tinyInteger('rating')->unsigned();
            $table->text('komentar')->nullable();
            $table->string('foto_ulasan')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id')->on('peminjaman_transaksi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
