<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('public.*', function ($view) {
            $settings = \App\Models\Tentang::where(function($q) {
                $q->whereNull('key2')->orWhere('key2', '');
            })->pluck('value', 'key')->toArray();
            
            $globalContact = [
                'nama_instansi' => $settings['nama_instansi'] ?? '',
                'alamat' => $settings['alamat'] ?? '',
                'no_hp' => $settings['no_hp'] ?? '',
                'email' => $settings['email'] ?? '',
                'kantor' => $settings['kantor'] ?? '',
                'jam_mulai' => $settings['jam_mulai'] ?? '',
                'jam_akhir' => $settings['jam_akhir'] ?? '',
                'jam_sabtu' => $settings['jam_sabtu'] ?? '',
                'tentang' => $settings['tentang'] ?? '',
                'copyright' => $settings['copyright'] ?? '',
                'naungan' => $settings['naungan'] ?? '',
            ];

            $globalSocial = [
                'facebook' => $settings['facebook'] ?? '',
                'instagram' => $settings['instagram'] ?? '',
                'youtube' => $settings['youtube'] ?? '',
                'telegram' => $settings['telegram'] ?? '',
                'twitter' => $settings['twitter/x'] ?? '',
                'whatsapp' => $settings['whatsapp'] ?? '',
                'e-katalog' => $settings['e-katalog'] ?? '',
            ];

            $globalLogo = isset($settings['logo']) && $settings['logo'] ? asset(ltrim($settings['logo'], '/')) : '';

            $view->with(compact('globalContact', 'globalSocial', 'globalLogo'));
        });
    }
}

