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
        
        $guests = [];
        
        for ($i = 1; $i <= 62; $i++) {
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
                'notes' => ($i % 5 === 0) ? 'Tamu rombongan dinas' : (($i % 7 === 0) ? 'Tamu reguler prioritas' : 'Tamu umum pendaftar web'),
                'createdAt' => now(),
                'updatedAt' => now(),
            ];
        }
        
        DB::table('guest')->insert($guests);
    }
}