<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder {
    public function run(): void {
        DB::table('guest')->delete();
        
        $firstNamesM = ['Ahmad', 'Budi', 'Joko', 'Slamet', 'Herman', 'Agus', 'Tono', 'Hadi', 'Yudi', 'Adi', 'Amir', 'Anwar', 'Rian', 'Faisal', 'Rudi', 'Hendri', 'Eko', 'Syarif', 'Lukman', 'Bambang', 'Wawan', 'Supriadi', 'Mulyono', 'Hartono', 'Dedi', 'Roni', 'Indra', 'Surya', 'Taufik', 'Aris', 'Edi'];
        $firstNamesF = ['Siti', 'Aminah', 'Halimah', 'Nur', 'Rina', 'Dewi', 'Sri', 'Endang', 'Lilis', 'Yanti', 'Kartika', 'Indah', 'Fitri', 'Wati', 'Mega', 'Ani', 'Rahma', 'Salma', 'Diah', 'Hayati', 'Kusuma', 'Ratna', 'Laras', 'Ningsih', 'Sari', 'Puji', 'Yuni', 'Tri', 'Retno', 'Utami', 'Wulan'];
        
        $lastNames = ['Suryanto', 'Prasetyo', 'Wijaya', 'Susanto', 'Hidayat', 'Kurniawan', 'Santoso', 'Subagyo', 'Saputra', 'Wibowo', 'Irawan', 'Darmawan', 'Harahap', 'Lubis', 'Siregar', 'Simanjuntak', 'Nasution', 'Nugroho', 'Pratama', 'Raharjo', 'Mahendra', 'Kusuma', 'Gunawan', 'Budiman', 'Setiawan', 'Ariyanto', 'Darmanto'];
        
        $streets = ['Jl. Merdeka', 'Jl. Sudirman', 'Jl. Ahmad Yani', 'Jl. Kartini', 'Jl. Diponegoro', 'Jl. Hasanuddin', 'Jl. Gajah Mada', 'Jl. Pemuda', 'Jl. Pahlawan', 'Jl. Anggrek'];
        $cities = ['Banjarmasin', 'Banjarbaru', 'Martapura', 'Pelaihari', 'Rantau', 'Kandangan', 'Barabai', 'Amuntai', 'Tanjung', 'Batulicin'];
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $instansis = [
            'LPTQ PROV. Kalsel',
            'Kanwil Kemenag Prov. Kalteng (Bid. Penmad)',
            'Travel Mahabbah',
            'CV. Rpy Production',
            'UIN Antasari Banjarmasin',
            'PT. Borneo Jaya Teknika',
            'Dinas Pendidikan Prov. Kalsel',
            'Bawaslu Kota Banjarmasin',
            'Polda Kalimantan Selatan',
            'Universitas Lambung Mangkurat',
            'Dinas Kesehatan Banjarbaru',
            'Kementerian Haji Kota Banjarmasin',
            'Personal',
            'MTs Negeri 2 Banjarmasin',
            'MAN 1 Kota Banjarbaru'
        ];

        $specificGuests = [
            1 => [
                'name' => 'Ibu Niswati',
                'gender' => 'FEMALE',
                'phone' => '08525261001',
                'instansi' => 'LPTQ PROV. Kalsel',
            ],
            2 => [
                'name' => 'Bpk Ahmad Maki',
                'gender' => 'MALE',
                'phone' => '08135273002',
                'instansi' => 'Kanwil Kemenag Prov. Kalteng (Bid. Penmad)',
            ],
            3 => [
                'name' => 'Bpk Haris Fadillah',
                'gender' => 'MALE',
                'phone' => '08215496003',
                'instansi' => 'LPTQ PROV. Kalsel',
            ],
            4 => [
                'name' => 'Bpk Bahar',
                'gender' => 'MALE',
                'phone' => '08538680004',
                'instansi' => 'Travel Mahabbah',
            ],
            5 => [
                'name' => 'Ibu Chrisna',
                'gender' => 'FEMALE',
                'phone' => '08534800005',
                'instansi' => 'CV. Rpy Production',
            ],
            6 => [
                'name' => 'Ibu Munisah',
                'gender' => 'FEMALE',
                'phone' => '08125643006',
                'instansi' => 'UIN Antasari Banjarmasin',
            ],
            7 => [
                'name' => 'Bpk Lutfi Hakim',
                'gender' => 'MALE',
                'phone' => '08152273007',
                'instansi' => 'Bpk Lutfi Hakim/ Saiun',
            ]
        ];
        
        $guests = [];
        
        for ($i = 1; $i <= 62; $i++) {
            if (isset($specificGuests[$i])) {
                $name = $specificGuests[$i]['name'];
                $gender = $specificGuests[$i]['gender'];
                $phone = $specificGuests[$i]['phone'];
                $instansi = $specificGuests[$i]['instansi'];
            } else {
                $isMale = ($i % 2 === 1);
                $gender = $isMale ? 'MALE' : 'FEMALE';
                $firstName = $isMale ? $firstNamesM[array_rand($firstNamesM)] : $firstNamesF[array_rand($firstNamesF)];
                $lastName = $lastNames[array_rand($lastNames)];
                
                // Add Haji/Hajja for some realism
                $title = '';
                if ($i % 4 === 0) {
                    $title = $isMale ? 'Haji ' : 'Hajja ';
                }
                
                $name = $title . $firstName . ' ' . $lastName;
                $phone = '08' . rand(111111111, 999999999);
                $instansi = $instansis[array_rand($instansis)];
            }
            
            // Generate unique 16 digit NIK
            $nik = '6371' . sprintf('%02d', rand(1, 10)) . sprintf('%06d', rand(100000, 999999)) . sprintf('%04d', $i);
            
            $address = $streets[array_rand($streets)] . ' No. ' . rand(1, 150) . ', ' . $cities[array_rand($cities)];
            
            $guests[] = [
                'id' => $i,
                'nik' => $nik,
                'name' => $name,
                'gender' => $gender,
                'address' => $address,
                'bloodType' => $bloodTypes[array_rand($bloodTypes)],
                'instansi' => $instansi,
                'phone' => $phone,
                'notes' => ($i % 5 === 0) ? 'Tamu rombongan dinas' : (($i % 7 === 0) ? 'Tamu reguler prioritas' : 'Tamu umum pendaftar web'),
                'createdAt' => now(),
                'updatedAt' => now(),
            ];
        }
        
        DB::table('guest')->insert($guests);
    }
}