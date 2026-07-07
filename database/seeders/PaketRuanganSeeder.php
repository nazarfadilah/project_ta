<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketRuanganSeeder extends Seeder {
    public function run(): void {
        DB::table('paket_ruangan')->delete();
        
        $pakets = [];
        $pakets[] = [
            'id' => 1,
            'ruangan_id' => 1,
            'nama_paket' => 'Sewa Harian Aula Jabal Rahmah',
            'durasi' => 24,
            'harga' => 3000000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 2,
            'ruangan_id' => 2,
            'nama_paket' => 'Sewa Harian Aula Jeddah',
            'durasi' => 24,
            'harga' => 2000000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 3,
            'ruangan_id' => 3,
            'nama_paket' => 'Sewa Harian Aula Mekkah',
            'durasi' => 24,
            'harga' => 1500000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 4,
            'ruangan_id' => 4,
            'nama_paket' => 'Sewa Harian Aula Aziziyah',
            'durasi' => 24,
            'harga' => 1200000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 5,
            'ruangan_id' => 5,
            'nama_paket' => 'Sewa Harian Kamar Superior',
            'durasi' => 24,
            'harga' => 400000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 6,
            'ruangan_id' => 6,
            'nama_paket' => 'Sewa Harian Kamar Standar',
            'durasi' => 24,
            'harga' => 150000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 7,
            'ruangan_id' => 7,
            'nama_paket' => 'Sewa Harian Kamar Ekonomi',
            'durasi' => 24,
            'harga' => 100000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 8,
            'ruangan_id' => 8,
            'nama_paket' => 'Sewa Harian Kamar Standar Double Bed',
            'durasi' => 24,
            'harga' => 200000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 9,
            'ruangan_id' => 9,
            'nama_paket' => 'Sewa Harian Kamar Superior 4 Single Bed',
            'durasi' => 24,
            'harga' => 300000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 10,
            'ruangan_id' => 10,
            'nama_paket' => 'Paket Harian Ruang Belajar',
            'durasi' => 24,
            'harga' => 500000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 11,
            'ruangan_id' => 11,
            'nama_paket' => 'Paket Harian Ruang Belajar 3',
            'durasi' => 24,
            'harga' => 400000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 12,
            'ruangan_id' => 12,
            'nama_paket' => 'Paket Harian Ruang Belajar 4',
            'durasi' => 24,
            'harga' => 400000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 13,
            'ruangan_id' => 13,
            'nama_paket' => 'Paket Harian Ruang Kelas',
            'durasi' => 24,
            'harga' => 600000.00,
            'currency' => 'IDR',
            'isExclusive' => 0,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 14,
            'ruangan_id' => 14,
            'nama_paket' => 'Paket Harian Area Manasik',
            'durasi' => 24,
            'harga' => 800000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];
        $pakets[] = [
            'id' => 15,
            'ruangan_id' => 15,
            'nama_paket' => 'Paket Harian Halaman',
            'durasi' => 24,
            'harga' => 1000000.00,
            'currency' => 'IDR',
            'isExclusive' => 1,
            'status' => 'ACTIVE'
        ];

        DB::table('paket_ruangan')->insert($pakets);
    }
}