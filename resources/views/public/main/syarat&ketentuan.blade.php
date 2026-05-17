@extends('public.layout.app')

@section('title', 'Syarat & Ketentuan — Asrama Haji Emberkasi Landasan Ulin')

@section('content')
<!-- ===================== SYARAT & KETENTUAN HERO HEADER ===================== -->
  <section class="about-hero">
    <div class="container">
      <h1 class="about-hero-title">Syarat & Ketentuan</h1>
    </div>
  </section>

  <!-- ===================== SYARAT & KETENTUAN CONTENT ===================== -->
  <section class="about-section">
    <div class="container">
      <div class="about-content">
        
                @foreach($termConditions as $term)
        <!-- {{ $term['title'] }} -->
        <h2 class="about-section-title">{{ $term['title'] }}</h2>
        <p>{{ $term['content'] }}</p>
        @endforeach
        </div>
    </div>
  </section>

  <!-- ===================== CTA BANNER ===================== -->
  <section class="cta-banner-section">
    <div class="cta-banner">
      <div class="cta-glow"></div>
      <div class="cta-content">
        <h2>Siap untuk Menginap?</h2>
        <p>Hubungi kami untuk pemesanan atau pertanyaan lebih lanjut tentang layanan kami.</p>
        <div class="cta-btns">
          <a href="https://wa.me/6281234567890" class="btn-wa" target="_blank">
            💬 WhatsApp Kami
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection


