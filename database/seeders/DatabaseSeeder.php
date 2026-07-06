<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core System Seeders
        $this->call([
            RoleSeeder::class,
            GuestSeeder::class,
        ]);

        // Landing Page Seeders
        $this->call([
            UserSeeder::class,
            TentangSeeder::class,
            BeritaSeeder::class,
            GambarDashboardSeeder::class,
            GaleriSeeder::class,
        ]);

        // Peminjaman Sarana Seeders
        $this->call([
            // GedungSeeder::class,
            RuanganSeeder::class,
            PaketRuanganSeeder::class,
            PeminjamanTransaksiSeeder::class,
            InvoiceSeeder::class,
            SaranaSeeder::class,
            DetailPeminjamanSaranaSeeder::class,
            MediaFileSeeder::class,
            ActivityLogSeeder::class,
        ]);

        echo "\n✅ Database seeding completed successfully!\n";
    }
}
