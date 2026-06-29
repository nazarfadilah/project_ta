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
        $gambars = [];
        
        // Generate 8 gambar dashboard untuk hero section dan carousel
        for ($i = 1; $i <= 8; $i++) {
            $gambars[] = [
                'posisi' => $i,
                'path' => 'https://placehold.co/1920x600?text=Dashboard+Image+' . $i,
                'waktu_upload' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('gambar_dashboard')->insert($gambars);
    }
}
