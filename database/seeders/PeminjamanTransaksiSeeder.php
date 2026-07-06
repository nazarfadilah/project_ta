<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanTransaksiSeeder extends Seeder {
    public function run(): void {
        DB::table('peminjaman_transaksi')->delete();
        
        $transaksis = [];
        $baseDate = Carbon::create(2026, 1, 1);

        for ($i = 1; $i <= 100; $i++) {
            // Generate a realistic date, spread throughout 2026
            $date = $baseDate->copy()->addDays(rand(0, 150));
            $tanggal = $date->format('Y-m-d');
            $jamMulai = $tanggal . ' ' . sprintf('%02d', rand(8, 14)) . ':00:00';
            
            // Randomly pick guest (1 to 62) and package/facility (1 to 40)
            $guestId = rand(1, 62);
            $facilityId = rand(1, 40);
            
            // Determine duration based on package/facility
            // Facility/packages 1-33 are standard rooms (24 hrs)
            // Facility 34, 38 (meeting - 8 hrs), 35 (aula - 24 hrs), 36 (transit - 12 hrs), 37 (aula - 24 hrs), 39-40 (meeting - 4 hrs)
            if ($facilityId >= 39) {
                $durasi = 4;
            } elseif ($facilityId === 36) {
                $durasi = 12;
            } elseif ($facilityId === 34 || $facilityId === 38) {
                $durasi = 8;
            } else {
                $durasi = rand(1, 4); // Standard room rentals range from 1 to 4 days
            }

            // Status distribution:
            // 1 to 60: Completed (SELESAI)
            // 61 to 75: Currently Checked In (CHECK_IN)
            // 76 to 88: Future Reservation Approved (RESERVASI, APPROVED)
            // 89 to 94: Future Reservation Pending (RESERVASI, PENDING)
            // 95 to 97: Rejected Reservation (RESERVASI, REJECTED)
            // 98 to 100: Cancelled (BATAL)
            
            $statusPeminjaman = 'RESERVASI';
            $statusApproval = 'PENDING';
            $checkIn = null;
            $checkOut = null;
            $kondisiReturn = null;
            $catatanKerusakan = null;
            $estimasiDamage = null;
            $userId = null; // Approving officer
            $catatanApproval = null;
            $tanggalApproval = null;
            $biayaTambahan = 0;

            if ($i <= 60) {
                $statusPeminjaman = 'SELESAI';
                $statusApproval = 'APPROVED';
                $userId = rand(3, 5); // approved by petugas 1-3
                $catatanApproval = 'Disetujui untuk digunakan';
                $tanggalApproval = $date->copy()->subHours(rand(1, 12))->format('Y-m-d H:i:s');
                $checkIn = $tanggal . ' ' . $date->copy()->setTime(rand(14, 15), rand(0, 59))->format('H:i:s');
                
                $checkOutDate = $date->copy()->addHours($durasi);
                $checkOut = $checkOutDate->format('Y-m-d') . ' ' . $checkOutDate->copy()->setTime(rand(11, 12), rand(0, 59))->format('H:i:s');
                $kondisiReturn = 'BAIK';
                
                // 10% chance of small damage
                if ($i % 10 === 0) {
                    $kondisiReturn = 'RUSAK_RINGAN';
                    $catatanKerusakan = 'Gagang pintu longgar atau lampu putus';
                    $estimasiDamage = 150000.00;
                    $biayaTambahan = 150000.00;
                }
            } elseif ($i <= 75) {
                $statusPeminjaman = 'CHECK_IN';
                $statusApproval = 'APPROVED';
                $userId = rand(3, 5);
                $catatanApproval = 'Sesuai dengan berkas pendaftaran';
                $tanggalApproval = $date->copy()->subHours(rand(1, 12))->format('Y-m-d H:i:s');
                $checkIn = $tanggal . ' ' . $date->copy()->setTime(rand(14, 15), rand(0, 59))->format('H:i:s');
            } elseif ($i <= 88) {
                $statusPeminjaman = 'RESERVASI';
                $statusApproval = 'APPROVED';
                $userId = rand(3, 5);
                $catatanApproval = 'Reservasi disetujui, harap tunjukkan identitas saat check-in';
                $tanggalApproval = $date->copy()->subHours(rand(1, 12))->format('Y-m-d H:i:s');
            } elseif ($i <= 94) {
                $statusPeminjaman = 'RESERVASI';
                $statusApproval = 'PENDING';
            } elseif ($i <= 97) {
                $statusPeminjaman = 'RESERVASI';
                $statusApproval = 'REJECTED';
                $userId = rand(3, 5);
                $catatanApproval = 'Kapasitas ruangan tidak sesuai dengan kebutuhan jumlah peserta';
                $tanggalApproval = $date->copy()->subHours(rand(1, 12))->format('Y-m-d H:i:s');
            } else {
                $statusPeminjaman = 'BATAL';
                $statusApproval = 'APPROVED';
                $userId = rand(3, 5);
                $catatanApproval = 'Dibatalkan oleh pihak peminjam karena perubahan jadwal';
                $tanggalApproval = $date->copy()->subHours(rand(1, 12))->format('Y-m-d H:i:s');
            }

            $transaksis[] = [
                'id' => $i,
                'kodePeminjaman' => 'PJM/' . $date->format('Ymd') . '/' . sprintf('%04d', $i),
                'guestId' => $guestId,
                'facilityId' => $facilityId,
                'tanggal' => $tanggal,
                'jamMulai' => $jamMulai,
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'durasi' => $durasi,
                'statusPeminjaman' => $statusPeminjaman,
                'keterangan' => 'Peminjaman operasional untuk kegiatan ' . ($i % 3 === 0 ? 'Pelatihan' : ($i % 2 === 0 ? 'Rapat Kerja' : 'Istirahat / Penginapan')),
                'userId' => $userId,
                'statusApproval' => $statusApproval,
                'catatanApproval' => $catatanApproval,
                'tanggalApproval' => $tanggalApproval,
                'biayaTambahan' => $biayaTambahan,
                'kondisiReturn' => $kondisiReturn,
                'catatanKerusakan' => $catatanKerusakan,
                'estimasiDamage' => $estimasiDamage,
                'createdAt' => $date->copy()->subDays(rand(2, 5))->format('Y-m-d H:i:s'),
                'updatedAt' => $date->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('peminjaman_transaksi')->insert($transaksis);
    }
}