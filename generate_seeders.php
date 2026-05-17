<?php

$seeders = [
    'RoleSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder {
    public function run(): void {
        DB::table('role')->insert([
            ['id' => 1, 'name' => 'Admin', 'description' => 'Full system access', 'permissions' => json_encode(['*'])],
            ['id' => 2, 'name' => 'Pimpinan', 'description' => 'View reports, analytics, approve requests', 'permissions' => json_encode(['report.view', 'analytics.view', 'verification.approve'])],
            ['id' => 3, 'name' => 'Petugas', 'description' => 'Check-in/out, verification, data entry', 'permissions' => json_encode(['checkin.manage', 'checkout.manage', 'verification.manage'])],
            ['id' => 4, 'name' => 'Tamu', 'description' => 'Browse facilities, make booking, view status', 'permissions' => json_encode(['booking.make', 'booking.cancel', 'status.view'])],
        ]);
    }
}
EOT,
    'GedungSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder {
    public function run(): void {
        DB::table('gedung')->insert([
            ['id_gedung' => 1, 'nama_gedung' => 'Gedung Utama', 'koordinat' => '-3.1234, 114.5678', 'keterangan' => 'Gedung utama dengan 2 lantai untuk penginapan standar'],
            ['id_gedung' => 2, 'nama_gedung' => 'Gedung Aula', 'koordinat' => '-3.1235, 114.5679', 'keterangan' => 'Gedung untuk aula dan ruang pertemuan besar'],
            ['id_gedung' => 3, 'nama_gedung' => 'Gedung Pendukung', 'koordinat' => '-3.1236, 114.5680', 'keterangan' => 'Gedung untuk fasilitas pendukung dan ruang meeting'],
        ]);
    }
}
EOT,
    'RuanganSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder {
    public function run(): void {
        DB::table('ruangan')->insert([
            ['id_ruangan' => 1, 'gedung_id' => 1, 'nama_ruangan' => 'Ruang 101', 'tipe_ruangan' => 'KAMAR_STANDAR', 'lantai' => 1, 'kapasitas' => 2, 'gender_policy' => 'MIXED', 'keterangan' => 'Ruangan di lantai 1 - Kamar standar dengan AC dan mandi'],
            ['id_ruangan' => 2, 'gedung_id' => 2, 'nama_ruangan' => 'Aula Besar', 'tipe_ruangan' => 'AULA', 'lantai' => null, 'kapasitas' => 300, 'gender_policy' => 'MIXED', 'keterangan' => 'Aula dengan kapasitas 300 orang, lengkap dengan audio visual'],
            ['id_ruangan' => 3, 'gedung_id' => 3, 'nama_ruangan' => 'Ruang Meeting Executive', 'tipe_ruangan' => 'RUANG_MEETING', 'lantai' => 1, 'kapasitas' => 50, 'gender_policy' => 'MIXED', 'keterangan' => 'Ruang rapat dengan fasilitas meeting table dan projector'],
        ]);
    }
}
EOT,
    'PaketRuanganSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketRuanganSeeder extends Seeder {
    public function run(): void {
        DB::table('paket_ruangan')->insert([
            ['id' => 1, 'ruangan_id' => 1, 'nama_paket' => 'Paket 24 Jam', 'durasi' => 24, 'harga' => 150000.00, 'currency' => 'IDR', 'isExclusive' => 0, 'status' => 'ACTIVE'],
            ['id' => 2, 'ruangan_id' => 2, 'nama_paket' => 'Sewa Aula Harian (Exclusive)', 'durasi' => 24, 'harga' => 2500000.00, 'currency' => 'IDR', 'isExclusive' => 1, 'status' => 'ACTIVE'],
            ['id' => 3, 'ruangan_id' => 3, 'nama_paket' => 'Sewa 4 Jam', 'durasi' => 4, 'harga' => 800000.00, 'currency' => 'IDR', 'isExclusive' => 0, 'status' => 'ACTIVE'],
        ]);
    }
}
EOT,
    'GuestSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder {
    public function run(): void {
        DB::table('guest')->insert([
            ['id' => 1, 'nik' => '3209072512850001', 'name' => 'Haji Ahmad Suryanto', 'gender' => 'MALE', 'address' => 'Jl. Merdeka No. 45, Banjarmasin', 'bloodType' => 'O', 'notes' => 'Tamu reguler, sering menggunakan layanan'],
            ['id' => 2, 'nik' => '3209151809870002', 'name' => 'Hajja Siti Nurhaliza', 'gender' => 'FEMALE', 'address' => 'Jl. Sultan Adam No. 12, Banjarmasin', 'bloodType' => 'AB', 'notes' => 'Tamu baru keluarga besar'],
            ['id' => 3, 'nik' => '3209089907920003', 'name' => 'Haji Bambang Irawan', 'gender' => 'MALE', 'address' => 'Jl. Hasanuddin No. 78, Banjarmasin', 'bloodType' => 'B', 'notes' => 'Tamu korporat dari PT Maju Jaya'],
        ]);
    }
}
EOT,
    'PeminjamanTransaksiSeeder' => <<<EOT
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
EOT,
    'InvoiceSeeder' => <<<EOT
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
EOT,
    'SaranaSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaranaSeeder extends Seeder {
    public function run(): void {
        DB::table('sarana')->insert([
            ['id' => 1, 'nama' => 'Kasur Lipat', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-01-15', 'stok' => '5'],
            ['id' => 2, 'nama' => 'Kursi Tambahan', 'kondisi' => 'Baik Sekali', 'tgl_penerimaan' => '2026-02-01', 'stok' => '20'],
            ['id' => 3, 'nama' => 'Proyektor Portable', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-01-20', 'stok' => '2'],
        ]);
    }
}
EOT,
    'DetailPeminjamanSaranaSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPeminjamanSaranaSeeder extends Seeder {
    public function run(): void {
        DB::table('detail_peminjaman_sarana')->insert([
            ['id' => 1, 'sarana_id' => 1, 'peminjaman_id' => 1, 'jumlah' => '2'],
            ['id' => 2, 'sarana_id' => 3, 'peminjaman_id' => 2, 'jumlah' => '1'],
            ['id' => 3, 'sarana_id' => 2, 'peminjaman_id' => 3, 'jumlah' => '5'],
        ]);
    }
}
EOT,
    'MediaFileSeeder' => <<<EOT
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaFileSeeder extends Seeder {
    public function run(): void {
        DB::table('media_file')->insert([
            ['id' => 1, 'ruangan_id' => 1, 'path' => '/uploads/ruangan/101/foto-kamar-standar-1.jpg'],
            ['id' => 2, 'ruangan_id' => 2, 'path' => '/uploads/aula/foto-aula-besar-1.jpg'],
            ['id' => 3, 'ruangan_id' => 3, 'path' => '/uploads/meeting/foto-ruang-meeting-1.jpg'],
        ]);
    }
}
EOT,
    'ActivityLogSeeder' => <<<EOT
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
EOT,
];

foreach ($seeders as $name => $content) {
    file_put_contents(__DIR__ . '/database/seeders/' . $name . '.php', $content);
}
echo "Seeders generated.\n";
