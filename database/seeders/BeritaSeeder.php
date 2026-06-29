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
        // Get admin user (first user with admin role)
        $adminUser = DB::table('users')
            ->join('role', 'users.roleId', '=', 'role.id')
            ->where('role.name', 'admin')
            ->select('users.id')
            ->first();

        $userId = $adminUser?->id ?? 1; // Default to 1 if not found

        $beritas = [
            [
                'userId' => $userId,
                'judul' => 'Pelayanan Pendaftaran Haji Dibuka',
                'slug' => 'pelayanan-pendaftaran-haji-dibuka',
                'isi' => 'Kabar gembira! Asrama Haji Landasan Ulin telah resmi membuka pelayanan pendaftaran calon jamaah haji untuk tahun 2026. Tim kami siap melayani Anda dengan sepenuh hati untuk mewujudkan impian ibadah haji Anda.',
                'gambar' => 'https://placehold.co/500x300?text=Pelayanan+Pendaftaran',
                'tanggal_publish' => Carbon::create(2026, 4, 15)->format('Y-m-d'),
                'status' => 'approved',
                'keterangan' => 'Berita resmi tentang pembukaan pendaftaran haji',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'userId' => $userId,
                'judul' => 'Tips Persiapan Ibadah Haji yang Sempurna',
                'slug' => 'tips-persiapan-ibadah-haji',
                'isi' => 'Persiapan ibadah haji memerlukan perencanaan yang matang baik dari segi fisik, mental, maupun spiritual. Dalam artikel ini kami bagikan beberapa tips untuk membantu Anda mempersiapkan diri dengan baik sebelum berangkat ke Tanah Suci.',
                'gambar' => 'https://placehold.co/500x300?text=Tips+Persiapan',
                'tanggal_publish' => Carbon::create(2026, 4, 18)->format('Y-m-d'),
                'status' => 'approved',
                'keterangan' => 'Artikel informatif tentang persiapan haji',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'userId' => $userId,
                'judul' => 'Pemberitahuan: Penutupan Kantor Hari Libur Nasional',
                'slug' => 'penutupan-kantor-hari-libur',
                'isi' => 'Untuk memperingati Hari Raya Idul Fitri, Asrama Haji Landasan Ulin akan ditutup dari tanggal 1-5 Mei 2026. Pelayanan akan kembali normal pada tanggal 6 Mei 2026. Terima kasih atas pengertian Anda.',
                'gambar' => 'https://placehold.co/500x300?text=Pemberitahuan+Libur',
                'tanggal_publish' => Carbon::create(2026, 4, 20)->format('Y-m-d'),
                'status' => 'approved',
                'keterangan' => 'Pemberitahuan resmi tutup kantor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'userId' => $userId,
                'judul' => 'Testimoni Calon Jamaah Haji 2025',
                'slug' => 'testimoni-calon-jamaah-haji-2025',
                'isi' => 'Banyak calon jamaah haji yang telah merasakan manfaat fasilitas dan pelayanan kami. Berikut adalah beberapa testimoni positif dari mereka yang telah mendaftar and bersiap untuk menunaikan ibadah haji tahun lalu.',
                'gambar' => 'https://placehold.co/500x300?text=Testimoni+Jamaah',
                'tanggal_publish' => Carbon::create(2026, 4, 10)->format('Y-m-d'),
                'status' => 'approved',
                'keterangan' => 'Testimoni dari calon jamaah haji sebelumnya',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'userId' => $userId,
                'judul' => 'Syarat dan Ketentuan Pendaftaran Calon Jamaah Haji',
                'slug' => 'syarat-ketentuan-pendaftaran-haji',
                'isi' => 'Sebelum melakukan pendaftaran, pastikan Anda telah memenuhi semua syarat dan ketentuan yang berlaku. Dokumentasi yang lengkap dan akurat sangat diperlukan untuk memproses pendaftaran Anda dengan lancar.',
                'gambar' => 'https://placehold.co/500x300?text=Syarat+Ketentuan',
                'tanggal_publish' => Carbon::create(2026, 4, 12)->format('Y-m-d'),
                'status' => 'approved',
                'keterangan' => 'Informasi syarat dan ketentuan pendaftaran',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'userId' => $userId,
                'judul' => 'Program Edukasi Pramanifestasi Calon Jamaah Haji',
                'slug' => 'program-edukasi-pramanifestasi-haji',
                'isi' => 'Kami menyediakan program edukasi lengkap untuk mempersiapkan Anda secara holistik sebelum berangkat haji. Program ini mencakup materi tentang tata cara ibadah, kesehatan, administrasi, hingga persiapan mental dan spiritual.',
                'gambar' => 'https://placehold.co/500x300?text=Program+Edukasi',
                'tanggal_publish' => Carbon::create(2026, 4, 22)->format('Y-m-d'),
                'status' => 'draft',
                'keterangan' => 'Program edukasi untuk calon jamaah haji',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'userId' => $userId,
                'judul' => 'Fasilitas Baru di Asrama Haji Landasan Ulin',
                'slug' => 'fasilitas-baru-asrama-haji',
                'isi' => 'Dalam upaya meningkatkan kualitas pelayanan, kami telah menambahkan beberapa fasilitas baru termasuk ruang tunggu yang lebih nyaman, sistem informasi online, dan layanan konsultasi kesehatan gratis.',
                'gambar' => 'https://placehold.co/500x300?text=Fasilitas+Baru',
                'tanggal_publish' => Carbon::create(2026, 4, 25)->format('Y-m-d'),
                'status' => 'draft',
                'keterangan' => 'Informasi fasilitas baru yang tersedia',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'userId' => $userId,
                'judul' => 'Panduan Pembayaran BPIH Calon Jamaah Haji',
                'slug' => 'panduan-pembayaran-bpih-haji',
                'isi' => 'BPIH (Biaya Penyelenggaraan Ibadah Haji) adalah biaya wajib yang harus dibayarkan oleh setiap calon jamaah haji. Pelajari panduan lengkap tentang tata cara pembayaran BPIH melalui artikel ini.',
                'gambar' => 'https://placehold.co/500x300?text=Panduan+Pembayaran',
                'tanggal_publish' => Carbon::create(2026, 4, 28)->format('Y-m-d'),
                'status' => 'draft',
                'keterangan' => 'Panduan pembayaran BPIH',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('berita')->insert($beritas);
    }
}
