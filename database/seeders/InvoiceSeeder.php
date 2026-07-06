<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder {
    public function run(): void {
        DB::table('invoice')->delete();
        
        // Fetch all generated transactions
        $transaksis = DB::table('peminjaman_transaksi')->get();
        $invoices = [];

        foreach ($transaksis as $transaksi) {
            // Fetch corresponding room package to get base price
            $paket = DB::table('paket_ruangan')->where('id', $transaksi->facilityId)->first();
            
            // Default pricing fallbacks if not found
            $baseHarga = $paket ? $paket->harga : 150000.00;
            $paketDurasi = $paket ? $paket->durasi : 24;

            // Calculate subtotal based on duration multiplier for daily rooms
            if ($paketDurasi === 24) {
                $multiplier = $transaksi->durasi;
                $subtotal = $baseHarga * $multiplier;
            } else {
                $subtotal = $baseHarga;
            }

            $biayaTambahan = $transaksi->biayaTambahan;
            $totalHarga = $subtotal + $biayaTambahan;

            // Align payment status with transaction status
            if ($transaksi->statusPeminjaman === 'SELESAI') {
                $statusInvoice = 'PAID';
                $status_pembayaran = 'LUNAS';
                $tglInvoice = Carbon::parse($transaksi->tanggalApproval ?? $transaksi->createdAt);
                $tgl_pembayaran = $tglInvoice->copy()->addMinutes(rand(10, 120))->format('Y-m-d H:i:s');
                $tglPaid = $tgl_pembayaran;
            } elseif ($transaksi->statusPeminjaman === 'CHECK_IN') {
                // 70% paid, 30% partial
                if ($transaksi->id % 3 === 0) {
                    $statusInvoice = 'PARTIAL';
                    $status_pembayaran = 'SEBAGIAN';
                    $tglInvoice = Carbon::parse($transaksi->tanggalApproval ?? $transaksi->createdAt);
                    $tgl_pembayaran = $tglInvoice->copy()->addMinutes(rand(10, 120))->format('Y-m-d H:i:s');
                    $tglPaid = null;
                } else {
                    $statusInvoice = 'PAID';
                    $status_pembayaran = 'LUNAS';
                    $tglInvoice = Carbon::parse($transaksi->tanggalApproval ?? $transaksi->createdAt);
                    $tgl_pembayaran = $tglInvoice->copy()->addMinutes(rand(10, 120))->format('Y-m-d H:i:s');
                    $tglPaid = $tgl_pembayaran;
                }
            } else {
                // RESERVASI or BATAL
                $statusInvoice = 'UNPAID';
                $status_pembayaran = 'BELUM_BAYAR';
                $tglInvoice = Carbon::parse($transaksi->createdAt);
                $tgl_pembayaran = null;
                $tglPaid = null;
            }

            $tglDueDate = $tglInvoice->copy()->addDays(7)->format('Y-m-d H:i:s');

            $invoices[] = [
                'id' => $transaksi->id,
                'noInvoice' => 'INV/' . Carbon::parse($transaksi->createdAt)->format('Ymd') . '/' . sprintf('%04d', $transaksi->id),
                'peminjamanId' => $transaksi->id,
                'subtotal' => $subtotal,
                'biayaTambahan' => $biayaTambahan,
                'totalHarga' => $totalHarga,
                'statusInvoice' => $statusInvoice,
                'status_pembayaran' => $status_pembayaran,
                'tglInvoice' => $tglInvoice->format('Y-m-d H:i:s'),
                'tglDueDate' => $tglDueDate,
                'tgl_pembayaran' => $tgl_pembayaran,
                'tglPaid' => $tglPaid,
                'notes' => 'Invoice pembayaran resmi fasilitas asrama haji SIPRASA.',
                'createdAt' => $transaksi->createdAt,
                'updatedAt' => $transaksi->updatedAt,
            ];
        }

        DB::table('invoice')->insert($invoices);
    }
}