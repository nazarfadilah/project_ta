<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [];
        $categories = ['pengapian', 'moshulla', 'aula', 'gedung'];
        
        // Data untuk Kategori Pengapian
        $pengapianData = [
            [
                'judul' => 'Ruang Pengapian Utama',
                'isi' => 'Ruang pengapian utama yang luas dan modern dengan kapasitas mengakomodasi ratusan jamaah haji. Dilengkapi dengan sistem ventilasi dan pencahayaan yang baik untuk kenyamanan beribadah.',
            ],
            [
                'judul' => 'Pengapian untuk Wanita',
                'isi' => 'Ruang pengapian khusus untuk calon jamaah haji wanita yang dirancang dengan mempertimbangkan privasi dan kenyamanan maksimal.',
            ],
            [
                'judul' => 'Area Wudhu Pengapian',
                'isi' => 'Area wudhu yang luas dan bersih dengan fasilitas lengkap untuk mempersiapkan diri sebelum beribadah di ruang pengapian.',
            ],
        ];
        
        // Data untuk Kategori Moshulla
        $moshullaData = [
            [
                'judul' => 'Musholla Pusat',
                'isi' => 'Musholla pusat dengan desain modern dan nyaman, dilengkapi dengan speaker system dan layar display untuk informasi jamaah.',
            ],
            [
                'judul' => 'Musholla Lantai 2',
                'isi' => 'Musholla tambahan di lantai 2 untuk menampung jamaah lebih banyak, terutama saat jam-jam sibuk pendaftaran.',
            ],
        ];
        
        // Data untuk Kategori Aula
        $aulaData = [
            [
                'judul' => 'Aula Utama Asrama',
                'isi' => 'Aula utama yang dapat digunakan untuk acara penting, seminar, dan workshop tentang persiapan ibadah haji. Dilengkapi dengan sistem audio visual yang lengkap.',
            ],
            [
                'judul' => 'Aula Kecil Meeting Room',
                'isi' => 'Ruang pertemuan kecil yang nyaman untuk konsultasi dan diskusi kelompok dengan tim pelayanan kami.',
            ],
        ];
        
        // Data untuk Kategori Gedung
        $gedungData = [
            [
                'judul' => 'Gedung Utama Asrama Haji',
                'isi' => 'Gedung utama Asrama Haji Landasan Ulin yang megah dan modern. Bangunan ini merupakan pusat pelayanan dan administrasi calon jamaah haji.',
            ],
            [
                'judul' => 'Gedung Penunjang Asrama',
                'isi' => 'Gedung penunjang dengan berbagai ruang fungsional untuk mendukung kelancaran pelayanan kepada calon jamaah haji.',
            ],
            [
                'judul' => 'Gedung Asrama dari Udara',
                'isi' => 'Pemandangan udara dari komplek Asrama Haji Landasan Ulin menunjukkan tata letak dan infrastruktur yang terorganisir dengan baik.',
            ],
        ];
        
        $categoryData = [
            'pengapian' => $pengapianData,
            'moshulla' => $moshullaData,
            'aula' => $aulaData,
            'gedung' => $gedungData,
        ];
        
        // Generate gallery data
        foreach ($categories as $category) {
            $dataArray = $categoryData[$category];
            $itemCount = count($dataArray);
            
            for ($i = 0; $i < $itemCount; $i++) {
                $galleries[] = [
                    'kategori' => $category,
                    'judul' => $dataArray[$i]['judul'],
                    'isi' => $dataArray[$i]['isi'],
                    'gambar' => 'https://via.placeholder.com/400x300?text=' . urlencode($dataArray[$i]['judul']),
                    'created_at' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d H:i:s'),
                ];
            }
        }

        DB::table('galeri')->insert($galleries);
    }
}
