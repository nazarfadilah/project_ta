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