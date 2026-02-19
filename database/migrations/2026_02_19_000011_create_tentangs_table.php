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
        Schema::create('tentang', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama_instansi', 64)->nullable();
            $table->string('kordinat_x')->nullable();
            $table->string('kordinat_y')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('kantor', 15)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('logo_instansi')->nullable();
            $table->string('foto_instansi')->nullable();
            $table->text('link_google_maps')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tentang');
    }
};
