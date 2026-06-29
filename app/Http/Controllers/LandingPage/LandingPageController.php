<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;

use App\Models\Berita;
use App\Models\GambarDashboard;
use App\Models\Galeri;
use App\Models\Tentang;

class LandingPageController extends Controller
{
    public function beranda()
    {
        return view('public.main.beranda', $this->landingData());
    }

    public function fasilitas()
    {
        return view('public.main.fasilitas', $this->landingData());
    }

    public function galeri()
    {
        $data = $this->landingData();
        $data['galeriItems'] = \App\Models\Galeri::latest()->get();
        $data['kategoriGaleri'] = \App\Models\Galeri::select('kategori')->whereNotNull('kategori')->distinct()->get()->pluck('kategori')->unique();
        return view('public.main.galeri', $data);
    }

    public function reservasi()
    {
        return view('public.main.reservasi', $this->landingData());
    }

    public function tentang()
    {
        return view('public.main.tentang', $this->landingData());
    }

    public function faq()
    {
        return view('public.main.faq', $this->landingData());
    }

    public function privacy()
    {
        return view('public.main.kebijkan&privasi', $this->landingData());
    }

    public function terms()
    {
        return view('public.main.syarat&ketentuan', $this->landingData());
    }

    public function kontakKami()
    {
        return view('public.main.kontak_kami', $this->landingData());
    }

    private function landingData(): array
    {
        $tentangRows = Tentang::query()->get(['key', 'key2', 'value']);

        $settings = $tentangRows
            ->filter(function($row) {
                return $row->key2 === null || $row->key2 === '';
            })
            ->pluck('value', 'key')
            ->toArray();

        $faqItems = $tentangRows
            ->where('key', 'faq')
            ->values()
            ->map(function ($item) {
                return [
                    'question' => $item->key2,
                    'answer' => $item->value,
                ];
            });

        $termConditions = $tentangRows
            ->where('key', 'term&conditions')
            ->values()
            ->map(function ($item) {
                return [
                    'title' => $item->key2,
                    'content' => $item->value
                ];
            });

        $privacyItems = $tentangRows
            ->where('key', 'kebijakan_privasi')
            ->values()
            ->map(function ($item) {
                return [
                    'title' => $item->key2,
                    'content' => $item->value
                ];
            });

        $contact = [
            'nama_instansi' => $settings['nama_instansi'] ?? '',
            'alamat' => $settings['alamat'] ?? '',
            'no_hp' => $settings['no_hp'] ?? '',
            'email' => $settings['email'] ?? '',
            'kantor' => $settings['kantor'] ?? '',
            'jam_mulai' => $settings['jam_mulai'] ?? '',
            'jam_akhir' => $settings['jam_akhir'] ?? '',
            'jam_sabtu' => $settings['jam_sabtu'] ?? '',
            'tentang' => $settings['tentang'] ?? '',
        ];

        $brandLogo = $this->resolveImage($settings['logo'] ?? null, 'logo');
        
        $mapsUrl = $settings['link_google_maps'] ?? '';
        if ($mapsUrl && ! str_contains($mapsUrl, 'output=embed')) {
            $mapsUrl .= (str_contains($mapsUrl, '?') ? '&' : '?') . 'output=embed';
        }
        
        $whatsappNumber = preg_replace('/\D+/', '', $contact['no_hp'] ?? '');
        $whatsappUrl = $settings['whatsapp'] ?? ($whatsappNumber ? 'https://wa.me/' . $whatsappNumber : '');

        $heroSlides = GambarDashboard::query()
            ->orderBy('posisi')
            ->get()
            ->map(function ($slide) {
                $slide->resolved_path = $this->resolveImage($slide->path, 'slide', $slide->posisi);
                return $slide;
            });

        $galleryItems = Galeri::query()
            ->orderByDesc('created_at')
            ->get()
            ->values()
            ->map(function ($item, $index) {
                $item->resolved_image = $this->resolveImage($item->gambar, 'galeri', $index);
                return $item;
            });

        $beritas = Berita::query()
            ->where('status', 'approved')
            ->orderByDesc('tanggal_publish')
            ->get()
            ->values()
            ->map(function ($berita, $index) {
                $berita->resolved_image = $this->resolveImage($berita->gambar, 'berita', $index);
                return $berita;
            });

        $galleryCategories = ['all', 'penginapan', 'moshulla', 'aula', 'gedung'];

        $socialLinks = [
            'facebook' => $settings['facebook'] ?? null,
            'instagram' => $settings['instagram'] ?? null,
            'youtube' => $settings['youtube'] ?? null,
            'telegram' => $settings['telegram'] ?? null,
            'twitter' => $settings['twitter/x'] ?? null,
        ];

        return compact(
            'settings',
            'contact',
            'brandLogo',
            'mapsUrl',
            'whatsappUrl',
            'socialLinks',
            'heroSlides',
            'galleryItems',
            'beritas',
            'galleryCategories',
            'faqItems',
            'termConditions', 
            'privacyItems'
        );
    }

    /**
     * Memvalidasi file gambar dari database. 
     * Prioritas utama: File dari database. 
     * Fallback: Gambar lokal yang valid jika file tidak ditemukan.
     */
    private function resolveImage(?string $path, string $fallbackType, int $index = 0): string
    {
        if (!$path) {
            return $this->getFallbackImage($fallbackType, $index);
        }

        // Jika path sudah berupa URL utuh (seperti placehold.co), langsung gunakan
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Bersihkan string path (hapus 'storage/' di awal jika ada)
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        // 1. Cek apakah file fisik ada di folder storage/app/public/
        if (file_exists(storage_path('app/public/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        // 2. Cek alternatif di folder public/storage/ (antisipasi jika symlink terputus tapi file ada)
        if (file_exists(public_path('storage/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        // 3. Jika file fisik BENAR-BENAR TIDAK ADA di server, jalankan fallback
        return $this->getFallbackImage($fallbackType, $index);
    }

    /**
     * Menyediakan gambar cadangan lokal dari folder public/assets/landing
     */
    private function getFallbackImage(string $type, int $index = 0): string
    {
        $slides = [
            'assets/landing/WhatsApp Image 2026-04-28 at 13.44.42.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.44.43.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.44.44.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.44.44 (1).jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.44.45.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.44.45 (1).jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.44.46.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.45.02.jpeg'
        ];

        $galeri = [
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.40.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.41.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.42.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.43.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.43 (1).jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.44.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.44 (1).jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.45.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.46.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.46 (1).jpeg'
        ];

        $berita = [
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.46 (2).jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.47.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.48.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.49.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.49 (1).jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.52.jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.52 (1).jpeg',
            'assets/landing/WhatsApp Image 2026-04-28 at 13.46.53.jpeg'
        ];

        if ($type === 'slide') {
            $path = $slides[$index % count($slides)];
        } elseif ($type === 'galeri') {
            $path = $galeri[$index % count($galeri)];
        } elseif ($type === 'berita') {
            $path = $berita[$index % count($berita)];
        } elseif ($type === 'logo') {
            $path = 'assets/landing/logo.png';
        } else {
            $path = 'assets/image/icon.png';
        }

        return asset($path);
    }
}
