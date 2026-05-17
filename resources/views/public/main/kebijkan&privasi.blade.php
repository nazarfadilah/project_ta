@extends('public.layout.app')

@section('title', 'Pernyataan Privasi — Asrama Haji Emberkasi Landasan Ulin')

@section('content')
<!-- ===================== PRIVACY HERO HEADER ===================== -->
  <section class="about-hero">
    <div class="container">
      <h1 class="about-hero-title">Pernyataan Privasi</h1>
    </div>
  </section>

  <!-- ===================== PRIVACY CONTENT ===================== -->
  <section class="about-section">
    <div class="container">
      <div class="about-content">
                @foreach($privacyItems as $privacy)
        <h2 class="about-section-title">{{ $privacy['title'] }}</h2>
        <p>{{ $privacy['content'] }}</p>
        @endforeach

      </div>
    </div>
  </section>

  <!-- ===================== CTA BANNER ===================== -->
  <section class="cta-banner-section">
    <div class="cta-banner">
      <div class="cta-glow"></div>
      <div class="cta-content">
        <h2>Ada Pertanyaan?</h2>
        <p>Hubungi kami jika Anda memiliki pertanyaan tentang kebijakan privasi atau data pribadi Anda.</p>
        <div class="cta-btns">
          <a href="https://wa.me/6281234567890" class="btn-wa" target="_blank">
            💬 WhatsApp Kami
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection



