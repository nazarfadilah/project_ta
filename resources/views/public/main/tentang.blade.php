@extends('public.layout.app')

@section('title', 'Tentang Kami — Asrama Haji Emberkasi Landasan Ulin')

@section('content')
<!-- ===================== ABOUT HERO HEADER ===================== -->
  <section class="about-hero">
    <div class="container">
      <h1 class="about-hero-title">Tentang Kami</h1>
    </div>
  </section>

  <!-- ===================== ABOUT CONTENT ===================== -->
  <section class="about-section">
    <div class="container">
      <div class="about-content">
        <p style="white-space: pre-line;">
          {{ $globalContact['tentang'] ?? '' }}
        </p>
      </div>
    </div>
  </section>

@endsection
