<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder {
    public function run(): void {
        DB::table('invoice')->insert([
            ['id' => 1, 'noInvoice' => 'INV/2026/0001', 'peminjamanId' => 1, 'subtotal' => 300000.00, 'biayaTambahan' => 0.00, 'totalHarga' => 300000.00, 'statusInvoice' => 'UNPAID', 'status_pembayaran' => 'BELUM_BAYAR', 'tglInvoice' => '2026-04-21 07:53:03', 'tglDueDate' => '2026-04-28 07:53:03', 'tgl_pembayaran' => null, 'tglPaid' => null, 'notes' => 'Invoice untuk peminjaman kamar premium'],
            ['id' => 2, 'noInvoice' => 'INV/2026/0002', 'peminjamanId' => 2, 'subtotal' => 2500000.00, 'biayaTambahan' => 500000.00, 'totalHarga' => 3000000.00, 'statusInvoice' => 'PARTIAL', 'status_pembayaran' => 'SEBAGIAN', 'tglInvoice' => '2026-04-21 07:53:03', 'tglDueDate' => '2026-04-28 07:53:03', 'tgl_pembayaran' => '2026-04-21 07:53:03', 'tglPaid' => null, 'notes' => 'Invoice untuk peminjaman aula + setup'],
            ['id' => 3, 'noInvoice' => 'INV/2026/0003', 'peminjamanId' => 3, 'subtotal' => 800000.00, 'biayaTambahan' => 0.00, 'totalHarga' => 800000.00, 'statusInvoice' => 'PAID', 'status_pembayaran' => 'LUNAS', 'tglInvoice' => '2026-04-21 08:00:00', 'tglDueDate' => '2026-04-28 17:00:00', 'tgl_pembayaran' => '2026-04-21 16:30:00', 'tglPaid' => '2026-04-21 16:30:00', 'notes' => 'Invoice untuk peminjaman ruang rapat'],
        ]);
    }
}