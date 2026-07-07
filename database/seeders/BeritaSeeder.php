<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('berita')->delete();

        // Get admin user (first user with admin role)
        $adminUser = DB::table('users')
            ->join('role', 'users.roleId', '=', 'role.id')
            ->where('role.name', 'admin')
            ->select('users.id')
            ->first();

        $userId = $adminUser?->id ?? 1; // Default to 1 if not found

        $beritas = [
            [
                'id' => 1,
                'userId' => $userId,
                'judul' => 'Pelayanan Pendaftaran Haji Dibuka',
                'slug' => 'pelayanan-pendaftaran-haji-dibuka',
                'isi' => 'Kabar gembira! Asrama Haji Landasan Ulin telah resmi membuka pelayanan pendaftaran calon jamaah haji untuk tahun 2026. Tim kami siap melayani Anda dengan sepenuh hati untuk mewujudkan impian ibadah haji Anda.',
                'gambar' => 'storage/berita/POi5MI4wFVHb0Y2o8WYIFCUrXC2ZljFmDBfNXoZc.jpg',
                'tanggal_publish' => '2026-04-15',
                'status' => 'approved',
                'keterangan' => 'Berita resmi tentang pembukaan pendaftaran haji',
                'created_at' => '2026-07-07 20:34:51',
                'updated_at' => '2026-07-07 21:01:12',
            ],
            [
                'id' => 2,
                'userId' => $userId,
                'judul' => 'Tips Persiapan Ibadah Haji yang Sempurna',
                'slug' => 'tips-persiapan-ibadah-haji-yang-sempurna',
                'isi' => 'Persiapan ibadah haji memerlukan perencanaan yang matang baik dari segi fisik, mental, maupun spiritual. Dalam artikel ini kami bagikan beberapa tips untuk membantu Anda mempersiapkan diri dengan baik sebelum berangkat ke Tanah Suci.',
                'gambar' => 'storage/berita/wGjmlXwrvf3NLuMWh5L1vmNCCtcALXiXOTe6NUCI.jpg',
                'tanggal_publish' => '2026-04-18',
                'status' => 'approved',
                'keterangan' => 'Artikel informatif tentang persiapan haji',
                'created_at' => '2026-07-07 20:34:51',
                'updated_at' => '2026-07-07 20:58:26',
            ],
            [
                'id' => 3,
                'userId' => $userId,
                'judul' => 'Pemberitahuan: Penutupan Kantor Hari Libur Nasional',
                'slug' => 'pemberitahuan-penutupan-kantor-hari-libur-nasional',
                'isi' => 'Untuk memperingati Hari Raya Idul Fitri, Asrama Haji Landasan Ulin akan ditutup dari tanggal 1-5 Mei 2026. Pelayanan akan kembali normal pada tanggal 6 Mei 2026. Terima kasih atas pengertian Anda.',
                'gambar' => 'storage/berita/Z9GrzkkR8wJnUA4msDikVxj3fYBv94PUyPunlouz.jpg',
                'tanggal_publish' => '2026-04-20',
                'status' => 'approved',
                'keterangan' => 'Pemberitahuan resmi tutup kantor',
                'created_at' => '2026-07-07 20:34:51',
                'updated_at' => '2026-07-07 20:57:55',
            ],
            [
                'id' => 5,
                'userId' => $userId,
                'judul' => 'Syarat dan Ketentuan Pendaftaran Calon Jamaah Haji',
                'slug' => 'syarat-dan-ketentuan-pendaftaran-calon-jamaah-haji',
                'isi' => 'Sebelum melakukan pendaftaran, pastikan Anda telah memenuhi semua syarat dan ketentuan yang berlaku. Dokumentasi yang lengkap and akurat sangat diperlukan untuk memproses pendaftaran Anda dengan lancar.',
                'gambar' => 'storage/berita/JXN9doFuwWIKAW6cyzrACDFYyCSkzSLVJcjiDWfF.jpg',
                'tanggal_publish' => '2026-04-12',
                'status' => 'approved',
                'keterangan' => 'Informasi syarat dan ketentuan pendaftaran',
                'created_at' => '2026-07-07 20:34:51',
                'updated_at' => '2026-07-07 20:58:46',
            ],
            [
                'id' => 6,
                'userId' => $userId,
                'judul' => 'Program Edukasi Pramanifestasi Calon Jamaah Haji',
                'slug' => 'program-edukasi-pramanifestasi-haji',
                'isi' => 'Kami menyediakan program edukasi lengkap untuk mempersiapkan Anda secara holistik sebelum berangkat haji. Program ini mencakup materi tentang tata cara ibadah, kesehatan, administrasi, hingga persiapan mental dan spiritual.',
                'gambar' => 'https://placehold.co/500x300?text=Program+Edukasi',
                'tanggal_publish' => '2026-04-22',
                'status' => 'draft',
                'keterangan' => 'Program edukasi untuk calon jamaah haji',
                'created_at' => '2026-07-07 20:34:51',
                'updated_at' => '2026-07-07 20:34:51',
            ],
            [
                'id' => 7,
                'userId' => $userId,
                'judul' => 'Fasilitas Baru di Asrama Haji Landasan Ulin',
                'slug' => 'fasilitas-baru-asrama-haji',
                'isi' => 'Dalam upaya meningkatkan kualitas pelayanan, kami telah menambahkan beberapa fasilitas baru termasuk ruang tunggu yang lebih nyaman, sistem informasi online, dan layanan konsultasi kesehatan gratis.',
                'gambar' => 'https://placehold.co/500x300?text=Fasilitas+Baru',
                'tanggal_publish' => '2026-04-25',
                'status' => 'draft',
                'keterangan' => 'Informasi fasilitas baru yang tersedia',
                'created_at' => '2026-07-07 20:34:51',
                'updated_at' => '2026-07-07 20:34:51',
            ],
            [
                'id' => 8,
                'userId' => $userId,
                'judul' => 'Panduan Pembayaran BPIH Calon Jamaah Haji',
                'slug' => 'panduan-pembayaran-bpih-haji',
                'isi' => 'BPIH (Biaya Penyelenggaraan Ibadah Haji) adalah biaya wajib yang harus dibayarkan oleh setiap calon jamaah haji. Pelajari panduan lengkap tentang tata cara pembayaran BPIH melalui artikel ini.',
                'gambar' => 'https://placehold.co/500x300?text=Panduan+Pembayaran',
                'tanggal_publish' => '2026-04-28',
                'status' => 'draft',
                'keterangan' => 'Panduan pembayaran BPIH',
                'created_at' => '2026-07-07 20:34:51',
                'updated_at' => '2026-07-07 20:34:51',
            ],
        ];

        DB::table('berita')->insert($beritas);
    }
}
