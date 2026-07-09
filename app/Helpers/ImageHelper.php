<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Mengoptimalkan gambar yang diunggah, mengubah format ke WebP,
     * melakukan kompresi, dan meresize jika melebihi batas ukuran maksimal.
     *
     * @param UploadedFile $file File gambar yang diunggah
     * @param string $directory Direktori penyimpanan di dalam disk public (contoh: 'profile_photos')
     * @param int|null $maxWidth Lebar maksimal gambar (null jika tidak diresize)
     * @param int|null $maxHeight Tinggi maksimal gambar (null jika tidak diresize)
     * @param int $quality Kualitas kompresi WebP (0-100, default 75)
     * @return string Path relatif file yang disimpan (contoh: 'storage/profile_photos/xyz.webp')
     */
    public static function optimizeAndStore(UploadedFile $file, string $directory, ?int $maxWidth = null, ?int $maxHeight = null, int $quality = 75): string
    {
        // 1. Jika GD extension tidak terpasang, simpan secara normal dengan file aslinya
        if (!extension_loaded('gd')) {
            $path = $file->store($directory, 'public');
            return 'storage/' . $path;
        }

        // 2. Baca gambar dari file upload
        $imagePath = $file->getRealPath();
        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            // Jika bukan gambar valid, simpan secara normal
            $path = $file->store($directory, 'public');
            return 'storage/' . $path;
        }

        $mime = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Buat GD resource berdasarkan mime type
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $srcImage = @imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $srcImage = @imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $srcImage = @imagecreatefromgif($imagePath);
                break;
            case 'image/webp':
                $srcImage = @imagecreatefromwebp($imagePath);
                break;
            default:
                // Jika format tidak didukung oleh generator kami, simpan normal
                $path = $file->store($directory, 'public');
                return 'storage/' . $path;
        }

        if (!$srcImage) {
            $path = $file->store($directory, 'public');
            return 'storage/' . $path;
        }

        // 3. Resize gambar secara proporsional jika melebihi ukuran max
        $newWidth = $width;
        $newHeight = $height;

        if ($maxWidth && $width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int)($height * ($maxWidth / $width));
        }

        if ($maxHeight && $newHeight > $maxHeight) {
            $newHeight = $maxHeight;
            $newWidth = (int)($newWidth * ($maxHeight / $newHeight));
        }

        // Jika ukuran berubah, lakukan resize
        if ($newWidth !== $width || $newHeight !== $height) {
            $dstImage = imagecreatetruecolor($newWidth, $newHeight);

            // Pertahankan transparansi untuk PNG/GIF/WebP
            if ($mime === 'image/png' || $mime === 'image/gif' || $mime === 'image/webp') {
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);
                $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
                imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($srcImage);
            $finalImage = $dstImage;
        } else {
            $finalImage = $srcImage;
        }

        // 4. Buat nama file acak yang unik dengan ekstensi .webp
        $filename = Str::random(40) . '.webp';
        
        // Buat direktori jika belum ada
        $storageDir = storage_path('app/public/' . $directory);
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $outputPath = $storageDir . '/' . $filename;

        // 5. Simpan sebagai file WebP terkompresi
        imagewebp($finalImage, $outputPath, $quality);
        imagedestroy($finalImage);

        return 'storage/' . $directory . '/' . $filename;
    }
}
