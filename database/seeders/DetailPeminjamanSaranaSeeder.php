<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPeminjamanSaranaSeeder extends Seeder {
    public function run(): void {
        DB::table('detail_peminjaman_sarana')->delete();
        
        $details = [];
        $counter = 1;

        // Seed equipment loans for transactions where ID is even and <= 80
        for ($i = 2; $i <= 80; $i += 2) {
            // Transaction $i borrows 1-2 types of equipment
            
            // 1. Borrow Chairs (sarana_id = 1) - Quantity: 10 to 100
            $details[] = [
                'id' => $counter++,
                'sarana_id' => 1,
                'peminjaman_id' => $i,
                'jumlah' => (string)rand(10, 80),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 2. Borrow Table or Microphone (sarana_id = 3 or 4) - Quantity: 2 to 10
            if ($i % 4 === 0) {
                $details[] = [
                    'id' => $counter++,
                    'sarana_id' => rand(3, 4),
                    'peminjaman_id' => $i,
                    'jumlah' => (string)rand(2, 8),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 3. Borrow Projector (sarana_id = 2) - Quantity: 1
            if ($i % 6 === 0) {
                $details[] = [
                    'id' => $counter++,
                    'sarana_id' => 2,
                    'peminjaman_id' => $i,
                    'jumlah' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('detail_peminjaman_sarana')->insert($details);
    }
}