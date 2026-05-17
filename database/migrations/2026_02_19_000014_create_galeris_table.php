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
        Schema::create('galeri', function (Blueprint $table) {
            $table->id('id');
            $table->enum('kategori', ['pengapian', 'moshulla', 'aula', 'gedung']); // Dapat disesuaikan dengan kategori yang ada
            $table->string('judul', 128);
            $table->text('isi');
            $table->string('gambar', 255);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
