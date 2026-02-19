<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Profil;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Sarana;
use App\Models\PeminjamanTransaksi;
use App\Models\DetailPeminjamanSarana;
use App\Models\Berita;
use App\Models\Tentang;
use App\Models\GambarDashboard;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $admin = Admin::create([
            'email_admin' => 'admin@siprasa.com',
            'name_admin' => 'Muhammad Rizki',
            'password_admin' => Hash::make('admin123'),
            'no_hp_admin' => '081234567890',
            'role' => 'admin',
            'gedung_id' => null,
        ]);

        // Profil Admin
        Profil::create([
            'email_admin' => 'admin@siprasa.com',
            'email_users' => null,
            'foto' => 'https://via.placeholder.com/150?text=Admin',
            'jenis_kelamin' => true,
            'alamat_users' => 'Jl. Admin No. 1, Jakarta',
            'tanggal_lahir' => '1990-01-15',
        ]);

        // Pimpinan
        $pimpinan = Admin::create([
            'email_admin' => 'pimpinan@siprasa.com',
            'name_admin' => 'Dr. Budi Santoso',
            'password_admin' => Hash::make('pimpinan123'),
            'no_hp_admin' => '081234567891',
            'role' => 'pimpinan',
            'gedung_id' => null,
        ]);

        // Profil Pimpinan
        Profil::create([
            'email_admin' => 'pimpinan@siprasa.com',
            'email_users' => null,
            'foto' => 'https://via.placeholder.com/150?text=Pimpinan',
            'jenis_kelamin' => true,
            'alamat_users' => 'Jl. Pimpinan No. 5, Jakarta',
            'tanggal_lahir' => '1985-03-20',
        ]);

        // Gedung 1, 2, 3
        $gedung1 = Gedung::create([
            'nama' => 'Gedung A - Aula Utama',
            'kordinat_y' => '-6.2088',
            'kordinat_x' => '106.8456',
            'lokasi' => 'Lantai 1, Blok A',
        ]);

        $gedung2 = Gedung::create([
            'nama' => 'Gedung B - Ruang Pertemuan',
            'kordinat_y' => '-6.2089',
            'kordinat_x' => '106.8457',
            'lokasi' => 'Lantai 2, Blok B',
        ]);

        $gedung3 = Gedung::create([
            'nama' => 'Gedung C - Ruang Kelas',
            'kordinat_y' => '-6.2090',
            'kordinat_x' => '106.8458',
            'lokasi' => 'Lantai 3, Blok C',
        ]);

        // Petugas 3 dengan gedung yang berbeda
        $petugas1 = Admin::create([
            'email_admin' => 'petugas1@siprasa.com',
            'name_admin' => 'Siti Nurhaliza',
            'password_admin' => Hash::make('petugas123'),
            'no_hp_admin' => '081298765432',
            'role' => 'petugas',
            'gedung_id' => $gedung1->id,
        ]);

        $petugas2 = Admin::create([
            'email_admin' => 'petugas2@siprasa.com',
            'name_admin' => 'Ahmad Wijaya',
            'password_admin' => Hash::make('petugas123'),
            'no_hp_admin' => '081298765433',
            'role' => 'petugas',
            'gedung_id' => $gedung2->id,
        ]);

        $petugas3 = Admin::create([
            'email_admin' => 'petugas3@siprasa.com',
            'name_admin' => 'Dewi Lestari',
            'password_admin' => Hash::make('petugas123'),
            'no_hp_admin' => '081298765434',
            'role' => 'petugas',
            'gedung_id' => $gedung3->id,
        ]);

        // Profil Petugas
        Profil::create([
            'email_admin' => 'petugas1@siprasa.com',
            'email_users' => null,
            'foto' => 'https://via.placeholder.com/150?text=Petugas1',
            'jenis_kelamin' => false,
            'alamat_users' => 'Jl. Petugas 1, Jakarta',
            'tanggal_lahir' => '1995-05-10',
        ]);

        Profil::create([
            'email_admin' => 'petugas2@siprasa.com',
            'email_users' => null,
            'foto' => 'https://via.placeholder.com/150?text=Petugas2',
            'jenis_kelamin' => true,
            'alamat_users' => 'Jl. Petugas 2, Jakarta',
            'tanggal_lahir' => '1993-08-22',
        ]);

        Profil::create([
            'email_admin' => 'petugas3@siprasa.com',
            'email_users' => null,
            'foto' => 'https://via.placeholder.com/150?text=Petugas3',
            'jenis_kelamin' => false,
            'alamat_users' => 'Jl. Petugas 3, Jakarta',
            'tanggal_lahir' => '1994-11-07',
        ]);

        // Users 5 (2 dengan profil lengkap, 3 kosong profil)
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[$i] = User::create([
                'email_users' => "user$i@siprasa.com",
                'name_users' => "User Test $i",
                'password_users' => Hash::make('user' . $i . '123'),
                'no_hp_users' => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Profil Users (hanya 2, sisanya kosong)
        Profil::create([
            'email_admin' => null,
            'email_users' => 'user1@siprasa.com',
            'foto' => 'https://via.placeholder.com/150?text=User1',
            'jenis_kelamin' => true,
            'alamat_users' => 'Jl. User 1, Jakarta',
            'tanggal_lahir' => '1998-02-14',
        ]);

        Profil::create([
            'email_admin' => null,
            'email_users' => 'user2@siprasa.com',
            'foto' => 'https://via.placeholder.com/150?text=User2',
            'jenis_kelamin' => false,
            'alamat_users' => 'Jl. User 2, Jakarta',
            'tanggal_lahir' => '1999-06-25',
        ]);

        // Ruangan 10 (acak di gedung)
        $ruangans = [];
        $namaRuangan = ['A101', 'A102', 'A103', 'B201', 'B202', 'B203', 'C301', 'C302', 'C303', 'C304'];
        for ($i = 0; $i < 10; $i++) {
            $gedungId = match ($i) {
                0, 1, 2 => $gedung1->id,
                3, 4, 5 => $gedung2->id,
                default => $gedung3->id,
            };
            $ruangans[$i] = Ruangan::create([
                'gedung_id' => $gedungId,
                'nama_ruangan' => $namaRuangan[$i],
            ]);
        }

        // Sarana 15
        $saranas = [];
        $namaSarana = [
            'Proyektor', 'Layar Projector', 'Meja', 'Kursi', 'Whiteboard',
            'Papan Tulis', 'LCD Monitor', 'Laptop', 'Mikrofon', 'Speaker',
            'Kamera', 'Tripod', 'Podium', 'Lampu LED', 'Kabel HDMI',
        ];
        $kondisiSarana = ['Baik', 'Baik Sekali', 'Normal', 'Perlu Perbaikan'];

        for ($i = 0; $i < 15; $i++) {
            $saranas[$i] = Sarana::create([
                'nama' => $namaSarana[$i],
                'kondisi' => $kondisiSarana[array_rand($kondisiSarana)],
                'tgl_penerimaan' => now()->subDays(rand(30, 365)),
                'stok' => rand(1, 10),
            ]);
        }

        // Peminjaman 7
        $peminjamans = [];
        $statusPeminjaman = ['Diajukan', 'Disetujui', 'Dibatalkan', 'Ditolak'];
        $statusSarana = [' ', 'Disiapkan', 'Siap Pakai'];
        $namaKegiatan = [
            'Seminar Teknologi Digital',
            'Workshop Kewirausahaan',
            'Rapat Koordinasi Departemen',
            'Training Kepemimpinan',
            'Konferensi Pers',
            'Acara Penghargaan Karyawan',
            'Forum Diskusi Kebijakan',
        ];

        for ($i = 0; $i < 7; $i++) {
            $peminjamans[$i] = PeminjamanTransaksi::create([
                'email_users' => $users[rand(1, 5)]->email_users,
                'ruangan_id' => $ruangans[rand(0, 9)]->id,
                'nama_kegiatan' => $namaKegiatan[$i],
                'tgl_peminjaman' => now()->addDays(rand(1, 30)),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '17:00:00',
                'status_peminjaman' => $statusPeminjaman[rand(0, 3)],
                'keterangan' => 'Peminjaman untuk acara ' . $namaKegiatan[$i],
                'status_sarana' => $statusSarana[rand(0, 2)],
                'email_admin' => $petugas1->email_admin,
            ]);
        }

        // Detail Peminjaman Sarana (menyesuaikan dengan peminjaman)
        for ($i = 0; $i < 7; $i++) {
            $jumlahDetail = rand(1, 3);
            for ($j = 0; $j < $jumlahDetail; $j++) {
                DetailPeminjamanSarana::create([
                    'peminjaman_id' => $peminjamans[$i]->id,
                    'sarana_id' => $saranas[rand(0, 14)]->id,
                    'jumlah' => rand(1, 5),
                ]);
            }
        }

        // Berita 3
        $berita = [
            [
                'email_admin' => 'admin@siprasa.com',
                'judul' => 'Peluncuran Sistem Peminjaman Sarana Digital',
                'slug' => 'peluncuran-sistem-peminjaman-sarana-digital',
                'isi' => 'Kami dengan bangga mengumumkan peluncuran sistem peminjaman sarana terbaru yang menggunakan teknologi digital.',
                'gambar' => 'https://via.placeholder.com/800x400?text=Berita1',
                'tanggal_publish' => now()->subDays(10),
                'status' => 'approved',
            ],
            [
                'email_admin' => 'pimpinan@siprasa.com',
                'judul' => 'Peningkatan Koleksi Sarana Tahun 2026',
                'slug' => 'peningkatan-koleksi-sarana-tahun-2026',
                'isi' => 'Dengan dukungan anggaran baru, kami menambah 50 unit sarana baru untuk mendukung kegiatan.',
                'gambar' => 'https://via.placeholder.com/800x400?text=Berita2',
                'tanggal_publish' => now()->subDays(5),
                'status' => 'approved',
            ],
            [
                'email_admin' => 'petugas1@siprasa.com',
                'judul' => 'Workshop Penggunaan Sarana Presentasi',
                'slug' => 'workshop-penggunaan-sarana-presentasi',
                'isi' => 'Diadakan workshop gratis tentang cara menggunakan sarana presentasi modern untuk semua pengguna.',
                'gambar' => 'https://via.placeholder.com/800x400?text=Berita3',
                'tanggal_publish' => now()->subDays(2),
                'status' => 'draft',
            ],
        ];

        foreach ($berita as $b) {
            Berita::create($b);
        }

        // Tentang 1
        Tentang::create([
            'nama_instansi' => 'Sistem Peminjaman Sarana (SIPRASA)',
            'kordinat_x' => '106.8456',
            'kordinat_y' => '-6.2088',
            'no_hp' => '021-1234567',
            'kantor' => 'Kantor Pusat',
            'email' => 'info@siprasa.com',
            'logo_instansi' => 'https://via.placeholder.com/200x100?text=Logo',
            'foto_instansi' => 'https://via.placeholder.com/800x600?text=Instansi',
            'link_google_maps' => 'https://maps.google.com/?q=-6.2088,106.8456',
        ]);

        // Gambar Dashboard 2
        GambarDashboard::create([
            'email_admin' => 'admin@siprasa.com',
            'posisi' => false,  // 0 - Carousel
            'path' => 'https://via.placeholder.com/1200x400?text=Dashboard+Image+1',
            'waktu_upload' => now()->subDays(7),
        ]);

        GambarDashboard::create([
            'email_admin' => 'admin@siprasa.com',
            'posisi' => true,   // 1 - Static
            'path' => 'https://via.placeholder.com/1200x400?text=Dashboard+Image+2',
            'waktu_upload' => now()->subDays(3),
        ]);

        echo "\n✅ Database seeding completed successfully!\n";
        echo "Admin: admin@siprasa.com (Password: admin123)\n";
        echo "Pimpinan: pimpinan@siprasa.com (Password: pimpinan123)\n";
        echo "Petugas 1: petugas1@siprasa.com (Password: petugas123)\n";
        echo "Petugas 2: petugas2@siprasa.com (Password: petugas123)\n";
        echo "Petugas 3: petugas3@siprasa.com (Password: petugas123)\n";
        echo "Users: user1-5@siprasa.com (Password: user[1-5]123)\n\n";
    }
}

