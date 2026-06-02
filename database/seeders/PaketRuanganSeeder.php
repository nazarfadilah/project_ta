<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketRuanganSeeder extends Seeder {
    public function run(): void {
        DB::table('paket_ruangan')->delete();
        
        $pakets = [];
        
        // Kamar Standar Arafah (1 - 10)
        for ($i = 1; $i <= 10; $i++) {
            $pakets[] = [
                'id' => $i,
                'ruangan_id' => $i,
                'nama_paket' => 'Sewa Harian Arafah',
                'durasi' => 24,
                'harga' => 150000.00,
                'currency' => 'IDR',
                'isExclusive' => 0,
                'status' => 'ACTIVE'
            ];
        }

        // Kamar VIP Mina (11 - 15)
        for ($i = 11; $i <= 15; $i++) {
            $pakets[] = [
                'id' => $i,
                'ruangan_id' => $i,
                'nama_paket' => 'Sewa Harian VIP Mina',
                'durasi' => 24,
                'harga' => 450000.00,
                'currency' => 'IDR',
                'isExclusive' => 0,
                'status' => 'ACTIVE'
            ];
        }

        // Kamar Standar Muzdalifah (16 - 27)
        for ($i = 16; $i <= 27; $i++) {
            $pakets[] = [
                'id' => $i,
                'ruangan_id' => $i,
                'nama_paket' => 'Sewa Harian Muzdalifah',
                'durasi' => 24,
                'harga' => 200000.00,
                'currency' => 'IDR',
                'isExclusive' => 0,
                'status' => 'ACTIVE'
            ];
        }

        // Kamar Premium Madinah (28 - 33)
        for ($i = 28; $i <= 33; $i++) {
            $pakets[] = [
                'id' => $i,
                'ruangan_id' => $i,
                'nama_paket' => 'Sewa Harian Premium Madinah',
                'durasi' => 24,
                'harga' => 600000.00,
                'currency' => 'IDR',
                'isExclusive' => 0,
                'status' => 'ACTIVE'
            ];
        }

        // Ruangan Makkah (34, 35, 36)
        $pakets[] = [
            'id' => 34,
            'ruangan_id' => 34,
            'nama_paket' => 'Paket Meeting Makkah (8 Jam)',
            'durasi' => 8,
            'harga' => 800000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 35,
            'ruangan_id' => 35,
            'nama_paket' => 'Sewa Harian Aula Makkah',
            'durasi' => 24,
            'harga' => 1500000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 36,
            'ruangan_id' => 36,
            'nama_paket' => 'Paket Transit VIP (12 Jam)',
            'durasi' => 12,
            'harga' => 250000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];

        // Aula Akbar Multazam (37)
        $pakets[] = [
            'id' => 37,
            'ruangan_id' => 37,
            'nama_paket' => 'Sewa Harian Aula Multazam (Exclusive)',
            'durasi' => 24,
            'harga' => 5000000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];

        // Executive Boardroom Shafa (38)
        $pakets[] = [
            'id' => 38,
            'ruangan_id' => 38,
            'nama_paket' => 'Paket VVIP Boardroom Shafa (8 Jam)',
            'durasi' => 8,
            'harga' => 2000000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];

        // Marwah (39, 40)
        $pakets[] = [
            'id' => 39,
            'ruangan_id' => 39,
            'nama_paket' => 'Sewa Rapat Marwah 1 (4 Jam)',
            'durasi' => 4,
            'harga' => 300000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 40,
            'ruangan_id' => 40,
            'nama_paket' => 'Sewa Rapat Marwah 2 (4 Jam)',
            'durasi' => 4,
            'harga' => 300000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];

        DB::table('paket_ruangan')->insert($pakets);
    }
}