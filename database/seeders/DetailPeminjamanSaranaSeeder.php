<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPeminjamanSaranaSeeder extends Seeder {
    public function run(): void {
        DB::table('detail_peminjaman_sarana')->insert([
            ['id' => 1, 'sarana_id' => 1, 'peminjaman_id' => 1, 'jumlah' => '2'],
            ['id' => 2, 'sarana_id' => 3, 'peminjaman_id' => 2, 'jumlah' => '1'],
            ['id' => 3, 'sarana_id' => 2, 'peminjaman_id' => 3, 'jumlah' => '5'],
        ]);
    }
}