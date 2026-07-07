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

        $specificBookings = [
            [
                'kodePeminjaman' => 'PJM/20260619/0001',
                'guestId' => 1, // Ibu Niswati (LPTQ PROV. Kalsel)
                'facilityId' => 1, // Aula Jabal Rahmah
                'tanggal' => '2026-06-19',
                'jamMulai' => '2026-06-19 08:00:00',
                'checkIn' => '2026-06-19 14:00:00',
                'checkOut' => '2026-06-22 12:00:00',
                'durasi' => 4,
                'statusPeminjaman' => 'SELESAI',
                'statusApproval' => 'APPROVED',
                'userId' => 3,
                'catatanApproval' => 'Sesuai agenda LPTQ',
                'tanggalApproval' => '2026-06-18 09:00:00',
                'keterangan' => 'Kegiatan MTQ Provinsi',
            ],
            [
                'kodePeminjaman' => 'PJM/20260619/0002',
                'guestId' => 3, // Bpk Haris Fadillah (LPTQ PROV. Kalsel)
                'facilityId' => 1, // Aula Jabal Rahmah
                'tanggal' => '2026-06-19',
                'jamMulai' => '2026-06-19 08:00:00',
                'checkIn' => '2026-06-19 14:00:00',
                'checkOut' => '2026-06-22 12:00:00',
                'durasi' => 4,
                'statusPeminjaman' => 'SELESAI',
                'statusApproval' => 'APPROVED',
                'userId' => 3,
                'catatanApproval' => 'Sesuai agenda LPTQ',
                'tanggalApproval' => '2026-06-18 09:10:00',
                'keterangan' => 'Kegiatan Evaluasi LPTQ',
            ],
            [
                'kodePeminjaman' => 'PJM/20260726/0001',
                'guestId' => 2, // Bpk Ahmad Maki (Kanwil Kemenag Prov. Kalteng)
                'facilityId' => 3, // Aula Mekkah
                'tanggal' => '2026-07-26',
                'jamMulai' => '2026-07-26 08:00:00',
                'checkIn' => '2026-07-26 14:30:00',
                'checkOut' => '2026-07-28 12:00:00',
                'durasi' => 3,
                'statusPeminjaman' => 'SELESAI',
                'statusApproval' => 'APPROVED',
                'userId' => 4,
                'catatanApproval' => 'Disetujui',
                'tanggalApproval' => '2026-07-25 10:00:00',
                'keterangan' => 'Bimtek Penmad',
            ],
            [
                'kodePeminjaman' => 'PJM/20260809/0001',
                'guestId' => 4, // Bpk Bahar (Travel Mahabbah)
                'facilityId' => 10, // Ruang Belajar
                'tanggal' => '2026-08-09',
                'jamMulai' => '2026-08-09 09:00:00',
                'checkIn' => '2026-08-09 09:00:00',
                'checkOut' => '2026-08-10 17:00:00',
                'durasi' => 2,
                'statusPeminjaman' => 'SELESAI',
                'statusApproval' => 'APPROVED',
                'userId' => 4,
                'catatanApproval' => 'Disetujui',
                'tanggalApproval' => '2026-08-08 14:00:00',
                'keterangan' => 'Rapat Koordinasi Travel Haji',
            ],
            [
                'kodePeminjaman' => 'PJM/20260812/0001',
                'guestId' => 5, // Ibu Chrisna (CV. Rpy Production)
                'facilityId' => 11, // Ruang Belajar 3
                'tanggal' => '2026-08-12',
                'jamMulai' => '2026-08-12 08:00:00',
                'checkIn' => '2026-08-12 08:30:00',
                'checkOut' => '2026-08-14 16:00:00',
                'durasi' => 3,
                'statusPeminjaman' => 'SELESAI',
                'statusApproval' => 'APPROVED',
                'userId' => 5,
                'catatanApproval' => 'OK',
                'tanggalApproval' => '2026-08-11 15:00:00',
                'keterangan' => 'Pelatihan Rpy Crew',
            ],
            [
                'kodePeminjaman' => 'PJM/20260821/0001',
                'guestId' => 6, // Ibu Munisah (UIN Antasari Banjarmasin)
                'facilityId' => 3, // Aula Mekkah
                'tanggal' => '2026-08-21',
                'jamMulai' => '2026-08-21 08:00:00',
                'checkIn' => '2026-08-21 08:00:00',
                'checkOut' => '2026-08-23 18:00:00',
                'durasi' => 3,
                'statusPeminjaman' => 'SELESAI',
                'statusApproval' => 'APPROVED',
                'userId' => 3,
                'catatanApproval' => 'Rekomendasi Rektorat',
                'tanggalApproval' => '2026-08-20 10:00:00',
                'keterangan' => 'LDK Mahasiswa',
            ],
            [
                'kodePeminjaman' => 'PJM/20260822/0001',
                'guestId' => 7, // Bpk Lutfi Hakim (Bpk Lutfi Hakim/ Saiun)
                'facilityId' => 14, // Area Manasik
                'tanggal' => '2026-08-22',
                'jamMulai' => '2026-08-22 08:00:00',
                'checkIn' => '2026-08-22 08:00:00',
                'checkOut' => '2026-08-23 20:00:00',
                'durasi' => 2,
                'statusPeminjaman' => 'SELESAI',
                'statusApproval' => 'APPROVED',
                'userId' => 5,
                'catatanApproval' => 'Disetujui',
                'tanggalApproval' => '2026-08-21 16:00:00',
                'keterangan' => 'Transit Tokoh Agama',
            ]
        ];

        foreach ($specificBookings as $idx => $sb) {
            $transaksis[] = [
                'id' => $idx + 1,
                'kodePeminjaman' => $sb['kodePeminjaman'],
                'guestId' => $sb['guestId'],
                'facilityId' => $sb['facilityId'],
                'tanggal' => $sb['tanggal'],
                'jamMulai' => $sb['jamMulai'],
                'checkIn' => $sb['checkIn'],
                'checkOut' => $sb['checkOut'],
                'durasi' => $sb['durasi'],
                'statusPeminjaman' => $sb['statusPeminjaman'],
                'keterangan' => $sb['keterangan'],
                'userId' => $sb['userId'],
                'statusApproval' => $sb['statusApproval'],
                'catatanApproval' => $sb['catatanApproval'],
                'tanggalApproval' => $sb['tanggalApproval'],
                'biayaTambahan' => 0,
                'kondisiReturn' => 'BAIK',
                'catatanKerusakan' => null,
                'estimasiDamage' => null,
                'createdAt' => Carbon::parse($sb['tanggal'])->subDays(5)->format('Y-m-d H:i:s'),
                'updatedAt' => Carbon::parse($sb['tanggal'])->format('Y-m-d H:i:s'),
            ];
        }

        for ($i = 8; $i <= 100; $i++) {
            // Generate a realistic date, spread throughout 2026
            $date = $baseDate->copy()->addDays(rand(0, 150));
            $tanggal = $date->format('Y-m-d');
            $jamMulai = $tanggal . ' ' . sprintf('%02d', rand(8, 14)) . ':00:00';
            
            // Randomly pick guest (1 to 62) and package/facility (1 to 15)
            $guestId = rand(1, 62);
            $facilityId = rand(1, 15);
            
            // All packages are daily (24 hrs)
            $durasi = rand(1, 4);

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