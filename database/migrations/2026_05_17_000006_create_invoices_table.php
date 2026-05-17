<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('invoice', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('noInvoice', 50)->unique();
            $table->integer('peminjamanId')->unique();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('biayaTambahan', 15, 2)->default(0);
            $table->decimal('totalHarga', 15, 2);
            $table->enum('statusInvoice', ['UNPAID','PARTIAL','PAID','OVERDUE'])->default('UNPAID');
            $table->enum('status_pembayaran', ['BELUM_BAYAR','SEBAGIAN','LUNAS'])->default('BELUM_BAYAR');
            $table->dateTime('tglInvoice')->useCurrent();
            $table->dateTime('tglDueDate')->nullable();
            $table->dateTime('tgl_pembayaran')->nullable();
            $table->dateTime('tglPaid')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('peminjamanId')->references('id')->on('peminjaman_transaksi')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('invoice');
    }
};