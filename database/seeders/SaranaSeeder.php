<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaranaSeeder extends Seeder {
    public function run(): void {
        DB::table('sarana')->insert([
            ['id' => 1, 'nama' => 'Kasur Lipat', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-01-15', 'stok' => '5'],
            ['id' => 2, 'nama' => 'Kursi Tambahan', 'kondisi' => 'Baik Sekali', 'tgl_penerimaan' => '2026-02-01', 'stok' => '20'],
            ['id' => 3, 'nama' => 'Proyektor Portable', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-01-20', 'stok' => '2'],
        ]);
    }
}