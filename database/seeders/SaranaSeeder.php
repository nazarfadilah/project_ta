<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaranaSeeder extends Seeder {
    public function run(): void {
        DB::table('sarana')->delete();
        
        DB::table('sarana')->insert([
            ['id' => 1, 'nama' => 'Kursi Lipat Futura', 'kondisi' => 'Baik Sekali', 'tgl_penerimaan' => '2026-01-10', 'stok' => '350'],
            ['id' => 2, 'nama' => 'Proyektor Epson HD', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-02-05', 'stok' => '8'],
            ['id' => 3, 'nama' => 'Meja Rapat Lipat', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-01-15', 'stok' => '50'],
            ['id' => 4, 'nama' => 'Mikrofon Wireless Shure', 'kondisi' => 'Baik Sekali', 'tgl_penerimaan' => '2026-02-12', 'stok' => '15'],
            ['id' => 5, 'nama' => 'Kasur Lipat Busa', 'kondisi' => 'Normal', 'tgl_penerimaan' => '2026-01-20', 'stok' => '80'],
            ['id' => 6, 'nama' => 'Sound System Portable', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-02-22', 'stok' => '6'],
            ['id' => 7, 'nama' => 'AC Portable 5 PK', 'kondisi' => 'Baik Sekali', 'tgl_penerimaan' => '2026-03-01', 'stok' => '12'],
            ['id' => 8, 'nama' => 'Layar Proyektor Stand', 'kondisi' => 'Baik', 'tgl_penerimaan' => '2026-01-28', 'stok' => '10'],
            ['id' => 9, 'nama' => 'Papan Tulis Whiteboard Stand', 'kondisi' => 'Normal', 'tgl_penerimaan' => '2026-01-05', 'stok' => '15'],
            ['id' => 10, 'nama' => 'Genset Silent 50 KVA', 'kondisi' => 'Baik Sekali', 'tgl_penerimaan' => '2026-03-10', 'stok' => '2'],
            ['id' => 11, 'nama' => 'Tenda Saji Lipat', 'kondisi' => 'Perlu Perbaikan', 'tgl_penerimaan' => '2026-02-18', 'stok' => '5'],
            ['id' => 12, 'nama' => 'Podium Kayu Jati', 'kondisi' => 'Baik Sekali', 'tgl_penerimaan' => '2026-01-02', 'stok' => '4'],
        ]);
    }
}