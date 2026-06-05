<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Asrama Haji Emberkasi Landasan Ulin')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/image/icon.png') }}">
  <meta name="description" content="Nikmati fasilitas penginapan premium dan sewa gedung di Asrama Haji Emberkasi Landasan Ulin. Booking online mudah, harga terjangkau, pelayanan profesional." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/landing/style.css') }}" />
  @stack('styles')
</head>
<body>

  <!-- ===================== NAVBAR ===================== -->
  @include('public.layout.navbar')

  <!-- ===================== CONTENT ===================== -->
  @yield('content')

  <!-- ===================== FOOTER ===================== -->
  @include('public.layout.footer')

  <!-- Toast Notification -->
  <div class="toast" id="toast">
    <span class="toast-icon">✅</span>
    <span class="toast-msg" id="toastMsg"></span>
  </div>

  <script src="{{ asset('js/landing/script.js') }}"></script>
  @stack('scripts')
  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Mobile menu toggle
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    if (hamburger) {
      hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('open');
      });
      // Close menu when link is clicked
      if (navLinks) {
        navLinks.querySelectorAll('.nav-link').forEach(link => {
          link.addEventListener('click', () => {
            navLinks.classList.remove('open');
          });
        });
      }
    }</script>
</body>
</html>

