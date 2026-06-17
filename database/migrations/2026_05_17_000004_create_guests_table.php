<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('guest', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->char('nik', 16)->unique();
            $table->string('name', 255);
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->char('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('bloodType', 5)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('guest');
    }
};