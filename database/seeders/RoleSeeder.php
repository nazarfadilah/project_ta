<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder {
    public function run(): void {
        DB::table('role')->insert([
            ['id' => 1, 'name' => 'Admin', 'description' => 'Full system access', 'permissions' => json_encode(['*'])],
            ['id' => 2, 'name' => 'Pimpinan', 'description' => 'View reports, analytics, approve requests', 'permissions' => json_encode(['report.view', 'analytics.view', 'verification.approve'])],
            ['id' => 3, 'name' => 'Petugas', 'description' => 'Check-in/out, verification, data entry', 'permissions' => json_encode(['checkin.manage', 'checkout.manage', 'verification.manage'])],
            ['id' => 4, 'name' => 'Tamu', 'description' => 'Browse facilities, make booking, view status', 'permissions' => json_encode(['booking.make', 'booking.cancel', 'status.view'])],
        ]);
    }
}