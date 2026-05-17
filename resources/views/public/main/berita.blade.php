@extends('public.layout.app')

@section('title', 'Berita & Update — Asrama Haji Emberkasi Landasan Ulin')

@section('content')
<!-- ===================== BERITA HERO HEADER ===================== -->
  <section class="about-hero">
    <div class="container">
      <div style="text-align: center;">
        <h1 class="about-hero-title">Berita & Update</h1>
        <p style="color: var(--white); font-size: 1.05rem; margin-top: 12px;">Informasi Terbaru dari Asrama Kami</p>
      </div>
    </div>
  </section>

  <!-- ===================== BERITA TERKINI SECTION ===================== -->
  <section class="berita-section" style="padding: 80px 0;">
    <div class="container">
      <h2 class="section-title" style="text-align: center; margin-bottom: 56px;">Berita Terkini</h2>
      
      <!-- News Carousel -->
      <div class="news-carousel-wrapper">
        <button class="carousel-nav-prev" id="newsPrev">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>

        <div class="news-carousel-container">
                    <div class="news-carousel-track" id="newsCarouselTrack">
            @foreach($beritas as $berita)
            <!-- News Card -->
            <div class="news-carousel-item">
              <div class="news-card-carousel">
                <div class="news-image-carousel">
                  <img src="{{ filter_var($berita->gambar, FILTER_VALIDATE_URL) ? $berita->gambar : asset(ltrim($berita->gambar, '/')) }}" onerror="this.src='{{ asset('gambar_dashboard/berita_placeholder.jpg') }}'; this.onerror=null;" alt="{{ $berita->judul }}" style="width: 100%; height: 100%; object-fit: cover;">
                  <span class="news-date-badge">{{ \Carbon\Carbon::parse($berita->tanggal_publish)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="news-body-carousel">
                  <h3>{{ $berita->judul }}</h3>
                  <p>{{ Str::limit(strip_tags($berita->isi), 100) }}</p>
                  <a href="{{ route('public.berita.show', $berita->slug) }}" class="news-read-more">Baca Selengkapnya ?</a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
@endsection

@push('scripts')
<script>
// News Carousel functionality
    document.addEventListener('DOMContentLoaded', () => {
      const newsCarouselTrack = document.getElementById('newsCarouselTrack');
      const newsPrevBtn = document.getElementById('newsPrev');
      const newsNextBtn = document.getElementById('newsNext');
      let newsCurrentPosition = 0;
      const newsItemWidth = 100 / 3; // 3 items per view

      newsPrevBtn.addEventListener('click', () => {
        if (newsCurrentPosition > 0) {
          newsCurrentPosition -= newsItemWidth;
          updateNewsCarouselPosition();
        }
      });

      newsNextBtn.addEventListener('click', () => {
        const totalItems = newsCarouselTrack.children.length;
        const maxPosition = (totalItems - 3) * newsItemWidth;
        if (newsCurrentPosition < maxPosition) {
          newsCurrentPosition += newsItemWidth;
          updateNewsCarouselPosition();
        }
      });

      function updateNewsCarouselPosition() {
        newsCarouselTrack.style.transform = `translateX(-${newsCurrentPosition}%)`;
      }
    });
</script>
@endpush


