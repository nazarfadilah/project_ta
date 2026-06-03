<nav class="navbar" id="navbar">
    <div class="nav-container">
      <a href="#" class="nav-logo">
        <div class="logo-icon">
          <img src="{{ filter_var($globalLogo, FILTER_VALIDATE_URL) ? $globalLogo : asset(ltrim($globalLogo, '/')) }}" alt="{{ $globalContact['nama_instansi'] }}" width="32" height="32" style="border-radius: 50%;" />
        </div>
        <div class="logo-text">
          <span class="logo-main">{{ $globalContact['nama_instansi'] }}</span>
        </div>
      </a>

      <div class="nav-links" id="navLinks">
        <a href="{{ url('/') }}" class="nav-link">Beranda</a>
        <a href="{{ url('/#fasilitas') }}" class="nav-link">Fasilitas</a>
        <a href="{{ url('/#harga') }}" class="nav-link">Harga</a>
        <a href="{{ url('/berita') }}" class="nav-link">Berita</a>
        <a href="{{ url('/#kontak') }}" class="nav-link">Kontak</a>
        <a href="/login" class="btn-booking" target="_blank">
          <span>📱</span>
          Booking Sekarang
        </a>
      </div>

      <div class="nav-hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
</nav>
