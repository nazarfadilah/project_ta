<footer class="footer">
    <div class="footer-top">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-brand">
            <div class="footer-logo">
              <img src="{{ filter_var($globalLogo, FILTER_VALIDATE_URL) ? $globalLogo : asset(ltrim($globalLogo, '/')) }}" alt="{{ $globalContact['nama_instansi'] }}" width="36" height="36" style="border-radius: 50%;" />
              <div>
                <strong>{{ $globalContact['nama_instansi'] }}</strong>
              </div>
            </div>
            <p>Fasilitas penginapan dan sewa gedung terpercaya di bawah naungan Kementerian Agama Kalimantan Selatan.</p>
            <div class="footer-social">
              @if(!empty($globalSocial['facebook'])) <a href="{{ $globalSocial['facebook'] }}" class="social-link social-fb" id="socialFb" aria-label="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a> @endif
              @if(!empty($globalSocial['instagram'])) <a href="{{ $globalSocial['instagram'] }}" class="social-link social-ig" id="socialIg" aria-label="Instagram" target="_blank"><i class="fab fa-instagram"></i></a> @endif
              @if(!empty($globalSocial['youtube'])) <a href="{{ $globalSocial['youtube'] }}" class="social-link social-yt" id="socialYt" aria-label="YouTube" target="_blank"><i class="fab fa-youtube"></i></a> @endif
              @if(!empty($globalSocial['whatsapp'])) <a href="{{ $globalSocial['whatsapp'] }}" class="social-link social-wa" id="socialWa" aria-label="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a> @endif
              @if(!empty($globalSocial['telegram'])) <a href="{{ $globalSocial['telegram'] }}" class="social-link social-tg" id="socialTg" aria-label="Telegram" target="_blank"><i class="fab fa-telegram-plane"></i></a> @endif
              @if(!empty($globalSocial['twitter'])) <a href="{{ $globalSocial['twitter'] }}" class="social-link social-x" id="socialX" aria-label="X" target="_blank"><i class="fab fa-x-twitter"></i></a> @endif
            </div>
          </div>

          <div style="flex: 1;"></div>

          <div class="footer-links-col">
            <h4>Informasi</h4>
            <ul>
              <li><a href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
              <li><a href="{{ url('/kebijakan-privasi') }}">Kebijakan Privasi</a></li>
              <li><a href="{{ url('/berita') }}">Berita & Update</a></li>
              <li><a href="{{ url('/faq') }}">FAQ</a></li>
              <li><a href="{{ url('/syarat-ketentuan') }}">Syarat & Ketentuan</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p>© 2026 Asrama Haji Emberkasi Landasan Ulin. Hak Cipta Dilindungi Undang-Undang.</p>
        <p>Dibawah naungan <strong>Kementerian Agama RI</strong> · Kanwil Kalimantan Selatan</p>
      </div>
    </div>
</footer>
