<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GambarDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gambar_dashboard')->delete();

        $gambars = [
            ['id' => 1, 'posisi' => 1, 'path' => 'gambar-landing/Jz1PZpKp7ku6FNaQ7pIByPnBnMMlTGYEFfCuM2vO.jpg', 'waktu_upload' => '2026-06-26 20:34:51'],
            ['id' => 2, 'posisi' => 2, 'path' => 'gambar-landing/PnWMW16zrue71XkToxWvr6DEKptbyAsWuDPX48cG.jpg', 'waktu_upload' => '2026-06-28 20:34:51'],
            ['id' => 3, 'posisi' => 3, 'path' => 'gambar-landing/Yp4MUDeQaEnUV5QO9EjR62Th7bQYFF5pVLWOK0Vt.jpg', 'waktu_upload' => '2026-06-17 20:34:51'],
            ['id' => 4, 'posisi' => 4, 'path' => 'gambar-landing/iTqcS47vdqdJNvZXaP7S6pEeR5f1R75PwJDeF4DE.jpg', 'waktu_upload' => '2026-06-08 20:34:51'],
            ['id' => 5, 'posisi' => 5, 'path' => 'gambar-landing/z660dARuZtozCMKX9hEyzScyUzVD0iTTOsReBB1F.jpg', 'waktu_upload' => '2026-06-10 20:34:51'],
            ['id' => 6, 'posisi' => 6, 'path' => 'gambar-landing/OOW4AFDSXh68f2za8V1UR1biw4n8WYhklWYkHcNp.jpg', 'waktu_upload' => '2026-07-01 20:34:51'],
            ['id' => 7, 'posisi' => 7, 'path' => 'gambar-landing/sCEXRkLDNLBrmNjPVE5xcCP5Lx7uNEzUROpCnyjL.jpg', 'waktu_upload' => '2026-06-13 20:34:51'],
            ['id' => 8, 'posisi' => 8, 'path' => 'gambar-landing/JkrnDMXoFzXdP4IYxDWQcVBuquMPw75Ci5CuAeqx.jpg', 'waktu_upload' => '2026-06-27 20:34:51'],
        ];

        DB::table('gambar_dashboard')->insert($gambars);
    }
}
