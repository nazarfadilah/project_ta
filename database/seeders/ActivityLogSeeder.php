<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLogSeeder extends Seeder {
    public function run(): void {
        DB::table('activity_log')->insert([
            ['id' => 1, 'userId' => 1, 'action' => 'CREATE', 'tabelNama' => 'peminjaman_transaksi', 'recordId' => '1', 'detailPerubahan' => json_encode(['kodePeminjaman' => 'PJM/2026/0001', 'guestId' => 1, 'facilityId' => 1]), 'ipAddress' => '192.168.1.100'],
            ['id' => 2, 'userId' => 2, 'action' => 'UPDATE', 'tabelNama' => 'peminjaman_transaksi', 'recordId' => '1', 'detailPerubahan' => json_encode(['statusPeminjaman' => 'RESERVASI -> CHECK_IN', 'jamMulai' => '2026-04-20 14:30:00']), 'ipAddress' => '192.168.1.101'],
            ['id' => 3, 'userId' => 1, 'action' => 'CREATE', 'tabelNama' => 'invoice', 'recordId' => '3', 'detailPerubahan' => json_encode(['noInvoice' => 'INV/2026/0003', 'peminjamanId' => 3, 'totalHarga' => 800000.00]), 'ipAddress' => '192.168.1.100'],
        ]);
    }
}