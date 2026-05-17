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