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

        $brandLogo = isset($settings['logo']) && $settings['logo'] ? asset(ltrim($settings['logo'], '/')) : '';
        
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
                $slide->resolved_path = $slide->path ? (filter_var($slide->path, FILTER_VALIDATE_URL) ? $slide->path : (str_starts_with($slide->path, 'storage/') ? asset($slide->path) : asset('storage/' . $slide->path))) : '';
                return $slide;
            });

        $galleryItems = Galeri::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item) {
                $item->resolved_image = $item->gambar ? asset(ltrim($item->gambar, '/')) : '';
                return $item;
            });

        $beritas = Berita::query()
            ->where('status', 'approved')
            ->orderByDesc('tanggal_publish')
            ->get()
            ->map(function ($berita) {
                $berita->resolved_image = $berita->gambar ? asset(ltrim($berita->gambar, '/')) : '';
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
}
