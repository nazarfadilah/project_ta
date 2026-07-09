@php
    $copyright = \App\Models\Tentang::where('key', 'copyright')->first()?->value ?? '© 2026 Asrama Haji Emberkasi Landasan Ulin. Hak Cipta Dilindungi Undang-Undang.';
    $naungan = \App\Models\Tentang::where('key', 'naungan')->first()?->value ?? 'Dibawah naungan <strong>Kementerian Haji RI</strong> - Kanwil Kalimantan Selatan';
@endphp
<p style="margin: 0;">
    {!! $copyright !!}<br>
    {!! $naungan !!}
</p>
