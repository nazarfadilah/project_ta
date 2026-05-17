<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TentangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Informasi Dasar Instansi
            ['key' => 'nama_instansi', 'key2' => null, 'value' => 'Asrama Haji Landasan Ulin'],
            ['key' => 'logo', 'key2' => null, 'value' => 'https://example.com/logo.png'],
            ['key' => 'email', 'key2' => null, 'value' => 'asramahajilandasanulin@gmail.com'],
            ['key' => 'alamat', 'key2' => null, 'value' => 'Jl. Landasan Ulin, Banjarmasin, Kalimantan Selatan'],
            
            // Kontak
            ['key' => 'no_hp', 'key2' => null, 'value' => '0123456789'],
            ['key' => 'kantor', 'key2' => null, 'value' => '0123456789'],
            
            // Jam Operasional
            ['key' => 'jam_mulai', 'key2' => null, 'value' => '08.00'],
            ['key' => 'jam_akhir', 'key2' => null, 'value' => '17.00'],
            ['key' => 'jam_sabtu', 'key2' => null, 'value' => '09.00'],
            
            // Lokasi
            ['key' => 'link_google_maps', 'key2' => null, 'value' => 'https://maps.google.com/maps?q=asrama+haji+landasan+ulin'],
            
            // Deskripsi
            ['key' => 'tentang', 'key2' => null, 'value' => 'Asrama Haji Landasan Ulin merupakan fasilitas penunjang yang disediakan untuk memenuhi kebutuhan pendaftaran dan pelayanan calon jamaah haji. Kami berkomitmen memberikan pelayanan terbaik untuk kenyamanan Anda.'],
            
            // Social Media
            ['key' => 'facebook', 'key2' => null, 'value' => 'https://facebook.com/asramahajilandasanulin'],
            ['key' => 'instagram', 'key2' => null, 'value' => 'https://instagram.com/asramahajilandasanulin'],
            ['key' => 'youtube', 'key2' => null, 'value' => 'https://youtube.com/@asramahajilandasanulin'],
            ['key' => 'telegram', 'key2' => null, 'value' => 'https://t.me/asramahajilandasanulin'],
            ['key' => 'twitter/x', 'key2' => null, 'value' => 'https://x.com/asramahajilandasanulin'],
            ['key' => 'whatsapp', 'key2' => null, 'value' => 'https://wa.me/60123456789'],
            
            // FAQ
            ['key' => 'faq', 'key2' => 'Bagaimana cara mendaftar sebagai calon jamaah haji?', 'value' => 'Anda dapat mendaftar dengan mengunjungi kantor kami atau melalui website resmi dengan membawa dokumen yang lengkap.'],
            
            ['key' => 'faq', 'key2' => 'Berapa biaya pendaftaran calon jamaah haji?', 'value' => 'Biaya pendaftaran dapat Anda lihat di website resmi atau hubungi customer service kami untuk informasi terbaru.'],
            
            ['key' => 'faq', 'key2' => 'Apa saja persyaratan untuk mendaftar haji?', 'value' => 'Persyaratan umum meliputi KTP, KK, Akte Lahir, Kartu BPIH, Foto 4x6, dan memenuhi kriteria kesehatan yang ditentukan.'],
            
            // Terms & Conditions
            ['key' => 'term&conditions', 'key2' => 'Sikap Santun', 'value' => 'Peserta harus bersikap santun dan mematuhi peraturan yang berlaku di Asrama Haji Landasan Ulin.'],
            ['key' => 'term&conditions', 'key2' => 'Kerahasiaan Data', 'value' => 'Segala data pribadi peserta dijaga kerahasiaannya sesuai dengan peraturan perlindungan data pribadi.'],
            ['key' => 'term&conditions', 'key2' => 'Barang Berharga', 'value' => 'Asrama Haji tidak bertanggung jawab atas barang berharga yang hilang selama di fasilitas kami.'],

            // Kebijakan Privasi
            ['key' => 'kebijakan_privasi', 'key2' => 'Informasi yang Kami Kumpulkan', 'value' => 'Kami mengumpulkan informasi pribadi yang Anda berikan kepada kami secara sukarela saat Anda mendaftar di situs kami, mengekspresikan minat untuk memperoleh informasi tentang kami atau produk dan layanan kami, atau sebaliknya saat Anda menghubungi kami.'],
            ['key' => 'kebijakan_privasi', 'key2' => 'Bagaimana Kami Menggunakan Informasi Anda', 'value' => 'Kami menggunakan informasi pribadi yang dikumpulkan melalui situs kami untuk berbagai tujuan bisnis yang dijelaskan di bawah ini. Kami memproses informasi pribadi Anda untuk tujuan-tujuan ini berdasarkan kepentingan bisnis kami yang sah, dalam rangka masuk ke dalam atau melakukan kontrak dengan Anda, dengan persetujuan Anda, dan/atau untuk kepatuhan dengan kewajiban hukum kami.'],
            ['key' => 'kebijakan_privasi', 'key2' => 'Keamanan Informasi', 'value' => 'Meskipun kami berusaha keras untuk melindungi informasi Anda, tidak ada metode transmisi melalui internet atau penyimpanan elektronik yang 100% aman. Oleh karena itu, kami tidak dapat menjamin keamanan mutlak.'],
            ['key' => 'kebijakan_privasi', 'key2' => 'Berbagi Informasi Anda', 'value' => 'Kami tidak menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda terlebih dahulu, kecuali sebagaimana diharuskan oleh hukum.'],
            ['key' => 'kebijakan_privasi', 'key2' => 'Retensi Data', 'value' => 'Kami menyimpan informasi pribadi Anda hanya selama diperlukan untuk memberikan layanan kepada Anda dan untuk memenuhi tujuan yang dijelaskan dalam kebijakan privasi ini.'],
            ['key' => 'kebijakan_privasi', 'key2' => 'Hak Anda', 'value' => 'Anda memiliki hak untuk mengakses, memperbaiki, dan menghapus informasi pribadi Anda.'],
        ];

        DB::table('tentang')->insert($data);
    }
}

