<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->integer('id_ruangan')->autoIncrement();
            $table->integer('gedung_id');
            $table->string('nama_ruangan', 255);
            $table->enum('tipe_ruangan', ['KAMAR_STANDAR','KAMAR_VIP','KAMAR_PREMIUM','AULA','RUANG_MEETING','RUANG_LAINNYA'])->default('KAMAR_STANDAR');
            $table->integer('lantai')->nullable();
            $table->integer('kapasitas')->default(1);
            $table->enum('gender_policy', ['MALE_ONLY','FEMALE_ONLY','MIXED'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('gedung_id')->references('id_gedung')->on('gedung')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('ruangan');
    }
};