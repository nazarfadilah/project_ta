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
                  <img src="{{ filter_var($berita->gambar, FILTER_VALIDATE_URL) ? $berita->gambar : asset(ltrim($berita->gambar, '/')) }}" onerror="this.src='https://placehold.co/500x300?text=Berita+Asrama+Haji'; this.onerror=null;" alt="{{ $berita->judul }}" style="width: 100%; height: 100%; object-fit: cover;">
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

        <button class="carousel-nav-next" id="newsNext">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
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

      if (!newsCarouselTrack || !newsPrevBtn || !newsNextBtn) return;

      const totalItems = newsCarouselTrack.children.length;
      if (totalItems === 0) {
        newsPrevBtn.style.display = 'none';
        newsNextBtn.style.display = 'none';
        return;
      }

      let currentIndex = 0;

      function updateNewsCarouselPosition() {
        const containerWidth = newsCarouselTrack.parentElement.offsetWidth;
        const lastItem = newsCarouselTrack.children[totalItems - 1];
        const lastItemRight = lastItem.offsetLeft + lastItem.offsetWidth;

        // Check if all items fit inside the container
        if (lastItemRight <= containerWidth) {
          newsPrevBtn.style.display = 'none';
          newsNextBtn.style.display = 'none';
          newsCarouselTrack.style.transform = 'translateX(0px)';
          return;
        } else {
          newsPrevBtn.style.display = 'flex';
          newsNextBtn.style.display = 'flex';
        }

        const maxTranslate = lastItemRight - containerWidth;

        let targetTranslate = newsCarouselTrack.children[currentIndex].offsetLeft;
        if (targetTranslate > maxTranslate) {
          targetTranslate = maxTranslate;
        }

        newsCarouselTrack.style.transform = `translateX(-${targetTranslate}px)`;

        // Update button states (disable/opacity)
        if (currentIndex === 0) {
          newsPrevBtn.style.opacity = '0.5';
          newsPrevBtn.style.pointerEvents = 'none';
        } else {
          newsPrevBtn.style.opacity = '1';
          newsPrevBtn.style.pointerEvents = 'auto';
        }

        if (targetTranslate >= maxTranslate) {
          newsNextBtn.style.opacity = '0.5';
          newsNextBtn.style.pointerEvents = 'none';
        } else {
          newsNextBtn.style.opacity = '1';
          newsNextBtn.style.pointerEvents = 'auto';
        }
      }

      newsPrevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
          currentIndex--;
          updateNewsCarouselPosition();
        }
      });

      newsNextBtn.addEventListener('click', () => {
        const containerWidth = newsCarouselTrack.parentElement.offsetWidth;
        const lastItem = newsCarouselTrack.children[totalItems - 1];
        const lastItemRight = lastItem.offsetLeft + lastItem.offsetWidth;
        const currentTranslate = newsCarouselTrack.children[currentIndex].offsetLeft;

        if (currentTranslate + containerWidth < lastItemRight) {
          currentIndex++;
          updateNewsCarouselPosition();
        }
      });

      // Initial layout setup
      updateNewsCarouselPosition();

      // Recalculate layout on window resize
      window.addEventListener('resize', updateNewsCarouselPosition);
    });
</script>
@endpush


