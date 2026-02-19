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
        Schema::create('profil', function (Blueprint $table) {
            $table->id('id');
            $table->string('email_admin', 128)->nullable();
            $table->string('email_users', 128)->nullable();
            $table->string('foto')->nullable();
            $table->boolean('jenis_kelamin')->nullable();
            $table->text('alamat_users')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->timestamps();
            $table->foreign('email_admin')->references('email_admin')->on('admin')->onDelete('restrict');
            $table->foreign('email_users')->references('email_users')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil');
    }
};
