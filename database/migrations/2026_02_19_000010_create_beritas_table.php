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
            $table->string('email_admin', 128);
            $table->string('judul', 128);
            $table->string('slug', 64);
            $table->text('isi');
            $table->string('gambar');
            $table->date('tanggal_publish');
            $table->enum('status', ['approved', 'draft'])->default('draft');
            $table->timestamps();
            $table->foreign('email_admin')->references('email_admin')->on('admin')->onDelete('restrict');
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
