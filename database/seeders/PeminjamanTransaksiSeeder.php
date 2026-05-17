<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeminjamanTransaksiSeeder extends Seeder {
    public function run(): void {
        DB::table('peminjaman_transaksi')->insert([
            ['id' => 1, 'kodePeminjaman' => 'PJM/2026/0001', 'guestId' => 1, 'facilityId' => 1, 'tanggal' => '2026-04-20', 'jamMulai' => '2026-04-20 14:00:00', 'checkIn' => '2026-04-20 14:30:00', 'checkOut' => null, 'durasi' => 2, 'statusPeminjaman' => 'CHECK_IN', 'keterangan' => 'Peminjaman kamar untuk keluarga Haji Ahmad', 'userId' => 1, 'statusApproval' => 'APPROVED', 'catatanApproval' => 'Disetujui sesuai prosedur', 'tanggalApproval' => '2026-04-20 13:00:00', 'biayaTambahan' => 0.00, 'kondisiReturn' => null, 'catatanKerusakan' => null, 'estimasiDamage' => null],
            ['id' => 2, 'kodePeminjaman' => 'PJM/2026/0002', 'guestId' => 2, 'facilityId' => 2, 'tanggal' => '2026-04-25', 'jamMulai' => '2026-04-25 08:00:00', 'checkIn' => null, 'checkOut' => null, 'durasi' => 1, 'statusPeminjaman' => 'RESERVASI', 'keterangan' => 'Peminjaman aula untuk acara pengajian', 'userId' => 3, 'statusApproval' => 'PENDING', 'catatanApproval' => null, 'tanggalApproval' => null, 'biayaTambahan' => 500000.00, 'kondisiReturn' => null, 'catatanKerusakan' => null, 'estimasiDamage' => null],
            ['id' => 3, 'kodePeminjaman' => 'PJM/2026/0003', 'guestId' => 3, 'facilityId' => 3, 'tanggal' => '2026-04-21', 'jamMulai' => '2026-04-21 09:00:00', 'checkIn' => '2026-04-21 09:15:00', 'checkOut' => '2026-04-21 16:45:00', 'durasi' => 1, 'statusPeminjaman' => 'SELESAI', 'keterangan' => 'Ruang rapat untuk meeting manajemen', 'userId' => 1, 'statusApproval' => 'APPROVED', 'catatanApproval' => 'Disetujui', 'tanggalApproval' => '2026-04-21 08:30:00', 'biayaTambahan' => 0.00, 'kondisiReturn' => 'BAIK', 'catatanKerusakan' => 'Tidak ada kerusakan', 'estimasiDamage' => 0.00],
        ]);
    }
}