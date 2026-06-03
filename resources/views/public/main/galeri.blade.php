@extends('public.layout.app')

@section('title', 'Galeri — Asrama Haji Emberkasi Landasan Ulin')

@section('content')
  <!-- ===================== HERO HEADER ===================== -->
  <section class="about-hero">
    <div class="container" style="text-align: center;">
      <h1 class="about-hero-title">Galeri</h1>
      <p style="color: var(--white, #fff); margin-top: 10px; font-size: 1.1rem;">Lihat Keindahan Asrama Kami</p>
    </div>
  </section>

  <!-- ===================== GALLERY CONTENT ===================== -->
  <section style="padding: 60px 0; position: relative;">
    <div class="container relative-container">
      <h2 class="section-title" style="text-align: center; margin-bottom: 40px;">Koleksi Foto</h2>

      <!-- Filters -->
      <div class="gallery-filters" style="display: flex; justify-content: center; flex-wrap: wrap; gap: 15px; margin-bottom: 40px;">
        <button class="filter-btn active" data-filter="all">Semua</button>
        @foreach($kategoriGaleri as $cat)
          @if($cat)
            <button class="filter-btn" data-filter="{{ Str::slug($cat) }}">{{ $cat }}</button>
          @endif
        @endforeach
      </div>

      <!-- Carousel Wrapper -->
      <div class="custom-carousel-wrapper" style="position: relative; max-width: 1000px; margin: 0 auto; overflow: hidden; padding: 20px 0;">
        
        <button id="galleryPrevBtn" class="gallery-arrow left-arrow">
          <i class="fas fa-chevron-left"></i>
        </button>
        
        <div class="gallery-carousel-viewport" style="overflow: hidden; padding: 10px;">
          <div class="gallery-carousel-track" id="galleryTrack" style="display: flex; transition: transform 0.5s ease-in-out;">
             <!-- Items will be injected here via JS -->
          </div>
        </div>

        <button id="galleryNextBtn" class="gallery-arrow right-arrow">
          <i class="fas fa-chevron-right"></i>
        </button>

      </div>
    </div>
  </section>

  <!-- Hidden original data for JS -->
  <div id="galleryData" style="display: none;">
    @foreach($galeriItems as $item)
      <div class="gallery-data-item" data-category="{{ Str::slug($item->kategori) }}">
        <div class="gallery-card">
          <img src="{{ filter_var($item->gambar, FILTER_VALIDATE_URL) ? $item->gambar : (str_starts_with($item->gambar, 'storage/') ? asset($item->gambar) : asset('storage/' . $item->gambar)) }}" alt="{{ $item->judul }}">
        </div>
      </div>
    @endforeach
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const track = document.getElementById('galleryTrack');
      const prevBtn = document.getElementById('galleryPrevBtn');
      const nextBtn = document.getElementById('galleryNextBtn');
      const filterBtns = document.querySelectorAll('.filter-btn');
      
      // Get all raw items
      const rawItems = Array.from(document.querySelectorAll('.gallery-data-item'));
      
      let currentPosition = 0;
      let totalColumns = 0;
      let itemWidthPercent = 33.3333; // 3 columns visible

      // Adjust columns for mobile
      if (window.innerWidth < 768) {
        itemWidthPercent = 100;
      } else if (window.innerWidth < 1024) {
        itemWidthPercent = 50;
      }

      function renderCarousel(filterValue) {
        // 1. Filter raw items
        let filteredItems = rawItems.filter(item => {
          return filterValue === 'all' || item.getAttribute('data-category') === filterValue;
        });

        // 2. Chunk items into columns of 2 (to show 2 rows = 6 items if 3 cols are visible)
        let columnsHTML = '';
        const itemsPerColumn = 2;
        
        for (let i = 0; i < filteredItems.length; i += itemsPerColumn) {
          const chunk = filteredItems.slice(i, i + itemsPerColumn);
          let colContent = '';
          chunk.forEach(el => {
            colContent += el.outerHTML;
          });
          columnsHTML += `<div class="gallery-column" style="flex: 0 0 ${itemWidthPercent}%; padding: 0 15px; display: flex; flex-direction: column; gap: 30px;">${colContent}</div>`;
        }

        if (filteredItems.length === 0) {
          columnsHTML = `<div style="width: 100%; text-align: center; padding: 40px;">Belum ada foto dalam galeri.</div>`;
          totalColumns = 0;
        } else {
          totalColumns = Math.ceil(filteredItems.length / itemsPerColumn);
        }

        // 3. Update track
        track.innerHTML = columnsHTML;
        
        // 4. Reset position
        currentPosition = 0;
        updatePosition();
      }

      function updatePosition() {
        track.style.transform = `translateX(-${currentPosition}%)`;
      }

      prevBtn.addEventListener('click', () => {
        if (currentPosition > 0) {
          currentPosition -= itemWidthPercent;
          // floating point rounding fix
          if (currentPosition < 1) currentPosition = 0;
          updatePosition();
        }
      });

      nextBtn.addEventListener('click', () => {
        const visibleCols = Math.round(100 / itemWidthPercent);
        const maxPosition = (totalColumns - visibleCols) * itemWidthPercent;
        
        if (totalColumns > visibleCols && currentPosition < maxPosition - 1) {
          currentPosition += itemWidthPercent;
          updatePosition();
        }
      });

      // Filter events
      filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          filterBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          renderCarousel(btn.getAttribute('data-filter'));
        });
      });

      // Initial render
      renderCarousel('all');

      // Handle resize
      window.addEventListener('resize', () => {
        let newPercent = 33.3333;
        if (window.innerWidth < 768) newPercent = 100;
        else if (window.innerWidth < 1024) newPercent = 50;
        
        if (newPercent !== itemWidthPercent) {
          itemWidthPercent = newPercent;
          const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
          renderCarousel(activeFilter);
        }
      });
    });
  </script>

  <!-- Styles for Gallery -->
  <style>
    .gallery-filters .filter-btn {
      padding: 8px 24px;
      border: 1px solid var(--primary-color, #c89e51);
      background: transparent;
      color: var(--primary-color, #c89e51);
      border-radius: 30px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .gallery-filters .filter-btn.active, 
    .gallery-filters .filter-btn:hover {
      background: var(--primary-color, #c89e51);
      color: #fff;
    }

    .gallery-card {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      aspect-ratio: 4/3;
      transition: transform 0.3s ease;
      background: #fff;
    }
    
    .gallery-card:hover {
      transform: translateY(-5px);
    }
    
    .gallery-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Custom Arrows */
    .gallery-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 45px;
      height: 45px;
      background: var(--primary-color, #c89e51);
      color: #fff;
      border: none;
      border-radius: 50%;
      font-size: 1.2rem;
      cursor: pointer;
      z-index: 10;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.3s ease;
    }
    .gallery-arrow:hover {
      background: #a68242;
    }
    .left-arrow {
      left: 0px;
    }
    .right-arrow {
      right: 0px;
    }
    .gallery-carousel-viewport {
      margin: 0 50px; /* Space for arrows */
    }
  </style>
@endsection
