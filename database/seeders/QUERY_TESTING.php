<?php

namespace Database\Seeders;

/**
 * Query Testing untuk Database SIPRASA
 * File ini berisi query untuk testing dan verifikasi data
 * 
 * Jalankan seeders dengan: php artisan db:seed
 * Atau reset database: php artisan migrate:fresh --seed
 */

/**
 * QUERY TESTING - Copy paste ke Tinker atau Database Manager
 */

// ===============================================
// 1. QUERY ADMIN
// ===============================================

// Lihat semua admin
// SELECT * FROM admin;

// Lihat admin dengan profil
// SELECT a.*, p.* FROM admin a 
// LEFT JOIN profil p ON a.email_admin = p.email_admin;

// Lihat petugas dengan gedung
// SELECT a.*, g.nama FROM admin a 
// LEFT JOIN gedung g ON a.gedung_id = g.id 
// WHERE a.role = 'petugas';

// ===============================================
// 2. QUERY USERS
// ===============================================

// Lihat semua users
// SELECT * FROM users;

// Lihat users dengan profil
// SELECT u.*, p.* FROM users u 
// LEFT JOIN profil p ON u.email_users = p.email_users;

// Count users dengan profil vs tanpa profil
// SELECT 
//   (SELECT COUNT(*) FROM profil WHERE email_users IS NOT NULL) AS users_dengan_profil,
//   (SELECT COUNT(*) FROM users WHERE email_users NOT IN (SELECT email_users FROM profil WHERE email_users IS NOT NULL)) AS users_tanpa_profil;

// ===============================================
// 3. QUERY GEDUNG & RUANGAN
// ===============================================

// Lihat gedung beserta petugas yang ditugaskan
// SELECT g.*, a.name_admin, a.email_admin FROM gedung g 
// LEFT JOIN admin a ON a.gedung_id = g.id 
// WHERE a.role = 'petugas';

// Lihat ruangan per gedung
// SELECT g.nama as gedung, r.* FROM ruangan r 
// JOIN gedung g ON r.gedung_id = g.id 
// ORDER BY g.id, r.nama_ruangan;

// Count ruangan per gedung
// SELECT g.nama, COUNT(*) as jumlah_ruangan FROM gedung g 
// LEFT JOIN ruangan r ON r.gedung_id = g.id 
// GROUP BY g.id;

// ===============================================
// 4. QUERY SARANA
// ===============================================

// Lihat semua sarana
// SELECT * FROM sarana;

// Total stok per sarana
// SELECT nama, SUM(stok) as total_stok FROM sarana GROUP BY nama;

// Sarana berdasarkan kondisi
// SELECT kondisi, COUNT(*) as jumlah FROM sarana GROUP BY kondisi;

// ===============================================
// 5. QUERY PEMINJAMAN
// ===============================================

// Lihat semua peminjaman
// SELECT * FROM peminjaman_transaksi;

// Peminjaman dengan detail user
// SELECT pt.*, u.name_users, r.nama_ruangan, g.nama as gedung 
// FROM peminjaman_transaksi pt
// JOIN users u ON pt.email_users = u.email_users
// JOIN ruangan r ON pt.ruangan_id = r.id
// JOIN gedung g ON r.gedung_id = g.id;

// Peminjaman yang disetujui
// SELECT * FROM peminjaman_transaksi WHERE status_peminjaman = 'Disetujui';

// Detail peminjaman per transaksi
// SELECT pt.id, pt.email_users, u.name_users, 
//   GROUP_CONCAT(s.nama SEPARATOR ', ') as sarana,
//   SUM(dps.jumlah) as total_jumlah
// FROM peminjaman_transaksi pt
// JOIN users u ON pt.email_users = u.email_users
// LEFT JOIN detail_peminjaman_sarana dps ON pt.id = dps.peminjaman_id
// LEFT JOIN sarana s ON dps.sarana_id = s.id
// GROUP BY pt.id;

// ===============================================
// 6. QUERY BERITA
// ===============================================

// Lihat semua berita
// SELECT * FROM berita;

// Berita yang di-approve
// SELECT * FROM berita WHERE status = 'approved';

// Berita per admin
// SELECT b.*, a.name_admin FROM berita b 
// JOIN admin a ON b.email_admin = a.email_admin;

// ===============================================
// 7. QUERY GAMBAR DASHBOARD
// ===============================================

// Lihat gambar dashboard
// SELECT * FROM gambar_dashboard;

// Gambar carousel vs statis
// SELECT posisi, COUNT(*) FROM gambar_dashboard GROUP BY posisi;

// ===============================================
// 8. QUERY COMPLEX - LAPORAN
// ===============================================

// Laporan peminjaman per user
// SELECT 
//   u.email_users, 
//   u.name_users,
//   COUNT(pt.id) as total_peminjaman,
//   COUNT(CASE WHEN pt.status_peminjaman = 'Disetujui' THEN 1 END) as disetujui,
//   COUNT(CASE WHEN pt.status_peminjaman = 'Ditolak' THEN 1 END) as ditolak
// FROM users u
// LEFT JOIN peminjaman_transaksi pt ON u.email_users = pt.email_users
// GROUP BY u.email_users;

// Laporan penggunaan sarana paling sering dipinjam
// SELECT 
//   s.nama,
//   COUNT(dps.sarana_id) as frekuensi_peminjaman,
//   SUM(dps.jumlah) as total_unit_dipinjam
// FROM sarana s
// LEFT JOIN detail_peminjaman_sarana dps ON s.id = dps.sarana_id
// GROUP BY s.id
// ORDER BY frekuensi_peminjaman DESC;

// Laporan sarana yang tidak pernah dipinjam
// SELECT * FROM sarana WHERE id NOT IN (SELECT DISTINCT sarana_id FROM detail_peminjaman_sarana);

// ===============================================
// 9. AGGREGATE STATISTICS
// ===============================================

// Total data di sistem
// SELECT 
//   (SELECT COUNT(*) FROM admin) as total_admin,
//   (SELECT COUNT(*) FROM users) as total_users,
//   (SELECT COUNT(*) FROM gedung) as total_gedung,
//   (SELECT COUNT(*) FROM ruangan) as total_ruangan,
//   (SELECT COUNT(*) FROM sarana) as total_sarana,
//   (SELECT COUNT(*) FROM peminjaman_transaksi) as total_peminjaman,
//   (SELECT COUNT(*) FROM berita) as total_berita,
//   (SELECT COUNT(*) FROM gambar_dashboard) as total_gambar;

// ===============================================
// TESTING DENGAN TINKER
// ===============================================

/*
Jalankan: php artisan tinker

// Import model
use App\Models\User;
use App\Models\Admin;
use App\Models\Profil;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Sarana;
use App\Models\PeminjamanTransaksi;
use App\Models\Berita;

// Query dengan Eloquent
Admin::where('role', 'petugas')->get();
User::with('profil')->get();
Gedung::with('runangans')->get();
Gedung::with('admins')->where('role', 'petugas')->get();
PeminjamanTransaksi::with(['user', 'ruangan', 'admin'])->get();
Berita::where('status', 'approved')->get();

// Count
User::count();
Admin::count();
Sarana::count();
PeminjamanTransaksi::count();

// Group by
Sarana::groupBy('kondisi')->selectRaw('kondisi, count(*) as total')->get();

*/
