@extends('public.layout.app')

@section('title', 'Detail Berita — Asrama Haji Emberkasi Landasan Ulin')

@push('styles')
<style>
        .news-detail-hero {
            background-color: #C9A961;
            padding: 120px 0 60px; /* adjusted padding to account for fixed navbar */
            text-align: center;
            color: white;
        }

        .news-detail-hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
            font-family: 'Playfair Display', serif; /* matched the font family of headings */
        }

        .news-detail-content {
            background-color: white;
            padding: 60px 0;
        }

        .news-detail-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .news-detail-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .news-detail-meta {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            color: #666;
        }

        .meta-item i {
            color: #C9A961;
            font-size: 1.1rem;
        }

        .meta-label {
            font-weight: 600;
            color: #5C4F47;
        }

        .meta-value {
            color: #666;
        }

        .news-detail-kategori {
            display: inline-block;
            background-color: #C9A961;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .news-detail-title {
            font-size: 2rem;
            font-weight: 700;
            color: #5C4F47;
            margin-bottom: 20px;
            line-height: 1.3;
            font-family: 'Playfair Display', serif;
        }

        .news-detail-body {
            color: #2C2C2C;
            font-size: 1rem;
            line-height: 1.8;
            text-align: justify;
        }

        .news-detail-body p {
            margin-bottom: 15px;
        }

        .news-detail-body p:last-child {
            margin-bottom: 0;
        }

        .news-back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #C9A961;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .news-back-button:hover {
            background-color: #5C4F47;
            color: white;
            transform: translateX(-3px);
        }

        @media (max-width: 768px) {
            .news-detail-title {
                font-size: 1.5rem;
            }

            .news-detail-image {
                height: 250px;
            }

            .news-detail-meta {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
@endpush

@section('content')
<main>
    

    <!-- NEWS DETAIL HERO SECTION -->
    <section class="news-detail-hero">
        <div class="container">
            <h1>Berita Detail</h1>
        </div>
    </section>

    <!-- NEWS DETAIL CONTENT SECTION -->
    <section class="news-detail-content">
        <div class="news-detail-container">
                        <!-- Gambar Berita -->
            <img src="{{ filter_var($berita->gambar, FILTER_VALIDATE_URL) ? $berita->gambar : asset(ltrim($berita->gambar, '/')) }}" onerror="this.src='https://placehold.co/800x400?text=Berita+Asrama+Haji'; this.onerror=null;" alt="{{ $berita->judul }}" class="news-detail-image" style="background: #e2e8f0;">

            <!-- Judul Berita -->
            <h2 class="news-detail-title">{{ $berita->judul }}</h2>

            <!-- Meta Information -->
            <div class="news-detail-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span><span class="meta-label">Tanggal:</span> <span class="meta-value">{{ \Carbon\Carbon::parse($berita->tanggal_publish)->translatedFormat('d F Y') }}</span></span>
                </div>
            </div>

            <!-- Konten Berita -->
            <div class="news-detail-body">
                {!! $berita->isi !!}
            </div>

            <!-- Back Button -->
            <a href="{{ route('public.berita.index') }}" class="news-back-button">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali ke Berita
            </a>
        </div>
    </section>

  </main>
@endsection


