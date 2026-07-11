<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('sarana', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 128);
            $table->enum('kondisi', ['Baik','Baik Sekali','Perlu Perbaikan'])->default('Baik');
            $table->date('tgl_penerimaan');
            $table->string('stok', 5);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sarana');
    }
};