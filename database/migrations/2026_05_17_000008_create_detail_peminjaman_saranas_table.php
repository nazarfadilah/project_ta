<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('detail_peminjaman_sarana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sarana_id');
            $table->integer('peminjaman_id');
            $table->string('jumlah', 5);
            $table->timestamps();
            
            $table->foreign('sarana_id')->references('id')->on('sarana')->onDelete('cascade');
            $table->foreign('peminjaman_id')->references('id')->on('peminjaman_transaksi')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('detail_peminjaman_sarana');
    }
};