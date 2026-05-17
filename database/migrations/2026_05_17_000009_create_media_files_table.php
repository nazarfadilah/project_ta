<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('media_file', function (Blueprint $table) {
            $table->id();
            $table->integer('ruangan_id');
            $table->string('path', 255);
            $table->timestamps();
            
            $table->foreign('ruangan_id')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('media_file');
    }
};