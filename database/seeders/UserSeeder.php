<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert([
            ['id' => 1, 'username' => 'budi.petugas', 'email' => 'budi@asrama.local', 'password' => '$2b$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36EZkev6', 'roleId' => 2, 'phone' => '082123456789', 'guestId' => null, 'status' => 'ACTIVE', 'lastLoginAt' => '2026-04-20 23:53:03'],
            ['id' => 2, 'username' => 'siti.pimpinan', 'email' => 'siti@asrama.local', 'password' => '$2b$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36EZkev6', 'roleId' => 3, 'phone' => '081234567891', 'guestId' => null, 'status' => 'ACTIVE', 'lastLoginAt' => '2026-04-19 06:30:00'],
            ['id' => 3, 'username' => 'ahmad.suryanto', 'email' => 'ahmad.suryanto@email.com', 'password' => '$2b$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36EZkev6', 'roleId' => 4, 'phone' => '085987654321', 'guestId' => 1, 'status' => 'ACTIVE', 'lastLoginAt' => '2026-04-18 02:15:00'],
        ]);
    }
}
