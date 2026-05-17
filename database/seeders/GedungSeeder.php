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