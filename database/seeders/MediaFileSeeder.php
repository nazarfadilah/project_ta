<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaFileSeeder extends Seeder {
    public function run(): void {
        DB::table('media_file')->insert([
            ['id' => 1, 'ruangan_id' => 1, 'path' => '/uploads/ruangan/101/foto-kamar-standar-1.jpg'],
            ['id' => 2, 'ruangan_id' => 2, 'path' => '/uploads/aula/foto-aula-besar-1.jpg'],
            ['id' => 3, 'ruangan_id' => 3, 'path' => '/uploads/meeting/foto-ruang-meeting-1.jpg'],
        ]);
    }
}