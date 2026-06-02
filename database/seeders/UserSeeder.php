<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->delete();
        
        $users = [];
        $hashedPassword = Hash::make('password'); // Single hash execution for optimization

        // 1. Seed Admin (roleId = 1)
        $users[] = [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@asrama.local',
            'password' => $hashedPassword,
            'roleId' => 1,
            'phone' => '081122334455',
            'guestId' => null,
            'status' => 'ACTIVE',
            'lastLoginAt' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 2. Seed Pimpinan (roleId = 2)
        $users[] = [
            'id' => 2,
            'username' => 'pimpinan',
            'email' => 'pimpinan@asrama.local',
            'password' => $hashedPassword,
            'roleId' => 2,
            'phone' => '081234567890',
            'guestId' => null,
            'status' => 'ACTIVE',
            'lastLoginAt' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3. Seed 3 Petugas (roleId = 3)
        for ($i = 1; $i <= 3; $i++) {
            $id = 2 + $i;
            $users[] = [
                'id' => $id,
                'username' => 'petugas' . $i,
                'email' => "petugas{$i}@asrama.local",
                'password' => $hashedPassword,
                'roleId' => 3,
                'phone' => '08311122233' . $i,
                'guestId' => null,
                'status' => 'ACTIVE',
                'lastLoginAt' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 4. Seed 62 Tamu (roleId = 4)
        for ($i = 1; $i <= 62; $i++) {
            $id = 5 + $i;
            $users[] = [
                'id' => $id,
                'username' => 'tamu' . $i,
                'email' => "tamu{$i}@email.com",
                'password' => $hashedPassword,
                'roleId' => 4,
                'phone' => '0859' . sprintf('%08d', rand(10000000, 99999999)),
                'guestId' => $i, // Mapped perfectly 1-to-1 with Guest IDs 1 to 62
                'status' => 'ACTIVE',
                'lastLoginAt' => now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
