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
        Schema::create('berita', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('userId')->nullable();
            $table->string('judul', 128);
            $table->string('slug', 64);
            $table->text('isi');
            $table->string('gambar', 255);
            $table->date('tanggal_publish');
            $table->enum('status', ['approved', 'draft', 'rejected'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
