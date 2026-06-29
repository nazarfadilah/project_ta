<?php
 
namespace App\Http\Controllers;
 
 use Illuminate\Support\Facades\Storage;
 use Symfony\Component\HttpFoundation\BinaryFileResponse;
 
 class StorageController extends Controller
 {
     /**
      * Serve a file securely from storage/app/public/
      *
      * @param string $folder
      * @param string $filename
      * @return BinaryFileResponse
      */
     public function serveFile(string $folder, string $filename)
     {
         // Safe characters check to prevent directory traversal
         if (preg_match('/\.\./', $folder) || preg_match('/\.\./', $filename)) {
             abort(403, 'Akses ditolak.');
         }
 
         $path = storage_path('app/public/' . $folder . '/' . $filename);
 
         if (!file_exists($path)) {
             // Cek apakah file yang diminta merupakan gambar berdasarkan ekstensinya
             $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
             if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                 // Kembalikan file icon default jika file yang dicari di storage tidak ada
                 $fallbackPath = public_path('assets/image/icon.png');
                 if (file_exists($fallbackPath)) {
                     return response()->file($fallbackPath);
                 }
             }
             abort(404, 'File tidak ditemukan.');
         }
 
         return response()->file($path);
     }
 }
