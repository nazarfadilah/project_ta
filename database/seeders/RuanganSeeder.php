<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder {
    public function run(): void {
        DB::table('ruangan')->delete();
        
        $ruangans = [
            [
                'id_ruangan' => 1,
                'gedung_id' => null,
                'nama_ruangan' => 'Aula Jabal Rahmah',
                'tipe_ruangan' => 'AULA',
                'lantai' => 1,
                'kapasitas' => 500,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Aula pertemuan besar berkapasitas hingga 500 orang, ber-AC, sound system lengkap, panggung, cocok untuk seminar, resepsi, atau pelatihan.'
            ],
            [
                'id_ruangan' => 2,
                'gedung_id' => null,
                'nama_ruangan' => 'Aula Jeddah',
                'tipe_ruangan' => 'AULA',
                'lantai' => 1,
                'kapasitas' => 300,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Aula berukuran sedang kapasitas 300 orang dengan fasilitas AC, sound system, meja kursi lipat.'
            ],
            [
                'id_ruangan' => 3,
                'gedung_id' => null,
                'nama_ruangan' => 'Aula Mekkah',
                'tipe_ruangan' => 'AULA',
                'lantai' => 1,
                'kapasitas' => 200,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Aula sedang berkapasitas 200 orang, ber-AC, sound system, sangat pas untuk acara internal atau bimtek.'
            ],
            [
                'id_ruangan' => 4,
                'gedung_id' => null,
                'nama_ruangan' => 'Aula Aziziyah',
                'tipe_ruangan' => 'AULA',
                'lantai' => 1,
                'kapasitas' => 150,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Aula Aziziyah berkapasitas 150 orang, AC, sound system, whiteboard.'
            ],
            [
                'id_ruangan' => 5,
                'gedung_id' => null,
                'nama_ruangan' => 'Kamar Superior',
                'tipe_ruangan' => 'KAMAR_VIP',
                'lantai' => 1,
                'kapasitas' => 2,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Kamar tipe VIP dengan tempat tidur twin-bed premium, pendingin ruangan (AC), kulkas kecil, TV, dan pemanas air mandi.'
            ],
            [
                'id_ruangan' => 6,
                'gedung_id' => null,
                'nama_ruangan' => 'Kamar Standar',
                'tipe_ruangan' => 'KAMAR_STANDAR',
                'lantai' => 1,
                'kapasitas' => 4,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Kamar asrama standar dengan kapasitas 4 orang, pendingin ruangan (AC), lemari pakaian, dan kamar mandi dalam.'
            ],
            [
                'id_ruangan' => 7,
                'gedung_id' => null,
                'nama_ruangan' => 'Kamar Ekonomi',
                'tipe_ruangan' => 'KAMAR_STANDAR',
                'lantai' => 1,
                'kapasitas' => 6,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Kamar tipe ekonomi berkapasitas 6 orang dengan tempat tidur tingkat, kipas angin, dan kamar mandi luar/dalam.'
            ],
            [
                'id_ruangan' => 8,
                'gedung_id' => null,
                'nama_ruangan' => 'Kamar Standar Double Bed',
                'tipe_ruangan' => 'KAMAR_STANDAR',
                'lantai' => 1,
                'kapasitas' => 2,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Kamar standar dengan fasilitas 1 double-bed untuk 2 orang, AC, lemari, dan kamar mandi dalam.'
            ],
            [
                'id_ruangan' => 9,
                'gedung_id' => null,
                'nama_ruangan' => 'Kamar Superior 4 Single Bed',
                'tipe_ruangan' => 'KAMAR_PREMIUM',
                'lantai' => 1,
                'kapasitas' => 4,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Kamar premium berkapasitas 4 orang dengan 4 single-bed, AC, Smart TV, kulkas, dan pemanas air mandi.'
            ],
            [
                'id_ruangan' => 10,
                'gedung_id' => null,
                'nama_ruangan' => 'Ruang Belajar',
                'tipe_ruangan' => 'RUANG_MEETING',
                'lantai' => 1,
                'kapasitas' => 40,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Ruang belajar / kelas berkapasitas 40 orang dengan kursi belajar, AC, proyektor, dan whiteboard.'
            ],
            [
                'id_ruangan' => 11,
                'gedung_id' => null,
                'nama_ruangan' => 'Ruang Belajar 3',
                'tipe_ruangan' => 'RUANG_MEETING',
                'lantai' => 1,
                'kapasitas' => 30,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Ruang kelas sedang dengan kapasitas 30 orang, dilengkapi AC, meja-kursi, whiteboard, dan proyektor.'
            ],
            [
                'id_ruangan' => 12,
                'gedung_id' => null,
                'nama_ruangan' => 'Ruang Belajar 4',
                'tipe_ruangan' => 'RUANG_MEETING',
                'lantai' => 1,
                'kapasitas' => 30,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Ruang kelas sedang dengan kapasitas 30 orang, dilengkapi AC, meja-kursi, whiteboard, dan proyektor.'
            ],
            [
                'id_ruangan' => 13,
                'gedung_id' => null,
                'nama_ruangan' => 'Ruang Kelas',
                'tipe_ruangan' => 'RUANG_MEETING',
                'lantai' => 1,
                'kapasitas' => 50,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Ruang kelas besar kapasitas 50 orang dengan meja-kursi lipat, AC, sound system mini, proyektor, dan whiteboard.'
            ],
            [
                'id_ruangan' => 14,
                'gedung_id' => null,
                'nama_ruangan' => 'Area Manasik',
                'tipe_ruangan' => 'RUANG_LAINNYA',
                'lantai' => 1,
                'kapasitas' => 100,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Miniatur Kakbah dan area manasik haji outdoor untuk pelatihan peragaan ibadah haji/umrah.'
            ],
            [
                'id_ruangan' => 15,
                'gedung_id' => null,
                'nama_ruangan' => 'Halaman',
                'tipe_ruangan' => 'RUANG_LAINNYA',
                'lantai' => 1,
                'kapasitas' => 200,
                'gender_policy' => 'MIXED',
                'keterangan' => 'Halaman terbuka hijau yang luas, cocok untuk kegiatan outbound, pameran, senam, atau parkir tambahan.'
            ]
        ];

        DB::table('ruangan')->insert($ruangans);
    }
}