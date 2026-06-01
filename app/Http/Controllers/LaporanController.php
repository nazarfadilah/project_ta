<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Ruangan;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // 1. Get Room and Building data for filters
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        $gedungs = Gedung::orderBy('nama_gedung')->get();

        // 2. Fetch all transaction data with relationships for DataTables
        $peminjaman = PeminjamanTransaksi::with(['guest', 'paketRuangan.ruangan.gedung', 'user'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // 3. Generate rolling 12 months for chart data
        $chartLabels = [];
        $chartData = [];
        $monthFilterList = []; // For the month filter dropdown

        // Set locale to Indonesian for month names
        Carbon::setLocale('id');

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            // E.g. "Januari 2026"
            $monthLabel = $date->translatedFormat('F Y');
            
            $chartLabels[$monthKey] = $monthLabel;
            $chartData[$monthKey] = 0;
            $monthFilterList[$monthKey] = $monthLabel;
        }

        // Query database to group bookings by month
        $bookingsByMonth = PeminjamanTransaksi::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as month_key, COUNT(*) as count")
            ->whereBetween('tanggal', [
                Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ])
            ->groupBy('month_key')
            ->pluck('count', 'month_key')
            ->toArray();

        foreach ($bookingsByMonth as $key => $count) {
            if (isset($chartData[$key])) {
                $chartData[$key] = $count;
            }
        }

        // Prepare simple KPI values for view
        $totalPeminjaman = PeminjamanTransaksi::count();
        $totalApproved = PeminjamanTransaksi::where('statusApproval', 'APPROVED')->count();
        $totalPending = PeminjamanTransaksi::where('statusApproval', 'PENDING')->count();
        
        // Sum of all income (harga + biayaTambahan where APPROVED)
        $totalPendapatan = PeminjamanTransaksi::where('statusApproval', 'APPROVED')
            ->join('paket_ruangan', 'peminjaman_transaksi.facilityId', '=', 'paket_ruangan.id')
            ->sum(DB::raw('paket_ruangan.harga + peminjaman_transaksi.biayaTambahan'));

        // Format labels and data as simple sequential arrays for JS consumption
        $formattedLabels = array_values($chartLabels);
        $formattedData = array_values($chartData);

        return view('main.laporan.index', compact(
            'ruangans',
            'gedungs',
            'peminjaman',
            'formattedLabels',
            'formattedData',
            'monthFilterList',
            'totalPeminjaman',
            'totalApproved',
            'totalPending',
            'totalPendapatan'
        ));
    }

    public function export(Request $request)
    {
        $query = PeminjamanTransaksi::with(['guest', 'paketRuangan.ruangan.gedung', 'user']);

        // Filter based on Building (Gedung)
        if ($request->filled('gedung_id')) {
            $query->whereHas('paketRuangan.ruangan', function ($q) use ($request) {
                $q->where('gedung_id', $request->gedung_id);
            });
        }

        // Filter based on Room (Ruangan)
        if ($request->filled('ruangan_id')) {
            $query->whereHas('paketRuangan', function ($q) use ($request) {
                $q->where('ruangan_id', $request->ruangan_id);
            });
        }

        // Filter based on Month
        if ($request->filled('bulan')) {
            $date = Carbon::parse($request->bulan);
            $query->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month);
        }

        // Filter based on Date Range
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        // Check if there's any data
        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data peminjaman yang cocok dengan filter yang dipilih.');
        }

        $filename = "Laporan_Peminjaman_" . Carbon::now()->format('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Microsoft Excel compliance
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header fields
            fputcsv($file, [
                'No',
                'Kode Peminjaman',
                'Nama Tamu (Guest)',
                'Gedung',
                'Ruangan',
                'Tanggal Peminjaman',
                'Jam Mulai',
                'Durasi (Jam)',
                'Biaya Tambahan (IDR)',
                'Status Peminjaman',
                'Status Approval',
                'Keterangan'
            ]);

            foreach ($data as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->kodePeminjaman,
                    $row->guest->name ?? 'N/A',
                    $row->paketRuangan->ruangan->gedung->nama_gedung ?? 'N/A',
                    $row->paketRuangan->ruangan->nama_ruangan ?? 'N/A',
                    Carbon::parse($row->tanggal)->format('d-m-Y'),
                    Carbon::parse($row->jamMulai)->format('H:i'),
                    $row->durasi,
                    number_format($row->biayaTambahan, 2, ',', '.'),
                    $row->statusPeminjaman,
                    $row->statusApproval,
                    $row->keterangan ?? '-'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
