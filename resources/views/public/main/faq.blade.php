@extends('public.layout.app')

@section('title', 'FAQ — Asrama Haji Emberkasi Landasan Ulin')

@section('content')
<!-- ===================== FAQ HERO HEADER ===================== -->
  <section class="about-hero">
    <div class="container">
      <h1 class="about-hero-title">FAQ</h1>
    </div>
  </section>

  <!-- ===================== FAQ CONTENT ===================== -->
  <section class="about-section">
    <div class="container">
      <div class="about-content">
                <div class="faq-container">
          @foreach($faqItems as $faq)
          <div class="faq-item">
            <button class="faq-header" aria-expanded="false">
              <span class="faq-question">{{ $faq['question'] }}</span>
              <span class="faq-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
              </span>
            </button>
            <div class="faq-content">
              <p>{{ $faq['answer'] }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== CTA BANNER ===================== -->
  <section class="cta-banner-section">
    <div class="cta-banner">
      <div class="cta-glow"></div>
      <div class="cta-content">
        <h2>Pertanyaan Lainnya?</h2>
        <p>Tim kami siap membantu Anda 24/7. Hubungi kami melalui WhatsApp atau kontak kami yang tersedia.</p>
        <div class="cta-btns">
          <a href="https://wa.me/6281234567890" class="btn-wa" target="_blank">
            💬 WhatsApp Kami
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
// FAQ Accordion functionality
    document.addEventListener('DOMContentLoaded', () => {
      const faqHeaders = document.querySelectorAll('.faq-header');
      
      faqHeaders.forEach(header => {
        header.addEventListener('click', () => {
          const faqItem = header.parentElement;
          const isExpanded = header.getAttribute('aria-expanded') === 'true';
          
          // Close all other items
          document.querySelectorAll('.faq-item').forEach(item => {
            const btn = item.querySelector('.faq-header');
            btn.setAttribute('aria-expanded', 'false');
            item.classList.remove('active');
          });
          
          // Toggle current item
          if (!isExpanded) {
            header.setAttribute('aria-expanded', 'true');
            faqItem.classList.add('active');
          }
        });
      });
    });
</script>
@endpush


