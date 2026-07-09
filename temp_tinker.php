<?php 
$bookings = \App\Models\PeminjamanTransaksi::with(['paketRuangan'])
    ->whereYear('tanggal', 2026)
    ->whereMonth('tanggal', 7)
    ->get()
    ->map(function($b) {
        return [
            'id' => $b->id,
            'kodePeminjaman' => $b->kodePeminjaman,
            'tanggal' => $b->tanggal ? $b->tanggal->format('Y-m-d') : null,
            'jamMulai' => $b->jamMulai ? $b->jamMulai->format('Y-m-d H:i:s') : null,
            'durasi' => $b->durasi,
            'statusPeminjaman' => $b->statusPeminjaman,
            'statusApproval' => $b->statusApproval,
            'ruangan_id' => $b->paketRuangan ? $b->paketRuangan->ruangan_id : null,
            'nama_paket' => $b->paketRuangan ? $b->paketRuangan->nama_paket : null
        ];
    });
echo json_encode($bookings, JSON_PRETTY_PRINT);
