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
        Schema::create('gambar_dashboard', function (Blueprint $table) {
            $table->id('id');
            $table->string('email_admin', 128);
            $table->boolean('posisi');
            $table->string('path');
            $table->timestamp('waktu_upload');
            $table->timestamps();
            $table->foreign('email_admin')->references('email_admin')->on('admin')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_dashboard');
    }
};
