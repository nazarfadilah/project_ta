<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder {
    public function run(): void {
        DB::table('gedung')->delete();
        
        DB::table('gedung')->insert([
            ['id_gedung' => 1, 'nama_gedung' => 'Gedung Arafah', 'koordinat' => '-3.3211, 114.5912', 'keterangan' => 'Gedung Asrama Standar, 3 lantai. Dilengkapi AC dan kipas angin.'],
            ['id_gedung' => 2, 'nama_gedung' => 'Gedung Mina', 'koordinat' => '-3.3215, 114.5915', 'keterangan' => 'Gedung Asrama VIP, 2 lantai. Kamar berukuran besar dengan fasilitas lengkap.'],
            ['id_gedung' => 3, 'nama_gedung' => 'Gedung Muzdalifah', 'koordinat' => '-3.3220, 114.5920', 'keterangan' => 'Gedung Asrama Utama, 3 lantai. Pilihan favorit untuk rombongan besar.'],
            ['id_gedung' => 4, 'nama_gedung' => 'Gedung Madinah', 'koordinat' => '-3.3225, 114.5925', 'keterangan' => 'Gedung Asrama Premium, 2 lantai. Desain interior modern bernuansa Arab.'],
            ['id_gedung' => 5, 'nama_gedung' => 'Gedung Makkah', 'koordinat' => '-3.3230, 114.5930', 'keterangan' => 'Gedung Serbaguna dan Ruang Rapat skala menengah.'],
            ['id_gedung' => 6, 'nama_gedung' => 'Gedung Multazam', 'koordinat' => '-3.3235, 114.5935', 'keterangan' => 'Gedung Khusus Aula Akbar. Hanya berisi satu ruangan aula berkapasitas sangat besar.'],
            ['id_gedung' => 7, 'nama_gedung' => 'Gedung Shafa', 'koordinat' => '-3.3240, 114.5940', 'keterangan' => 'Gedung Khusus Meeting Eksekutif. Hanya berisi satu ruang rapat eksklusif VVIP.'],
            ['id_gedung' => 8, 'nama_gedung' => 'Gedung Marwah', 'koordinat' => '-3.3245, 114.5945', 'keterangan' => 'Gedung Kantor, Administrasi, dan Ruang Rapat Staf.'],
        ]);
    }
}