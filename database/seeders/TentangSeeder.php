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
        DB::table('tentang')->delete();

        $data = [
            // id 1 to 17
            ['id' => 1, 'key' => 'nama_instansi', 'key2' => null, 'value' => 'Asrama Haji Landasan Ulin'],
            ['id' => 2, 'key' => 'logo', 'key2' => null, 'value' => 'storage/logo/jN2j0l6Kc9KeXHoNOqLyGrdHSfBKcrIKPlTqE5pW.png'],
            ['id' => 3, 'key' => 'email', 'key2' => null, 'value' => 'uptbanjarmasin@gmail.com'],
            ['id' => 4, 'key' => 'alamat', 'key2' => null, 'value' => 'Jalan Jenderal A. Yani Km. 28, Landasan Ulin, Banjarbaru, Kalimantan Selatan'],
            ['id' => 5, 'key' => 'no_hp', 'key2' => null, 'value' => '08114838448'],
            ['id' => 6, 'key' => 'kantor', 'key2' => null, 'value' => '(0511) 4705150'],
            ['id' => 7, 'key' => 'jam_mulai', 'key2' => null, 'value' => '08.00'],
            ['id' => 8, 'key' => 'jam_akhir', 'key2' => null, 'value' => '17.00'],
            ['id' => 9, 'key' => 'jam_sabtu', 'key2' => null, 'value' => '09.00'],
            ['id' => 10, 'key' => 'link_google_maps', 'key2' => null, 'value' => 'https://maps.google.com/maps?q=asrama+haji+landasan+ulin'],
            ['id' => 11, 'key' => 'tentang', 'key2' => null, 'value' => 'Asrama Haji Landasan Ulin merupakan fasilitas penunjang yang disediakan untuk memenuhi kebutuhan pendaftaran dan pelayanan calon jamaah haji. Kami berkomitmen memberikan pelayanan terbaik untuk kenyamanan Anda.'],
            ['id' => 12, 'key' => 'facebook', 'key2' => null, 'value' => 'https://www.facebook.com/share/1BWd3BbQwj/'],
            ['id' => 13, 'key' => 'instagram', 'key2' => null, 'value' => 'https://www.instagram.com/kemenhaj.asramahaji.bjm'],
            ['id' => 14, 'key' => 'youtube', 'key2' => null, 'value' => 'https://www.youtube.com/@asramahajibanjarmasin'],
            ['id' => 15, 'key' => 'telegram', 'key2' => null, 'value' => 'https://t.me/asramahajilandasanulin'],
            ['id' => 16, 'key' => 'twitter/x', 'key2' => null, 'value' => 'https://x.com/asramahajilandasanulin'],
            ['id' => 17, 'key' => 'whatsapp', 'key2' => null, 'value' => 'https://wa.me/628114838448'],
            
            // id 18 to 20 (faq)
            ['id' => 18, 'key' => 'faq', 'key2' => 'Bagaimana cara mendaftar sebagai calon jamaah haji?', 'value' => 'Anda dapat mendaftar dengan mengunjungi kantor kami atau melalui website resmi dengan membawa dokumen yang lengkap.'],
            ['id' => 19, 'key' => 'faq', 'key2' => 'Berapa biaya pendaftaran calon jamaah haji?', 'value' => 'Biaya pendaftaran dapat Anda lihat di website resmi atau hubungi customer service kami untuk informasi terbaru.'],
            ['id' => 20, 'key' => 'faq', 'key2' => 'Apa saja persyaratan untuk mendaftar haji?', 'value' => 'Persyaratan umum meliputi KTP, KK, Akte Lahir, Kartu BPIH, Foto 4x6, dan memenuhi kriteria kesehatan yang ditentukan.'],
            
            // id 21 to 23 (term&conditions)
            ['id' => 21, 'key' => 'term&conditions', 'key2' => 'Sikap Santun', 'value' => 'Peserta harus bersikap santun dan mematuhi peraturan yang berlaku di Asrama Haji Landasan Ulin.'],
            ['id' => 22, 'key' => 'term&conditions', 'key2' => 'Kerahasiaan Data', 'value' => 'Segala data pribadi peserta dijaga kerahasiaannya sesuai dengan peraturan perlindungan data pribadi.'],
            ['id' => 23, 'key' => 'term&conditions', 'key2' => 'Barang Berharga', 'value' => 'Asrama Haji tidak bertanggung jawab atas barang berharga yang hilang selama di fasilitas kami.'],
            
            // id 24 to 29 (kebijakan_privasi)
            ['id' => 24, 'key' => 'kebijakan_privasi', 'key2' => 'Informasi yang Kami Kumpulkan', 'value' => 'Kami mengumpulkan informasi pribadi yang Anda berikan kepada kami secara sukarela saat Anda mendaftar di situs kami, mengekspresikan minat untuk memperoleh informasi tentang kami atau produk dan layanan kami, atau sebaliknya saat Anda menghubungi kami.'],
            ['id' => 25, 'key' => 'kebijakan_privasi', 'key2' => 'Bagaimana Kami Menggunakan Informasi Anda', 'value' => 'Kami menggunakan informasi pribadi yang dikumpulkan melalui situs kami untuk berbagai tujuan bisnis yang dijelaskan di bawah ini. Kami memproses informasi pribadi Anda untuk tujuan-tujuan ini berdasarkan kepentingan bisnis kami yang sah, dalam rangka masuk ke dalam atau melakukan kontrak dengan Anda, dengan persetujuan Anda, dan/atau untuk kepatuhan dengan kewajiban hukum kami.'],
            ['id' => 26, 'key' => 'kebijakan_privasi', 'key2' => 'Keamanan Informasi', 'value' => 'Meskipun kami berusaha keras untuk melindungi informasi Anda, tidak ada metode transmisi melalui internet atau penyimpanan elektronik yang 100% aman. Oleh karena itu, kami tidak dapat menjamin keamanan mutlak.'],
            ['id' => 27, 'key' => 'kebijakan_privasi', 'key2' => 'Berbagi Informasi Anda', 'value' => 'Kami tidak menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda terlebih dahulu, kecuali sebagaimana diharuskan oleh hukum.'],
            ['id' => 28, 'key' => 'kebijakan_privasi', 'key2' => 'Retensi Data', 'value' => 'Kami menyimpan informasi pribadi Anda hanya selama diperlukan untuk memberikan layanan kepada Anda dan untuk memenuhi tujuan yang dijelaskan dalam kebijakan privasi ini.'],
            ['id' => 29, 'key' => 'kebijakan_privasi', 'key2' => 'Hak Anda', 'value' => 'Anda memiliki hak untuk mengakses, memperbaiki, dan menghapus informasi pribadi Anda.'],

            // Custom dynamic footer keys
            ['id' => 30, 'key' => 'copyright', 'key2' => null, 'value' => '© 2026 Asrama Haji Emberkasi Landasan Ulin. Hak Cipta Dilindungi Undang-Undang.'],
            ['id' => 31, 'key' => 'naungan', 'key2' => null, 'value' => 'Dibawah naungan <strong>Kementerian Haji RI</strong> · Kanwil Kalimantan Selatan'],
            ['id' => 32, 'key' => 'e-katalog', 'key2' => null, 'value' => 'https://drive.google.com/drive/folders/19EJRGPIosgWPLrgf_FWMODZsm6PkYr-N'],
        ];

        DB::table('tentang')->insert($data);
    }
}
