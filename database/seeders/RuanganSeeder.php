<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder {
    public function run(): void {
        DB::table('ruangan')->delete();
        
        $ruangans = [];
        
        // Gedung Arafah (id_gedung = 1) - 10 Kamar Standar
        for ($i = 1; $i <= 10; $i++) {
            $lantai = ($i <= 5) ? 1 : 2;
            $nomor = 100 + $i;
            $ruangans[] = [
                'id_ruangan' => $i,
                'gedung_id' => 1,
                'nama_ruangan' => "Kamar Arafah " . $nomor,
                'tipe_ruangan' => 'KAMAR_STANDAR',
                'lantai' => $lantai,
                'kapasitas' => 4,
                'gender_policy' => ($i % 2 === 0) ? 'MALE_ONLY' : 'FEMALE_ONLY',
                'keterangan' => "Kamar asrama standar lantai {$lantai} dengan kapasitas 4 orang, lengkap dengan kipas angin, lemari, dan kamar mandi dalam."
            ];
        }

        // Gedung Mina (id_gedung = 2) - 5 Kamar VIP
        for ($i = 1; $i <= 5; $i++) {
            $id = 10 + $i;
            $ruangans[] = [
                'id_ruangan' => $id,
                'gedung_id' => 2,
                'nama_ruangan' => "Kamar Mina VIP " . (200 + $i),
                'tipe_ruangan' => 'KAMAR_VIP',
                'lantai' => 1,
                'kapasitas' => 2,
                'gender_policy' => 'MIXED',
                'keterangan' => "Kamar tipe VIP asrama haji dengan tempat tidur twin-bed premium, pendingin ruangan (AC), kulkas kecil, TV, dan pemanas air mandi."
            ];
        }

        // Gedung Muzdalifah (id_gedung = 3) - 12 Kamar Standar
        for ($i = 1; $i <= 12; $i++) {
            $id = 15 + $i;
            $lantai = ($i <= 6) ? 1 : 2;
            $nomor = 300 + $i;
            $ruangans[] = [
                'id_ruangan' => $id,
                'gedung_id' => 3,
                'nama_ruangan' => "Kamar Muzdalifah " . $nomor,
                'tipe_ruangan' => 'KAMAR_STANDAR',
                'lantai' => $lantai,
                'kapasitas' => 6,
                'gender_policy' => ($i % 2 === 0) ? 'MALE_ONLY' : 'FEMALE_ONLY',
                'keterangan' => "Kamar tipe asrama berkapasitas besar (6 orang) dengan tempat tidur bertingkat, pendingin ruangan (AC), dan loker penyimpanan barang."
            ];
        }

        // Gedung Madinah (id_gedung = 4) - 6 Kamar Premium
        for ($i = 1; $i <= 6; $i++) {
            $id = 27 + $i;
            $ruangans[] = [
                'id_ruangan' => $id,
                'gedung_id' => 4,
                'nama_ruangan' => "Kamar Madinah Premium " . (400 + $i),
                'tipe_ruangan' => 'KAMAR_PREMIUM',
                'lantai' => 1,
                'kapasitas' => 2,
                'gender_policy' => 'MIXED',
                'keterangan' => "Kamar asrama eksklusif dengan single-bed ukuran King, AC, Smart TV, Wi-Fi kencang, sofa rileks, dan bath-tub."
            ];
        }

        // Gedung Makkah (id_gedung = 5) - 3 Ruangan
        $ruangans[] = [
            'id_ruangan' => 34,
            'gedung_id' => 5,
            'nama_ruangan' => 'Ruang Rapat Makkah',
            'tipe_ruangan' => 'RUANG_MEETING',
            'lantai' => 1,
            'kapasitas' => 40,
            'gender_policy' => 'MIXED',
            'keterangan' => 'Ruang rapat kapasitas 40 orang dengan meja melingkar, proyektor, whiteboard, dan sound system standar.'
        ];
        $ruangans[] = [
            'id_ruangan' => 35,
            'gedung_id' => 5,
            'nama_ruangan' => 'Aula Makkah Kecil',
            'tipe_ruangan' => 'AULA',
            'lantai' => 1,
            'kapasitas' => 100,
            'gender_policy' => 'MIXED',
            'keterangan' => 'Aula berukuran sedang cocok untuk acara seminar, syukuran, atau pelatihan dengan kapasitas hingga 100 orang.'
        ];
        $ruangans[] = [
            'id_ruangan' => 36,
            'gedung_id' => 5,
            'nama_ruangan' => 'Ruang Transit Makkah',
            'tipe_ruangan' => 'RUANG_LAINNYA',
            'lantai' => 1,
            'kapasitas' => 10,
            'gender_policy' => 'MIXED',
            'keterangan' => 'Ruang tunggu ber-AC dengan sofa empuk yang diperuntukkan bagi tamu VIP atau pengisi acara.'
        ];

        // Gedung Multazam (id_gedung = 6) - Gedung Khusus 1 Ruangan
        $ruangans[] = [
            'id_ruangan' => 37,
            'gedung_id' => 6,
            'nama_ruangan' => 'Aula Akbar Multazam',
            'tipe_ruangan' => 'AULA',
            'lantai' => 1,
            'kapasitas' => 800,
            'gender_policy' => 'MIXED',
            'keterangan' => 'Aula utama Asrama Haji dengan kapasitas raksasa hingga 800 orang. Sangat ideal untuk pelepasan jamaah, resepsi pernikahan besar, dan wisuda.'
        ];

        // Gedung Shafa (id_gedung = 7) - Gedung Khusus 1 Ruangan
        $ruangans[] = [
            'id_ruangan' => 38,
            'gedung_id' => 7,
            'nama_ruangan' => 'Executive Boardroom Shafa',
            'tipe_ruangan' => 'RUANG_MEETING',
            'lantai' => 1,
            'kapasitas' => 30,
            'gender_policy' => 'MIXED',
            'keterangan' => 'Ruang pertemuan tingkat VVIP dengan kursi kulit mewah, sistem video conference canggih, proyektor laser ultra HD, dan AC central.'
        ];

        // Gedung Marwah (id_gedung = 8) - 2 Ruangan
        $ruangans[] = [
            'id_ruangan' => 39,
            'gedung_id' => 8,
            'nama_ruangan' => 'Ruang Rapat Marwah 1',
            'tipe_ruangan' => 'RUANG_MEETING',
            'lantai' => 1,
            'kapasitas' => 15,
            'gender_policy' => 'MIXED',
            'keterangan' => 'Ruang rapat berskala kecil untuk staf internal maupun eksternal.'
        ];
        $ruangans[] = [
            'id_ruangan' => 40,
            'gedung_id' => 8,
            'nama_ruangan' => 'Ruang Rapat Marwah 2',
            'tipe_ruangan' => 'RUANG_MEETING',
            'lantai' => 1,
            'kapasitas' => 15,
            'gender_policy' => 'MIXED',
            'keterangan' => 'Ruang rapat cadangan di Gedung Administrasi.'
        ];

        DB::table('ruangan')->insert($ruangans);
    }
}