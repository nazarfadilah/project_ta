<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Ruangan;
// use App\Models\Gedung;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    public function index()
    {
        // 1. Get Room and Building data for filters
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        // Code Lama:
        // $gedungs = Gedung::orderBy('nama_gedung')->get();
        // Code Baru:
        $gedungs = collect();

        // 2. Fetch all transaction data with relationships for DataTables
        // Code Lama:
        // $peminjaman = PeminjamanTransaksi::with(['guest', 'paketRuangan.ruangan.gedung', 'user'])
        // Code Baru:
        $peminjaman = PeminjamanTransaksi::with(['guest', 'paketRuangan.ruangan', 'user'])
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
        $query = PeminjamanTransaksi::with(['guest.user', 'paketRuangan.ruangan', 'user']);

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

        // UPT name default or from tentang table key: nama_instansi
        $namaInstansi = \App\Models\Tentang::where('key', 'nama_instansi')->first()?->value ?? 'UPT Asrama Haji Embarkasi Banjarmasin';

        // Initialize Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Document Properties
        $spreadsheet->getProperties()
            ->setCreator('SIPRASA')
            ->setLastModifiedBy('SIPRASA')
            ->setTitle('Data Pesanan')
            ->setSubject('Laporan SPI');

        // Title rows matching Data Pesanan.xlsx template
        $sheet->setCellValue('A1', 'DATA RESPONDEN EKSTERNAL');
        $sheet->setCellValue('A2', 'KEGIATAN SURVEY PENILAIAN INTEGRITAS (SPI) TAHUN ' . Carbon::now()->year);
        $sheet->setCellValue('A3', 'PADA ' . strtoupper($namaInstansi));

        // Styling the titles (bold)
        $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(11);

        // Header fields
        $headers = [
            'No',
            'Unit Tempat Mengurus Layanan Publik',
            'Nama Layanan yang Diterima',
            'Tanggal/Bulan/Tahun Mengurus Layanan',
            'Nama Pengguna Layanan',
            'PIC',
            'Nomor Telepon',
            'E-mail (Aktif)'
        ];

        // Fill headers in row 5
        foreach ($headers as $colIdx => $headerText) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
            $cellCoord = $colLetter . '5';
            $sheet->setCellValue($cellCoord, $headerText);
            
            // Set header styling: bold, light gray background, thin borders
            $sheet->getStyle($cellCoord)->getFont()->setBold(true);
            $sheet->getStyle($cellCoord)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            if ($colIdx === 0) {
                $sheet->getStyle($cellCoord)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        // Add thin border to headers and style them nicely
        $headerRange = 'A5:H5';
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF2F2F2');
        $sheet->getRowDimension(5)->setRowHeight(25);

        // Fill data starting at row 6
        $rowNum = 6;
        foreach ($data as $index => $row) {
            $sheet->setCellValue('A' . $rowNum, $index + 1);
            $sheet->setCellValue('B' . $rowNum, $namaInstansi);
            $sheet->setCellValue('C' . $rowNum, $row->paketRuangan->ruangan->nama_ruangan ?? '-');

            // Format Tanggal: d/m/Y - d/m/Y or d/m/Y depending on duration
            $start = Carbon::parse($row->jamMulai);
            $isHarian = ($row->paketRuangan && (stripos($row->paketRuangan->nama_paket, 'hari') !== false || stripos($row->paketRuangan->nama_paket, 'harian') !== false));
            if ($isHarian && $row->durasi > 1) {
                $end = $start->copy()->addDays($row->durasi - 1);
                $tanggalStr = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
            } elseif ($isHarian && $row->durasi == 1) {
                $tanggalStr = $start->format('d/m/Y');
            } else {
                $tanggalStr = $start->format('d/m/Y');
            }
            $sheet->setCellValue('D' . $rowNum, $tanggalStr);

            // Nama Pengguna Layanan (instansi guest)
            $sheet->setCellValue('E' . $rowNum, $row->guest->instansi ?? '-');
            // PIC
            $sheet->setCellValue('F' . $rowNum, $row->guest->name ?? '-');
            // Nomor telepon
            $sheet->setCellValue('G' . $rowNum, $row->guest->phone ?? '-');
            // E-mail (Aktif)
            $sheet->setCellValue('H' . $rowNum, $row->guest->user->email ?? ($row->user->email ?? '-'));

            // Apply borders and alignment to current row
            $rowRange = 'A' . $rowNum . ':H' . $rowNum;
            $sheet->getStyle($rowRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $rowNum++;
        }

        // Auto size columns for readability
        foreach (range(1, 8) as $colIdx) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Set response headers to download .xlsx with filename "Data Pesanan.xlsx"
        $filename = "Data Pesanan.xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
