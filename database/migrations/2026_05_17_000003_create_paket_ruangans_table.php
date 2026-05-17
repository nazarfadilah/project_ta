<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('paket_ruangan', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('ruangan_id');
            $table->string('nama_paket', 255);
            $table->integer('durasi')->nullable();
            $table->decimal('harga', 15, 2);
            $table->string('currency', 3)->default('IDR');
            $table->boolean('isExclusive')->default(0);
            $table->enum('status', ['ACTIVE','INACTIVE','MAINTENANCE'])->default('ACTIVE');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('ruangan_id')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('paket_ruangan');
    }
};