@extends('public.layout.app')

@section('title', 'Asrama Haji Emberkasi Landasan Ulin — Wisma & Fasilitas')

@section('content')

  <!-- ===================== HERO ===================== -->
  <section class="hero" id="home">
    <div class="hero-bg">
      <div class="hero-overlay"></div>
      <div class="hero-particles" id="heroParticles"></div>
    </div>

    <div class="hero-content">
      <h1 class="hero-title animate-fade-up" style="--delay: 0.1s">
        Tempat Menginap &<br/>
        <span class="hero-title-gold">Sewa Fasilitas</span><br/>
        Terpercaya di Banjarmasin
      </h1>

      <p class="hero-desc animate-fade-up" style="--delay: 0.2s">
        Nikmati kamar nyaman, aula representatif, dan fasilitas lengkap berkelas.
        Reservasi online mudah, cepat, dan transparan.
      </p>
    </div>
  </section>

  <!-- ===================== IMAGE CAROUSEL ===================== -->
  <section class="image-carousel-section">
    <div class="carousel-container">
      <button class="carousel-nav carousel-prev" id="carouselPrev" aria-label="Gambar sebelumnya">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      
            <div class="carousel-track">
        @foreach($heroSlides as $slide)
        <div class="carousel-slide {{ $loop->first ? 'active' : '' }}">
          <img src="{{ $slide->resolved_path }}" alt="Gambar Slide {{ $loop->iteration }}">
        </div>
        @endforeach
      </div>
      <button class="carousel-nav carousel-next" id="carouselNext" aria-label="Gambar berikutnya">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M9 19l7-7-7-7"/>
        </svg>
      </button>
    </div>
    
    <div class="carousel-dots" id="carouselDots"></div>
  </section>

  <!-- ===================== FEATURES STRIP ===================== -->
  <section class="features-strip">
    <div class="container">
      <div class="features-grid">
        <div class="feature-item">
          <div class="feature-icon">✅</div>
          <div class="feature-text">
            <strong>Booking Online 24/7</strong>
            <span>Reservasi kapan saja, di mana saja</span>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">�</div>
          <div class="feature-text">
            <strong>Harga Transparan</strong>
            <span>Tidak ada biaya tersembunyi</span>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">🔒</div>
          <div class="feature-text">
            <strong>Pembayaran Aman</strong>
            <span>QRIS, Transfer, Tunai</span>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">�</div>
          <div class="feature-text">
            <strong>Fasilitas Premium</strong>
            <span>Standar akomodasi internasional</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== FASILITAS ===================== -->
  <section class="section fasilitas-section" id="fasilitas">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Fasilitas Kami</span>
        <h2 class="section-title">Pilihan <span class="text-gold">Penginapan & Ruangan</span><br/>untuk Semua Kebutuhan</h2>
        <p class="section-desc">Dari kamar standar hingga suite eksklusif, dari aula kecil hingga gedung pertemuan besar — semua tersedia di satu tempat.</p>
      </div>

      <div class="facility-filter" id="facilityFilter">
        <button class="filter-btn active" data-filter="all" id="filterAll">Semua</button>
        <button class="filter-btn" data-filter="kamar" id="filterKamar">Kamar</button>
        <button class="filter-btn" data-filter="aula" id="filterAula">Aula & Gedung</button>
        <button class="filter-btn" data-filter="fasum" id="filterFasum">Fasilitas Umum</button>
      </div>

      <div class="facility-grid" id="facilityGrid">
        @foreach($ruangans as $ruangan)
        @php
          $category = 'fasum';
          if (str_contains($ruangan->tipe_ruangan, 'KAMAR')) {
              $category = 'kamar';
          } elseif ($ruangan->tipe_ruangan === 'AULA' || $ruangan->tipe_ruangan === 'RUANG_MEETING') {
              $category = 'aula';
          }

          $emoji = '🏢';
          $gradient = 'linear-gradient(135deg, #1a2a3a 0%, #2d4a6a 100%)';
          
          switch($ruangan->tipe_ruangan) {
              case 'KAMAR_STANDAR':
                  $emoji = '🛏️';
                  $gradient = 'linear-gradient(135deg, #1a2a3a 0%, #2d4a6a 100%)';
                  break;
              case 'KAMAR_VIP':
                  $emoji = '👑';
                  $gradient = 'linear-gradient(135deg, #2c1810 0%, #5c3520 100%)';
                  break;
              case 'KAMAR_PREMIUM':
                  $emoji = '✨';
                  $gradient = 'linear-gradient(135deg, #1a0d37 0%, #3a1a6a 100%)';
                  break;
              case 'AULA':
                  $emoji = '🏛️';
                  $gradient = 'linear-gradient(135deg, #0d2837 0%, #1a4a6a 100%)';
                  break;
              case 'RUANG_MEETING':
                  $emoji = '💼';
                  $gradient = 'linear-gradient(135deg, #0d3020 0%, #1a6a40 100%)';
                  break;
              case 'RUANG_LAINNYA':
                  $emoji = '🌟';
                  $gradient = 'linear-gradient(135deg, #2d1a0d 0%, #6a4020 100%)';
                  break;
          }

          $features = [];
          $features[] = '👥 ' . ($ruangan->kapasitas > 1 ? 's.d. ' : '') . $ruangan->kapasitas . ' Orang';
          
          $desc = strtolower($ruangan->keterangan);
          if (str_contains($desc, 'ac')) $features[] = '❄️ AC';
          if (str_contains($desc, 'wifi') || str_contains($desc, 'wi-fi')) $features[] = '📶 WiFi';
          if (str_contains($desc, 'tv')) $features[] = '📺 TV';
          if (str_contains($desc, 'bathtub')) $features[] = '🛁 Bathtub';
          if (str_contains($desc, 'sound') || str_contains($desc, 'audio')) $features[] = '🔊 Sound';
          if (str_contains($desc, 'proyektor') || str_contains($desc, 'projector')) $features[] = '📽️ Proyektor';
          if (str_contains($desc, 'panggung')) $features[] = '🎤 Panggung';
          if (str_contains($desc, 'kulkas')) $features[] = '❄️ Kulkas';
          if (str_contains($desc, 'sarapan')) $features[] = '🍳 Sarapan';
          
          if (count($features) < 3) {
              if (str_contains($ruangan->tipe_ruangan, 'KAMAR')) {
                  $features[] = '📶 WiFi';
                  $features[] = '❄️ AC';
              } else {
                  $features[] = '❄️ AC';
              }
          }
          $features = array_unique($features);
          $features = array_slice($features, 0, 4);

          $minPaket = $ruangan->paketRuangans->sortBy('harga')->first();
          $priceText = 'Hubungi Kami';
          $periodText = '';
          if ($minPaket) {
              $priceText = 'Rp ' . number_format($minPaket->harga, 0, ',', '.');
              $periodText = $minPaket->durasi == 24 ? '/ malam' : '/ ' . $minPaket->durasi . ' jam';
          }
          
          $hasImage = $ruangan->mediaFiles->isNotEmpty();
          $imageUrl = $hasImage ? asset($ruangan->mediaFiles->first()->path) : null;
        @endphp
        <div class="facility-card {{ $ruangan->tipe_ruangan === 'KAMAR_VIP' ? 'featured' : '' }}" data-category="{{ $category }}">
          <div class="card-image-wrap">
            @if($hasImage)
              <div class="card-image" style="background-image: url('{{ $imageUrl }}'); background-size: cover; background-position: center;">
              </div>
            @else
              <div class="card-image" style="background: {{ $gradient }};">
                <div class="card-image-placeholder">
                  <span>{{ $emoji }}</span>
                </div>
              </div>
            @endif
            <div class="card-badge">{{ $ruangan->tipe_ruangan === 'KAMAR_VIP' ? 'Rekomendasi' : 'Tersedia' }}</div>
            <div class="card-tag">{{ str_replace('_', ' ', $ruangan->tipe_ruangan) }}</div>
          </div>
          <div class="card-body">
            <h3 class="card-title">{{ $ruangan->nama_ruangan }}</h3>
            <p class="card-desc">{{ Str::limit(strip_tags($ruangan->keterangan), 120) }}</p>
            <div class="card-features">
              @foreach($features as $feature)
                <span>{{ $feature }}</span>
              @endforeach
            </div>
            <div class="card-footer">
              <div class="card-price">
                @if($minPaket)
                  <span class="price-from">Mulai dari</span>
                  <span class="price-amount">{{ $priceText }}</span>
                  <span class="price-period">{{ $periodText }}</span>
                @else
                  <span class="price-amount">{{ $priceText }}</span>
                @endif
              </div>
              <a href="{{ route('users.main.reservasi.create') }}" class="card-btn" id="bookRoom{{ $ruangan->id_ruangan }}">Pesan</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>



  <!-- ===================== HOW IT WORKS ===================== -->
  <section class="section how-section">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Cara Booking</span>
        <h2 class="section-title">Reservasi Mudah dalam<br/><span class="text-gold">4 Langkah Simpel</span></h2>
      </div>

      <div class="steps-grid">
        <div class="step-item">
          <div class="step-num">01</div>
          <div class="step-icon">🔍</div>
          <h3>Cari Fasilitas</h3>
          <p>Pilih kamar atau ruangan yang tersedia sesuai tanggal dan kebutuhan Anda.</p>
        </div>
        <div class="step-arrow">→</div>
        <div class="step-item">
          <div class="step-num">02</div>
          <div class="step-icon">📝</div>
          <h3>Isi Formulir</h3>
          <p>Masukkan data diri dan detail pesanan. Dapatkan kode booking unik otomatis.</p>
        </div>
        <div class="step-arrow">→</div>
        <div class="step-item">
          <div class="step-num">03</div>
          <div class="step-icon">💳</div>
          <h3>Bayar</h3>
          <p>Silahkan Lakukan Pembayaran ditempat saat akan melakukan check-in.</p>
        </div>
        <div class="step-arrow">→</div>
        <div class="step-item">
          <div class="step-num">04</div>
          <div class="step-icon">🏨</div>
          <h3>Check-In</h3>
          <p>Tunjukkan kode booking saat tiba. Kami siap menyambut Anda!</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== BERITA ===================== -->
  <section class="section berita-section" id="berita">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Berita & Pengumuman</span>
        <h2 class="section-title">Info Terkini dari<br/><span class="text-gold">Asrama Haji</span></h2>
      </div>

            <div class="news-grid">
        @foreach($beritas as $index => $berita)
          @if($index == 0)
          <article class="news-card featured-news">
            <div class="news-image" style="background-image: url('{{ $berita->resolved_image }}'); background-size: cover; background-position: center;">
              <div class="news-date-badge">{{ \Carbon\Carbon::parse($berita->tanggal_publish)->format('d M Y') }}</div>
            </div>
            <div class="news-body">
              <span class="news-tag">Berita</span>
              <h3>{{ $berita->judul }}</h3>
              <p>{{ Str::limit(strip_tags($berita->isi), 150) }}</p>
              <a href="{{ route('public.berita.show', $berita->slug) }}" class="news-read-more" id="newsFeatured">Baca Selengkapnya ?</a>
            </div>
          </article>
          @elseif($index <= 2)
          <article class="news-card">
            <div class="news-image small" style="background-image: url('{{ $berita->resolved_image }}'); background-size: cover; background-position: center;">
              <div class="news-date-badge">{{ \Carbon\Carbon::parse($berita->tanggal_publish)->format('d M Y') }}</div>
            </div>
            <div class="news-body">
              <span class="news-tag">Berita</span>
              <h3>{{ $berita->judul }}</h3>
              <p>{{ Str::limit(strip_tags($berita->isi), 100) }}</p>
              <a href="{{ route('public.berita.show', $berita->slug) }}" class="news-read-more" id="newsSmall{{ $index }}">Baca ?</a>
            </div>
          </article>
          @endif
        @endforeach
      </div>

      <div class="view-all-wrap">
        <a href="{{ route('public.berita.index') }}" class="btn-view-all" id="viewAllNews">
          Lihat Semua Berita
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== CTA BANNER ===================== -->
  <section class="cta-banner-section">
    <div class="cta-banner">
      <div class="cta-glow"></div>
      <div class="cta-content">
        <h2>Siap Memesan Fasilitas?</h2>
        <p>Reservasi sekarang dan dapatkan konfirmasi instan via WhatsApp atau Email.</p>
        <div class="cta-btns">
          <a href="{{ route('users.main.reservasi.create') }}" class="btn-primary" id="ctaBannerBook">Booking Online</a>
          <a href="https://wa.me/6281234567890" class="btn-wa" id="ctaWA" target="_blank">
            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WhatsApp
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== KONTAK ===================== -->
  <section class="section kontak-section" id="kontak">
    <div class="container">
      <div class="kontak-grid">
        <div class="kontak-info">
          <span class="section-tag">Kontak Kami</span>
          <h2>Butuh Bantuan?<br/>Kami Siap <span class="text-gold">Melayani</span></h2>
          <p>Tim kami siap membantu Anda 24/7 untuk menjawab pertanyaan dan membantu proses reservasi.</p>

          <div class="kontak-items">
            <div class="kontak-item">
              <div class="kontak-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div>
                <strong>Alamat</strong>
                <span>{{ $globalContact['alamat'] ?? 'Jl. Gubernur Sarkawi No.1, Banjarmasin, Kalimantan Selatan 70238' }}</span>
              </div>
            </div>
            <div class="kontak-item">
              <div class="kontak-icon"><i class="fas fa-phone-alt"></i></div>
              <div>
                <strong>Telepon</strong>
                <span>{{ $globalContact['kantor'] ?? $globalContact['no_hp'] ?? '(0511) 3364880' }}</span>
              </div>
            </div>
            <div class="kontak-item">
              <div class="kontak-icon"><i class="fas fa-envelope"></i></div>
              <div>
                <strong>Email</strong>
                <span>{{ $globalContact['email'] ?? 'info@asramahaji-bjm.go.id' }}</span>
              </div>
            </div>
            <div class="kontak-item">
              <div class="kontak-icon"><i class="fas fa-clock"></i></div>
              <div>
                <strong>Jam Operasional</strong>
                <span>Senin - Jumat, {{ $globalContact['jam_mulai'] ?? '08.00' }} - {{ $globalContact['jam_akhir'] ?? '17.00' }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="map-container" style="margin-top: 40px; width: 100%; height: 400px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <iframe 
          src="{{ $mapsUrl }}" 
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </section>

@endsection
