<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->unsignedBigInteger('userId')->nullable();
            $table->string('action', 100);
            $table->string('tabelNama', 100);
            $table->string('recordId', 100);
            $table->json('detailPerubahan')->nullable();
            $table->string('ipAddress', 50)->nullable();
            $table->timestamp('createdAt')->useCurrent();
            
            $table->foreign('userId')->references('id')->on('users')->onDelete('set null');
        });
    }
    public function down(): void {
        Schema::dropIfExists('activity_log');
    }
};