import fs from 'fs';
import path from 'path';

const outDir = './Wireframe';

if (!fs.existsSync(outDir)) {
  fs.mkdirSync(outDir, { recursive: true });
}

// Obfuscator: turns text into 'xxx' preserving structure, spacing, capitalization, and numbers
const obfuscateHTML = (html) => {
  const blocks = [];
  let processed = html;

  // Extract <style> blocks
  processed = processed.replace(/<style[^>]*>([\s\S]*?)<\/style>/gi, (match) => {
    const placeholder = `___STYLE_BLOCK_${blocks.length}___`;
    blocks.push({ placeholder, content: match });
    return placeholder;
  });

  // Extract <script> blocks
  processed = processed.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, (match) => {
    const placeholder = `___SCRIPT_BLOCK_${blocks.length}___`;
    blocks.push({ placeholder, content: match });
    return placeholder;
  });

  const obfuscateText = (text) => {
    return text.split(/(\s+)/).map(word => {
      if (word.trim() === '') return word;
      return word.replace(/[a-zA-Z0-9]/g, char => {
        if (/[A-Z]/.test(char)) return 'X';
        if (/[a-z]/.test(char)) return 'x';
        if (/[0-9]/.test(char)) return 'x';
        return char;
      });
    }).join('');
  };

  // 1. Obfuscate <tbody>...</tbody> content (excluding action buttons/links)
  processed = processed.replace(/<tbody[^>]*>([\s\S]*?)<\/tbody>/gi, (match, bodyContent) => {
    const subBlocks = [];
    let subProcessed = bodyContent;

    // Extract buttons
    subProcessed = subProcessed.replace(/<button[^>]*>([\s\S]*?)<\/button>/gi, (btnMatch) => {
      const placeholder = `___SUB_BTN_${subBlocks.length}___`;
      subBlocks.push({ placeholder, content: btnMatch });
      return placeholder;
    });

    // Extract link buttons (a with wf-btn class)
    subProcessed = subProcessed.replace(/<a[^>]*class="[^"]*wf-btn[^"]*"[^>]*>([\s\S]*?)<\/a>/gi, (linkMatch) => {
      const placeholder = `___SUB_LINK_${subBlocks.length}___`;
      subBlocks.push({ placeholder, content: linkMatch });
      return placeholder;
    });

    // Obfuscate text nodes inside tbody
    subProcessed = subProcessed.replace(/>([^<]+)</g, (textMatch, text) => {
      const trimmed = text.trim();
      if (trimmed === '') return textMatch;
      if (text.includes('___SUB_BTN_') || text.includes('___SUB_LINK_')) return textMatch;
      // Do not obfuscate row headers like "Subtotal", "Biaya Tambahan", "Total Harga"
      if (trimmed === 'Subtotal' || trimmed === 'Biaya Tambahan' || trimmed === 'Total Harga') {
        return textMatch;
      }
      return `>${obfuscateText(text)}<`;
    });

    // Re-inject buttons and link buttons
    for (const block of subBlocks) {
      subProcessed = subProcessed.replace(block.placeholder, block.content);
    }

    const openTag = match.match(/<tbody[^>]*>/i)[0];
    return `${openTag}${subProcessed}</tbody>`;
  });

  // 2. Obfuscate option tags text content: <option...>TEXT</option>
  processed = processed.replace(/(<option[^>]*>)([\s\S]*?)(<\/option>)/gi, (match, openTag, text, closeTag) => {
    return `${openTag}${obfuscateText(text)}${closeTag}`;
  });

  // 3. Obfuscate textarea tags text content: <textarea...>TEXT</textarea>
  processed = processed.replace(/(<textarea[^>]*>)([\s\S]*?)(<\/textarea>)/gi, (match, openTag, text, closeTag) => {
    return `${openTag}${obfuscateText(text)}${closeTag}`;
  });

  // 4. Obfuscate attributes like placeholder, value, title inside inputs and textareas
  const obfuscateAttr = (match, attrName, value) => {
    if (value.startsWith('___STYLE_BLOCK_') || value.startsWith('___SCRIPT_BLOCK_')) return match;
    return `${attrName}="${obfuscateText(value)}"`;
  };

  processed = processed.replace(/<(input|textarea|select)[^>]*>/gi, (tagMatch) => {
    let tagProcessed = tagMatch;
    tagProcessed = tagProcessed.replace(/(placeholder)="([^"]*)"/gi, obfuscateAttr);
    tagProcessed = tagProcessed.replace(/(value)="([^"]*)"/gi, obfuscateAttr);
    tagProcessed = tagProcessed.replace(/(title)="([^"]*)"/gi, obfuscateAttr);
    return tagProcessed;
  });

  // Re-inject style and script blocks
  for (const block of blocks) {
    processed = processed.replace(block.placeholder, block.content);
  }

  return processed;
};

// Common head template
const head = (title) => `
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wireframe - ${title}</title>
  <link rel="stylesheet" href="wireframe.css">
  <style>
    .wf-placeholder-image {
      border: 1px dashed #000;
      background: #f2f2f2 url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%'><line x1='0' y1='0' x2='100%' y2='100%' stroke='%23999999' stroke-width='1.5'/><line x1='100%' y1='0' x2='0' y2='100%' stroke='%23999999' stroke-width='1.5'/></svg>") no-repeat center;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 120px;
      font-weight: bold;
    }
  </style>
</head>
`;

// Browser Bar component
const browserBar = (url) => `
<div class="wf-browser-bar">
  <div class="wf-browser-dots">
    <div class="wf-browser-dot"></div>
    <div class="wf-browser-dot"></div>
    <div class="wf-browser-dot"></div>
  </div>
  <div class="wf-browser-address">${url}</div>
</div>
`;

// Wrapper definitions
const standaloneWrapper = (url, title, content) => `<!DOCTYPE html>
<html lang="id">
${head(title)}
<body style="background-color: var(--wf-bg-shaded); min-height: 100vh;">
  ${browserBar(url)}
  <div style="padding: 40px 20px; display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 900px; display: flex; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border-radius: 8px; overflow: hidden; border: 1px solid #000; background: #fff;">
      ${content}
    </div>
  </div>
</body>
</html>`;

const dashboardWrapper = (url, title, role, activeItem, content) => {
  const sidebarItems = role === 'Tamu' 
    ? `
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'dashboard' ? 'active' : ''}">Dashboard</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'ruangan' ? 'active' : ''}">Daftar Ruangan</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link">Cek Ketersediaan</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link">Daftar Gedung</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link">Daftar Sarana</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'reservasi' ? 'active' : ''}">Reservasi Saya</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'profil' ? 'active' : ''}">Profil Saya</a></li>
    `
    : `
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'dashboard' ? 'active' : ''}">Beranda</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'users' ? 'active' : ''}">Kelola User</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'tamu' ? 'active' : ''}">Kelola Tamu</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'berita' ? 'active' : ''}">Kelola Berita</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'peminjaman' ? 'active' : ''}">Peminjaman</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'master' ? 'active' : ''}">Data Master</a></li>
      <li class="wf-nav-item"><a href="#" class="wf-nav-link ${activeItem === 'laporan' ? 'active' : ''}">Laporan</a></li>
    `;

  return `<!DOCTYPE html>
<html lang="id">
${head(title)}
<body>
  ${browserBar(url)}
  <div class="wf-header-main">
    <div class="wf-logo">[ LOGO ] SIPRASA</div>
    <div style="display: flex; align-items: center; gap: 15px;">
      <span>Selamat Datang, <strong>[ ${role} User ]</strong></span>
      <button class="wf-btn wf-btn-secondary">Keluar</button>
    </div>
  </div>
  <div class="wf-dashboard-layout">
    <div class="wf-sidebar">
      <ul class="wf-nav-list" style="margin-bottom: 20px;">
        ${sidebarItems}
      </ul>
      <div style="border-top: 1px solid #000; padding-top: 15px; margin-top: auto; font-size: 11px;">
        <p><strong>SIPRASA v1.0</strong></p>
        <p>Role: ${role}</p>
      </div>
    </div>
    <div class="wf-main-content">
      ${content}
    </div>
  </div>
  <div class="wf-footer-main">
    <p>&copy; 2026 UPT Asrama Haji Banjarmasin. [ Wireframe Version ]</p>
  </div>
</body>
</html>`;
};

const publicWrapper = (url, title, content) => `<!DOCTYPE html>
<html lang="id">
${head(title)}
<body>
  ${browserBar(url)}
  <div class="wf-header-main">
    <div class="wf-logo">[ LOGO ] SIPRASA</div>
    <div style="display: flex; gap: 20px; font-weight: bold;">
      <a href="#" style="text-decoration: none; color: #000;">Beranda</a>
      <a href="#" style="text-decoration: none; color: #666;">Fasilitas</a>
      <a href="#" style="text-decoration: none; color: #666;">Tentang Kami</a>
      <a href="#" style="text-decoration: none; color: #666;">Berita</a>
      <a href="#" style="text-decoration: none; color: #666;">FAQ</a>
    </div>
    <div style="display: flex; gap: 10px;">
      <button class="wf-btn wf-btn-secondary">Masuk</button>
      <button class="wf-btn wf-btn-primary">Daftar</button>
    </div>
  </div>
  <div class="wf-container">
    ${content}
  </div>
  <div class="wf-footer-main">
    <p>&copy; 2026 SIPRASA Asrama Haji Banjarmasin. [ Landing Page Public Wireframe ]</p>
  </div>
</body>
</html>`;

// The 26 Views matching the PDF layout with Sidebar wrapper and URL bar
const views = {
  "1_beranda": publicWrapper("http://siprasa.com/", "Halaman Beranda (Landing Page)", `
    <div class="wf-card" style="text-align: center; padding: 60px 20px; margin-top: 20px;">
      <h1 class="wf-title-lg">Tempat Menginap & Sewa Fasilitas Terpercaya di Banjarmasin</h1>
      <p style="margin-bottom: 25px; max-width: 600px; margin-left: auto; margin-right: auto;" class="wf-text-muted">
        Nikmati kamar nyaman, aula representatif, dan fasilitas lengkap berkelas. Reservasi online mudah, cepat, dan transparan.
      </p>
      <div style="display: flex; justify-content: center; gap: 15px;">
        <button class="wf-btn wf-btn-primary" style="padding: 12px 24px;">Booking Sekarang</button>
      </div>
    </div>
    
    <div class="wf-spacer"></div>
    
    <div class="wf-card" style="padding: 10px;">
      <div class="wf-placeholder-image" style="min-height: 350px;">
        [ ASRAMA HAJI EMBARKASI BANJARMASIN CAROUSEL ]
      </div>
      <div style="display: flex; justify-content: center; gap: 5px; margin-top: 10px;">
        <span style="width: 8px; height: 8px; background: #000; border-radius: 50%;"></span>
        <span style="width: 8px; height: 8px; background: #ccc; border-radius: 50%;"></span>
        <span style="width: 8px; height: 8px; background: #ccc; border-radius: 50%;"></span>
      </div>
    </div>
    
    <div class="wf-spacer"></div>
    
    <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
      <div class="wf-grid wf-col-4" style="text-align: center; font-size: 13px;">
        <div>
          <strong>Booking Online 24/7</strong><br>
          <span class="wf-text-muted">Reservasi kapan saja</span>
        </div>
        <div>
          <strong>Harga Transparan</strong><br>
          <span class="wf-text-muted">Tidak ada biaya tersembunyi</span>
        </div>
        <div>
          <strong>Pembayaran Aman</strong><br>
          <span class="wf-text-muted">QRIS, Transfer, Tunai</span>
        </div>
        <div>
          <strong>Fasilitas Premium</strong><br>
          <span class="wf-text-muted">Standar akomodasi internasional</span>
        </div>
      </div>
    </div>
    
    <div class="wf-spacer"></div>
    
    <h2 class="wf-title-md" style="text-align: center;">Pilihan Penginapan & Ruangan untuk Semua Kebutuhan</h2>
    <p class="wf-text-muted" style="text-align: center; margin-bottom: 20px;">Dari kamar standar hingga suite eksklusif, dari aula kecil hingga gedung pertemuan besar.</p>
    <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 20px;">
      <button class="wf-btn wf-btn-primary">Semua</button>
      <button class="wf-btn wf-btn-secondary">Kamar</button>
      <button class="wf-btn wf-btn-secondary">Aula & Gedung</button>
      <button class="wf-btn wf-btn-secondary">Fasilitas Umum</button>
    </div>
    
    <div class="wf-grid wf-col-3">
      <div class="wf-card">
        <div class="wf-placeholder-image" style="min-height: 150px; margin-bottom: 10px;">[ KAMAR STANDAR ]</div>
        <h3>Kamar Standar</h3>
        <p class="wf-text-muted" style="font-size: 12px; margin-bottom: 10px;">Kamar nyaman dengan AC, kamar mandi dalam, TV, wifi.</p>
        <button class="wf-btn wf-btn-secondary" style="width: 100%;">Pesan Sekarang</button>
      </div>
      <div class="wf-card">
        <div class="wf-placeholder-image" style="min-height: 150px; margin-bottom: 10px;">[ KAMAR VIP ]</div>
        <h3>Kamar VIP</h3>
        <p class="wf-text-muted" style="font-size: 12px; margin-bottom: 10px;">Suite eksklusif dengan furnitur premium, bathtub, ruang tamu.</p>
        <button class="wf-btn wf-btn-secondary" style="width: 100%;">Pesan Sekarang</button>
      </div>
      <div class="wf-card">
        <div class="wf-placeholder-image" style="min-height: 150px; margin-bottom: 10px;">[ AULA KECIL ]</div>
        <h3>Aula Kecil</h3>
        <p class="wf-text-muted" style="font-size: 12px; margin-bottom: 10px;">Ideal untuk rapat, seminar kecil, atau arisan keluarga.</p>
        <button class="wf-btn wf-btn-secondary" style="width: 100%;">Hubungi Kami</button>
      </div>
    </div>
  `),

  "2_login": standaloneWrapper("http://siprasa.com/login", "Halaman Login", `
    <div style="width: 40%; background: #000; color: #fff; padding: 40px; display: flex; flex-direction: column; justify-content: center; align-items: center; border-right: 1px solid #000;">
      <div class="wf-logo" style="margin-bottom: 20px; font-size: 28px; background: #fff; color: #000; padding: 15px 30px;">SIPRASA</div>
      <p style="text-align: center; font-size: 12px; line-height: 1.6; color: #ccc;">Sistem Informasi Peminjaman Sarana & Prasarana</p>
    </div>
    <div style="width: 60%; background: #fff; padding: 40px;">
      <h3 class="wf-title-md" style="font-size: 24px; margin-bottom: 5px;">Masuk ke Sistem</h3>
      
      <form onsubmit="return false;">
        <div class="wf-form-group">
          <label class="wf-label">EMAIL ATAU USERNAME</label>
          <input type="text" class="wf-input" placeholder="Email atau username">
        </div>
        
        <div class="wf-form-group">
          <label class="wf-label">PASSWORD</label>
          <input type="password" class="wf-input" placeholder="••••••••">
        </div>
        
        <div class="wf-form-group">
          <label class="wf-label">VERIFIKASI CAPTCHA</label>
          <div style="display: flex; gap: 10px; margin-bottom: 8px;">
            <div class="wf-placeholder-image" style="flex: 1; min-height: 44px;">771283</div>
            <button class="wf-btn wf-btn-secondary" type="button">Refresh</button>
          </div>
          <input type="text" class="wf-input" placeholder="Masukkan angka di atas">
        </div>
        
        <div style="text-align: right; margin-bottom: 15px; font-size: 12px;">
          <a href="#" style="color: #666; text-decoration: none;">Lupa password?</a>
        </div>
        
        <button class="wf-btn wf-btn-primary" style="width: 100%; padding: 12px; font-size: 14px;">Masuk</button>
        
        <div style="text-align: center; margin: 15px 0; color: #666; font-size: 12px;">atau</div>
        
        <button class="wf-btn wf-btn-secondary" style="width: 100%; padding: 12px; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px;">
          <span>Masuk dengan Google</span>
        </button>
      </form>
      
      <div style="text-align: center; margin-top: 25px; font-size: 13px;">
        Belum punya akun? <a href="#" style="font-weight: bold; color: #000;">Daftar</a>
      </div>
      <a href="1_beranda.html" style="display: block; text-align: center; margin-top: 15px; font-size: 12px; color: #666;">&larr; Kembali ke Beranda</a>
      <a href="#" style="display: block; text-align: center; margin-top: 8px; font-size: 12px; color: #25D366;">Hubungi Admin via WhatsApp</a>
    </div>
  `),

  "3_dashboard_tamu": dashboardWrapper("http://siprasa.com/tamu/dashboard", "Dashboard Tamu", "Tamu", "dashboard", `
    <h1 class="wf-title-lg">Dashboard Pengguna</h1>
    
    <div class="wf-card" style="background-color: #fcfcfc; border-left: 5px solid #000;">
      <h2 style="font-size: 16px; margin-bottom: 5px;">Selamat Datang, Rudi Hidayat!</h2>
      <p class="wf-text-muted" style="font-size: 13px;">Di portal layanan SIPRASA (Sistem Informasi Peminjaman Sarana & Prasarana). Kami berkomitmen memberikan kemudahan bagi Anda untuk mengelola profil pribadi, menelusuri ketersediaan gedung, ruangan, sarana pendukung, hingga melakukan pengajuan reservasi dengan cepat dan akurat.</p>
    </div>
    
    <div class="wf-grid wf-col-3" style="margin-top: 20px;">
      <div class="wf-card" style="text-align: center; background-color: var(--wf-bg-shaded);">
        <h2 class="wf-title-lg" style="margin: 5px 0;">8</h2>
        <p class="wf-text-muted" style="font-size: 11px; font-weight: bold; text-transform: uppercase;">JUMLAH GEDUNG</p>
      </div>
      <div class="wf-card" style="text-align: center; background-color: var(--wf-bg-shaded);">
        <h2 class="wf-title-lg" style="margin: 5px 0;">40</h2>
        <p class="wf-text-muted" style="font-size: 11px; font-weight: bold; text-transform: uppercase;">JUMLAH RUANGAN</p>
      </div>
      <div class="wf-card" style="text-align: center; background-color: var(--wf-bg-shaded);">
        <h2 class="wf-title-lg" style="margin: 5px 0;">12</h2>
        <p class="wf-text-muted" style="font-size: 11px; font-weight: bold; text-transform: uppercase;">JUMLAH SARANA</p>
      </div>
    </div>
    
    <div class="wf-grid wf-col-2" style="margin-top: 20px;">
      <div class="wf-card">
        <h3 class="wf-title-md">Kelola Profil Peminjam</h3>
        <p class="wf-text-muted" style="font-size: 12px; margin-bottom: 15px;">Lengkapi atau perbarui informasi data diri Anda seperti Nomor NIK, Nama Lengkap, Alamat, Golongan Darah, hingga pengaturan kata sandi baru untuk menjaga keamanan akun Anda.</p>
        <button class="wf-btn wf-btn-primary">Pengaturan Profil Saya</button>
      </div>
      <div class="wf-card">
        <h3 class="wf-title-md">Mulai Reservasi Ruangan</h3>
        <p class="wf-text-muted" style="font-size: 12px; margin-bottom: 15px;">Jelajahi berbagai tipe ruangan yang ditawarkan Asrama Haji, periksa kapasitas peserta, fasilitas yang ada di setiap ruangan, lalu buat pengajuan peminjaman baru secara instan.</p>
        <button class="wf-btn wf-btn-primary">+ Cari & Pesan Ruangan</button>
      </div>
    </div>
  `),

  "4_form_reservasi": dashboardWrapper("http://siprasa.com/tamu/reservasi/create", "Form Pengajuan Reservasi", "Tamu", "reservasi", `
    <div style="margin-bottom: 15px;">
      <button class="wf-btn wf-btn-secondary">&larr; Kembali ke Daftar Reservasi Saya</button>
    </div>
    
    <div class="wf-card">
      <h3 class="wf-title-md" style="border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 15px;">1. Pilih Ruangan & Fasilitas</h3>
      <div class="wf-grid wf-col-2">
        <div class="wf-form-group">
          <label class="wf-label">FILTER KATEGORI RUANGAN</label>
          <select class="wf-select">
            <option>Semua Kategori</option>
          </select>
        </div>
        <div class="wf-form-group">
          <label class="wf-label">PILIH RUANGAN *</label>
          <select class="wf-select">
            <option>Aula Akbar Multazam - Gedung Multazam (Lantai 1, Kapasitas: 800 Orang)</option>
          </select>
        </div>
      </div>
      
      <div style="background-color: var(--wf-bg-shaded); padding: 15px; border: 1px solid #000; margin-top: 15px;">
        <div class="wf-grid wf-col-4" style="font-size: 12px;">
          <div><span class="wf-text-muted">NAMA RUANGAN</span><br><strong>Aula Akbar Multazam</strong></div>
          <div><span class="wf-text-muted">KATEGORI / TIPE</span><br><strong>AULA</strong></div>
          <div><span class="wf-text-muted">GEDUNG</span><br><strong>Gedung Multazam</strong></div>
          <div><span class="wf-text-muted">KAPASITAS MAKSIMAL</span><br><strong>800 orang</strong></div>
        </div>
        <div style="margin-top: 15px;">
          <span class="wf-text-muted" style="font-size: 11px;">GALERI FOTO RUANGAN:</span>
          <div style="display: flex; gap: 10px; margin-top: 5px;">
            <div class="wf-placeholder-image" style="width: 100px; min-height: 60px;">[ FOTO 1 ]</div>
            <div class="wf-placeholder-image" style="width: 100px; min-height: 60px;">[ FOTO 2 ]</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="wf-card">
      <h3 class="wf-title-md" style="border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 15px;">2. Tanggal, Waktu & Paket Sewa</h3>
      <div class="wf-grid wf-col-3">
        <div class="wf-form-group">
          <label class="wf-label">TANGGAL MULAI PEMINJAMAN *</label>
          <input type="date" class="wf-input" value="2026-07-02">
        </div>
        <div class="wf-form-group">
          <label class="wf-label">WAKTU MULAI *</label>
          <select class="wf-select"><option>-- Pilih Jam Mulai --</option></select>
        </div>
        <div class="wf-form-group">
          <label class="wf-label">PILIH PAKET SEWA *</label>
          <select class="wf-select"><option>-- Pilih Paket Sewa --</option></select>
        </div>
      </div>
    </div>
    
    <div class="wf-card">
      <h3 class="wf-title-md" style="border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 15px;">3. Peminjaman Sarana Tambahan (Opsional)</h3>
      <div style="border: 1px solid #000; padding: 10px; background-color: #f9f9f9; font-size: 12px; margin-bottom: 15px;">
        Anda dapat meminjam sarana pendukung (seperti kursi lipat, meja, mikrofon, proyektor) yang tersedia. Jumlah peminjaman akan dibatasi oleh sistem sesuai jumlah stok yang ada di gudang.
      </div>
      <div class="wf-grid wf-col-8-4">
        <div class="wf-form-group">
          <label class="wf-label">PILIH SARANA *</label>
          <select class="wf-select"><option>-- Pilih Sarana --</option></select>
        </div>
        <div class="wf-form-group">
          <label class="wf-label">JUMLAH SEWA *</label>
          <div style="display: flex; gap: 10px;">
            <input type="number" class="wf-input" placeholder="Jumlah sewa">
            <button class="wf-btn wf-btn-secondary" style="color: red; border-color: red;">Hapus</button>
          </div>
        </div>
      </div>
      <button class="wf-btn wf-btn-secondary">+ Pinjam Sarana Tambahan</button>
    </div>
    
    <div class="wf-card">
      <h3 class="wf-title-md" style="border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 15px;">4. Detail Kegiatan & Data Kontak</h3>
      <div class="wf-form-group">
        <label class="wf-label">KEPERLUAN / TUJUAN PENGGUNAAN *</label>
        <textarea class="wf-textarea" rows="3" placeholder="Jelaskan keperluan penggunaan ruangan secara jelas (misalnya: Rapat koordinasi dinas, Diklat pengawas sekolah, dsb.)"></textarea>
        <small class="wf-text-muted">Minimal 10 karakter, maksimal 500 karakter.</small>
      </div>
      <div class="wf-grid wf-col-2">
        <div class="wf-form-group">
          <label class="wf-label">ESTIMASI JUMLAH PESERTA *</label>
          <input type="text" class="wf-input" placeholder="Contoh: 30">
          <small class="wf-text-muted">Maksimal kapasitas ruangan: 800 orang.</small>
        </div>
        <div class="wf-form-group">
          <label class="wf-label">NAMA PENANGGUNG JAWAB / KONTAK PERSON *</label>
          <input type="text" class="wf-input" value="Rudi Hidayat">
        </div>
      </div>
      <div class="wf-form-group">
        <label class="wf-label">NOMOR WHATSAPP / HP AKTIF *</label>
        <input type="text" class="wf-input" value="085955077954">
        <small class="wf-text-muted">Nomor kontak yang dapat dihubungi oleh admin untuk proses verifikasi lanjutan.</small>
      </div>
      
      <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
        <button class="wf-btn wf-btn-primary" style="padding: 12px 24px;">Kirim Pengajuan Reservasi Ruangan</button>
      </div>
    </div>
  `),

  "5_riwayat_peminjaman": dashboardWrapper("http://siprasa.com/tamu/reservasi", "Riwayat Peminjaman", "Tamu", "reservasi", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Reservasi Saya</h2>
        <button class="wf-btn wf-btn-primary">+ Buat Reservasi Baru</button>
      </div>
      
      <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <div>
          Tampilkan <select class="wf-select" style="width: 80px; display: inline-block;"><option>10</option></select> entri
        </div>
        <div>
          Cari: <input type="text" class="wf-input" style="width: 200px; display: inline-block;">
        </div>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>Ruangan</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Estimasi Peserta</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Executive Boardroom Shafa<br><small class="wf-text-muted">Gedung Shafa</small></td>
            <td>25/06/2026</td>
            <td>25/06/2026</td>
            <td>30 orang</td>
            <td><span class="wf-badge wf-badge-dark">Selesai</span></td>
            <td><button class="wf-btn">Detail</button></td>
          </tr>
          <tr>
            <td>Kamar Arafah 109<br><small class="wf-text-muted">Gedung Arafah</small></td>
            <td>18/06/2026</td>
            <td>19/06/2026</td>
            <td>4 orang</td>
            <td><span class="wf-badge wf-badge-shaded">Menunggu</span></td>
            <td><button class="wf-btn">Detail</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "6_profil_pengguna": dashboardWrapper("http://siprasa.com/tamu/profil/edit", "Profil Pengguna", "Tamu", "profil", `
    <div class="wf-card">
      <h2 class="wf-title-lg">Profil Pengguna</h2>
      
      <div class="wf-grid wf-col-8-4">
        <div>
          <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border: 2px solid #000; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; background: var(--wf-bg-shaded);">R</div>
            <h3>Rudi Hidayat</h3>
          </div>
          
          <form onsubmit="return false;">
            <div class="wf-form-group">
              <label class="wf-label">NIK / NOMOR IDENTITAS *</label>
              <input type="text" class="wf-input" value="6371051882840001">
            </div>
            <div class="wf-form-group">
              <label class="wf-label">NAMA LENGKAP *</label>
              <input type="text" class="wf-input" value="Rudi Hidayat">
            </div>
            <div class="wf-form-group">
              <label class="wf-label">JENIS KELAMIN *</label>
              <div style="display: flex; gap: 15px;">
                <label><input type="radio" checked> Laki-laki</label>
                <label><input type="radio"> Perempuan</label>
              </div>
            </div>
            <div class="wf-form-group">
              <label class="wf-label">NOMOR TELEPON / WHATSAPP *</label>
              <input type="text" class="wf-input" value="085955077954">
            </div>
            <div class="wf-form-group">
              <label class="wf-label">GOLONGAN DARAH (OPSIONAL)</label>
              <select class="wf-select"><option>-- Pilih Golongan Darah --</option></select>
            </div>
            <div class="wf-form-group">
              <label class="wf-label">ALAMAT LENGKAP</label>
              <textarea class="wf-textarea" rows="3">Jl. Diponegoro No. 25, Tanjung</textarea>
            </div>
            <button class="wf-btn wf-btn-primary">Simpan Perubahan</button>
          </form>
        </div>
        
        <div style="border-left: 1px solid #000; padding-left: 20px;">
          <h3 class="wf-title-md">Keamanan Akun</h3>
          <form onsubmit="return false;">
            <div class="wf-form-group">
              <label class="wf-label">PASSWORD BARU</label>
              <input type="password" class="wf-input" placeholder="Minimal 6 karakter">
            </div>
            <div class="wf-form-group">
              <label class="wf-label">KONFIRMASI PASSWORD BARU</label>
              <input type="password" class="wf-input" placeholder="Ulangi password baru">
            </div>
            <button class="wf-btn wf-btn-primary" style="width: 100%;">Ubah Password</button>
          </form>
        </div>
      </div>
    </div>
  `),

  "7_detail_invoice": dashboardWrapper("http://siprasa.com/tamu/invoice/INV/20260618/0103", "Detail Invoice", "Tamu", "reservasi", `
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
      <button class="wf-btn wf-btn-secondary">&larr; Kembali ke Peminjaman</button>
      <button class="wf-btn wf-btn-primary">Cetak Invoice</button>
    </div>
    
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #000; padding-bottom: 15px; margin-bottom: 15px;">
        <div>
          <h2 class="wf-title-md" style="margin-bottom: 0;">Invoice</h2>
        </div>
        <div>
          <strong>INV/20260618/0103</strong>
        </div>
      </div>
      
      <div class="wf-grid wf-col-2" style="margin-bottom: 15px;">
        <div>
          <small class="wf-text-muted">STATUS PEMBAYARAN</small><br>
          <span class="wf-badge wf-badge-dark" style="background-color: green; color: white;">Lunas</span>
        </div>
        <div>
          <small class="wf-text-muted">NOMOR INVOICE</small><br>
          <strong>INV/20260618/0103</strong>
        </div>
      </div>
      
      <div style="margin-bottom: 15px;">
        <button class="wf-btn wf-btn-secondary" style="color: red; border-color: red;">X Tandai Belum Bayar (UNPAID)</button>
      </div>
      
      <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Informasi Fasilitas</h4>
        <div class="wf-grid wf-col-2" style="font-size: 13px;">
          <div><small>NAMA FASILITAS</small><br><strong>Aula Makkah Kecil</strong></div>
          <div><small>TIPE RUANGAN</small><br><strong>AULA</strong></div>
          <div><small>GEDUNG</small><br><strong>Gedung Makkah</strong></div>
          <div><small>PAKET</small><br><strong>Sewa Harian Aula Makkah</strong></div>
        </div>
      </div>
      
      <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Informasi Peminjaman</h4>
        <div class="wf-grid wf-col-2" style="font-size: 13px;">
          <div><small>TANGGAL PEMINJAMAN</small><br><strong>18 June 2026</strong></div>
          <div><small>JAM MULAI</small><br><strong>07:00 WIB</strong></div>
          <div><small>DURASI PEMINJAMAN</small><br><strong>24 jam</strong></div>
          <div><small>KODE PEMINJAMAN</small><br><strong>PJM/20260618/8092</strong></div>
        </div>
      </div>
      
      <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Detail Biaya</h4>
        <table class="wf-table" style="font-size: 13px;">
          <tbody>
            <tr>
              <td>Subtotal</td>
              <td style="text-align: right;">Rp 1.500.000</td>
            </tr>
            <tr>
              <td>Biaya Tambahan</td>
              <td style="text-align: right; color: red;">Rp 0</td>
            </tr>
            <tr style="font-weight: bold;">
              <td>Total Harga</td>
              <td style="text-align: right;">Rp 1.500.000</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Informasi Sistem</h4>
        <div class="wf-grid wf-col-3" style="font-size: 12px;">
          <div><small>TANGGAL INVOICE</small><br><strong>04 June 2026 13:38</strong></div>
          <div><small>JATUH TEMPO</small><br><strong>11 June 2026 13:38</strong></div>
          <div><small>TANGGAL PEMBAYARAN</small><br><strong>14 June 2026 16:06</strong></div>
        </div>
      </div>
    </div>
  `),

  "8_dashboard_admin": dashboardWrapper("http://siprasa.com/admin/dashboard", "Dashboard Admin", "Admin", "dashboard", `
    <h1 class="wf-title-lg">Dashboard</h1>
    
    <div class="wf-grid wf-col-3" style="gap: 15px; margin-bottom: 20px;">
      <div class="wf-card" style="background-color: #ffc107; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">69</h2>
          <p style="font-size: 12px; font-weight: bold;">Total Pengguna</p>
        </div>
        <div>[ USER ICON ]</div>
      </div>
      <div class="wf-card" style="background-color: #17a2b8; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">64</h2>
          <p style="font-size: 12px; font-weight: bold;">Kelola Tamu</p>
        </div>
        <div>[ TAMU ICON ]</div>
      </div>
      <div class="wf-card" style="background-color: #6c757d; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">8</h2>
          <p style="font-size: 12px; font-weight: bold;">Kelola Gedung</p>
        </div>
        <div>[ GEDUNG ICON ]</div>
      </div>
    </div>
    
    <div class="wf-grid wf-col-3" style="gap: 15px; margin-bottom: 20px;">
      <div class="wf-card" style="background-color: #007bff; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">40</h2>
          <p style="font-size: 12px; font-weight: bold;">Kelola Ruangan</p>
        </div>
        <div>[ RUANGAN ICON ]</div>
      </div>
      <div class="wf-card" style="background-color: #28a745; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">12</h2>
          <p style="font-size: 12px; font-weight: bold;">Kelola Sarana</p>
        </div>
        <div>[ SARANA ICON ]</div>
      </div>
      <div class="wf-card" style="background-color: #e83e8c; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">40</h2>
          <p style="font-size: 12px; font-weight: bold;">Kelola Paket Ruangan</p>
        </div>
        <div>[ PAKET ICON ]</div>
      </div>
    </div>
    
    <div class="wf-grid wf-col-2" style="gap: 15px;">
      <div class="wf-card" style="background-color: #6f42c1; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">103</h2>
          <p style="font-size: 12px; font-weight: bold;">Peminjaman Ruangan</p>
        </div>
        <div>[ TRANSAKSI ICON ]</div>
      </div>
      <div class="wf-card" style="background-color: #28a745; display: flex; justify-content: space-between; align-items: center; padding: 25px;">
        <div>
          <h2 style="font-size: 36px; margin: 0;">8</h2>
          <p style="font-size: 12px; font-weight: bold;">Kelola Berita</p>
        </div>
        <div>[ BERITA ICON ]</div>
      </div>
    </div>
  `),

  "9_kelola_gedung": dashboardWrapper("http://siprasa.com/admin/gedung", "Kelola Gedung", "Admin", "master", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Gedung</h2>
        <button class="wf-btn wf-btn-primary">+ Tambah Gedung</button>
      </div>
      
      <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <div>
          Tampilkan <select class="wf-select" style="width: 80px; display: inline-block;"><option>10</option></select> data
        </div>
        <div>
          Cari: <input type="text" class="wf-input" style="width: 200px; display: inline-block;">
        </div>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th style="width: 50px; text-align: center;">No</th>
            <th>Nama Gedung</th>
            <th>Koordinat</th>
            <th>Keterangan</th>
            <th style="width: 120px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align: center;">1</td>
            <td>Gedung Marwah</td>
            <td>-3.3245, 114.5945</td>
            <td>Gedung Kantor, Administrasi, dan Ruang Rapat Staf.</td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
          <tr>
            <td style="text-align: center;">2</td>
            <td>Gedung Shafa</td>
            <td>-3.3240, 114.5940</td>
            <td>Gedung Khusus Meeting Eksekutif. Hanya berisi satu...</td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "10_form_gedung": dashboardWrapper("http://siprasa.com/admin/gedung/create", "Form Kelola Gedung", "Admin", "master", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px; display: flex; justify-content: space-between; align-items: center;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">+ Tambah Gedung</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Nama Gedung *</label>
            <input type="text" class="wf-input" placeholder="Masukkan nama gedung">
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Koordinat Map (Opsional)</label>
            <input type="text" class="wf-input" placeholder="Contoh: -6.200000, 106.816666">
          </div>
        </div>
        <div class="wf-form-group">
          <label class="wf-label">Keterangan (Opsional)</label>
          <textarea class="wf-textarea" rows="4" placeholder="Masukkan keterangan gedung"></textarea>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Tambah</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "11_kelola_ruangan": dashboardWrapper("http://siprasa.com/admin/ruangan", "Kelola Ruangan", "Admin", "master", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Ruangan</h2>
        <button class="wf-btn wf-btn-primary">+ Tambah Ruangan</button>
      </div>
      
      <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <div>
          Tampilkan <select class="wf-select" style="width: 80px; display: inline-block;"><option>10</option></select> data
        </div>
        <div>
          Cari: <input type="text" class="wf-input" style="width: 200px; display: inline-block;">
        </div>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Ruangan</th>
            <th>Gedung</th>
            <th>Tipe Ruangan</th>
            <th>Kapasitas</th>
            <th style="width: 120px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Ruang Rapat Marwah 2<br><small class="wf-text-muted">[ 2 Foto ]</small></td>
            <td>Gedung Marwah</td>
            <td>RUANG MEETING</td>
            <td>15 Orang</td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "12_form_ruangan": dashboardWrapper("http://siprasa.com/admin/ruangan/create", "Form Kelola Ruangan", "Admin", "master", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">+ Tambah Ruangan</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Gedung *</label>
            <select class="wf-select"><option>Pilih Gedung</option></select>
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Nama Ruangan *</label>
            <input type="text" class="wf-input" placeholder="Masukkan nama ruangan">
          </div>
        </div>
        
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Tipe Ruangan *</label>
            <select class="wf-select"><option>Pilih Tipe Ruangan</option></select>
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Lantai (Opsional)</label>
            <input type="text" class="wf-input" placeholder="Contoh: 1">
          </div>
        </div>
        
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Kapasitas (Orang) *</label>
            <input type="number" class="wf-input" value="1">
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Gender Policy (Opsional)</label>
            <select class="wf-select"><option>Pilih Kebijakan (Bebas)</option></select>
          </div>
        </div>
        
        <div class="wf-form-group">
          <label class="wf-label">Keterangan (Opsional)</label>
          <textarea class="wf-textarea" rows="3" placeholder="Masukkan keterangan ruangan"></textarea>
        </div>
        
        <div class="wf-form-group">
          <label class="wf-label">Foto Ruangan (Bisa pilih lebih dari satu)</label>
          <div class="wf-placeholder-image" style="min-height: 80px;">[ CHOOSE FILES ] No file chosen</div>
          <small class="wf-text-muted">Format: JPG, PNG, GIF (Max: 2MB/file). Bisa pilih banyak file sekaligus.</small>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Tambah</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "13_kelola_sarana": dashboardWrapper("http://siprasa.com/admin/sarana", "Kelola Sarana", "Admin", "master", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Sarana</h2>
        <button class="wf-btn wf-btn-primary">+ Tambah Sarana</button>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Sarana</th>
            <th>Kondisi</th>
            <th>Tgl Penerimaan</th>
            <th>Stok</th>
            <th>Stok Hari Ini</th>
            <th style="width: 120px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Podium Kayu Jati</td>
            <td><span class="wf-badge wf-badge-dark" style="background-color: green; color: white;">Baik Sekali</span></td>
            <td>02 Jan 2026</td>
            <td>4</td>
            <td>4</td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "14_form_sarana": dashboardWrapper("http://siprasa.com/admin/sarana/create", "Form Kelola Sarana", "Admin", "master", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">+ Tambah Sarana</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Nama Sarana *</label>
            <input type="text" class="wf-input" placeholder="Masukkan nama sarana">
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Kondisi *</label>
            <select class="wf-select"><option>Pilih Kondisi</option></select>
          </div>
        </div>
        
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Tanggal Penerimaan *</label>
            <input type="date" class="wf-input">
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Stok *</label>
            <input type="number" class="wf-input" placeholder="Masukkan stok (contoh: 10)">
          </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Tambah</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "15_kelola_paket_ruangan": dashboardWrapper("http://siprasa.com/admin/paket-ruangan", "Kelola Paket Ruangan", "Admin", "master", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Paket Ruangan</h2>
        <button class="wf-btn wf-btn-primary">+ Tambah Paket</button>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Paket</th>
            <th>Ruangan</th>
            <th>Gedung</th>
            <th>Durasi (Jam)</th>
            <th>Harga Sewa</th>
            <th>Status</th>
            <th style="width: 120px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Sewa Rapat Marwah 2 (4 Jam)</td>
            <td>Ruang Rapat Marwah 2</td>
            <td>Gedung Marwah</td>
            <td>4 Jam</td>
            <td>Rp 300.000,00</td>
            <td><span class="wf-badge wf-badge-dark" style="background-color: green; color: white;">Aktif</span></td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "16_form_paket_ruangan": dashboardWrapper("http://siprasa.com/admin/paket-ruangan/create", "Form Kelola Paket", "Admin", "master", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">+ Tambah Paket Ruangan</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Pilih Ruangan *</label>
            <select class="wf-select"><option>-- Pilih Ruangan --</option></select>
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Nama Paket *</label>
            <input type="text" class="wf-input" placeholder="Contoh: Paket Harian, Paket Jam-Jaman">
          </div>
        </div>
        
        <div class="wf-grid wf-col-3">
          <div class="wf-form-group">
            <label class="wf-label">Durasi Sewa (Jam - Opsional)</label>
            <input type="text" class="wf-input" placeholder="Contoh: 8 (Biarkan kosong jika fleksibel)">
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Harga Sewa (IDR) *</label>
            <div style="display: flex; gap: 5px; align-items: center;">
              <span>Rp</span>
              <input type="number" class="wf-input" placeholder="Contoh: 1500000">
            </div>
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Status *</label>
            <select class="wf-select"><option>Aktif</option></select>
          </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Tambah</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "17_kelola_pengguna": dashboardWrapper("http://siprasa.com/admin/users", "Kelola Pengguna", "Admin", "users", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Pengguna</h2>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Pengguna</th>
            <th>Alamat Email</th>
            <th>Role</th>
            <th style="width: 100px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>admin</td>
            <td>admin@asrama.local</td>
            <td><span class="wf-badge wf-badge-dark">Admin</span></td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "18_form_pengguna": dashboardWrapper("http://siprasa.com/admin/users/1/edit", "Form Kelola Pengguna", "Admin", "users", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">Edit Data Pengguna</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-form-group">
          <label class="wf-label">Nama Pengguna</label>
          <input type="text" class="wf-input" value="admin">
        </div>
        <div class="wf-form-group">
          <label class="wf-label">Alamat Email</label>
          <input type="email" class="wf-input" value="admin@asrama.local">
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Simpan</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "19_kelola_tamu": dashboardWrapper("http://siprasa.com/admin/tamu", "Kelola Tamu", "Admin", "tamu", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Tamu (Guest)</h2>
        <button class="wf-btn wf-btn-primary">+ Tambah Tamu</button>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th style="width: 120px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>6371051882840001</td>
            <td>Rudi Hidayat</td>
            <td>Laki-laki</td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "20_form_tamu": dashboardWrapper("http://siprasa.com/admin/tamu/create", "Form Kelola Tamu", "Admin", "tamu", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">Tambah Tamu</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">NIK *</label>
            <input type="text" class="wf-input" placeholder="Masukkan NIK">
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Nama Lengkap *</label>
            <input type="text" class="wf-input" placeholder="Nama Lengkap">
          </div>
        </div>
        
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Jenis Kelamin *</label>
            <select class="wf-select"><option>-- Pilih --</option></select>
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Golongan Darah</label>
            <input type="text" class="wf-input" placeholder="Contoh: A, B, O, AB">
          </div>
        </div>
        
        <div class="wf-form-group">
          <label class="wf-label">Alamat Lengkap</label>
          <textarea class="wf-textarea" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
        </div>
        
        <div class="wf-form-group">
          <label class="wf-label">Catatan Tambahan</label>
          <textarea class="wf-textarea" rows="2" placeholder="Catatan medis/khusus..."></textarea>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Simpan Perubahan</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "21_kelola_berita": dashboardWrapper("http://siprasa.com/admin/berita", "Kelola Berita", "Admin", "berita", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Daftar Berita</h2>
        <button class="wf-btn wf-btn-primary">+ Tambah Berita</button>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Pembuat</th>
            <th>Status</th>
            <th style="width: 120px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Panduan Pembayaran BPIH Calon Jamaah Haji</td>
            <td>admin</td>
            <td><span class="wf-badge wf-badge-shaded">Draft</span></td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "22_form_berita": dashboardWrapper("http://siprasa.com/admin/berita/create", "Form Kelola Berita", "Admin", "berita", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">+ Tambah Berita</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-form-group">
          <label class="wf-label">Judul *</label>
          <input type="text" class="wf-input" placeholder="Masukkan judul berita">
        </div>
        <div class="wf-form-group">
          <label class="wf-label">Isi Berita *</label>
          <textarea class="wf-textarea" rows="6" placeholder="Masukkan isi berita"></textarea>
        </div>
        
        <div class="wf-grid wf-col-2">
          <div class="wf-form-group">
            <label class="wf-label">Tanggal Publikasi *</label>
            <input type="date" class="wf-input">
          </div>
          <div class="wf-form-group">
            <label class="wf-label">Gambar *</label>
            <div class="wf-placeholder-image" style="min-height: 44px;">[ CHOOSE FILE ] No file chosen</div>
            <small class="wf-text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
          </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Tambah</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "23_kelola_landing_page": dashboardWrapper("http://siprasa.com/admin/landing-page/tentang", "Kelola Landing Page", "Admin", "master", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Kelola Tentang</h2>
        <button class="wf-btn wf-btn-primary">+ Tambah Data</button>
      </div>
      
      <table class="wf-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Isi</th>
            <th style="width: 120px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>alamat</td>
            <td>Jl. Landasan Ulin, Banjarbaru, Kalimantan Selatan</td>
            <td style="text-align: center;">
              <button class="wf-btn">Edit</button>
              <button class="wf-btn wf-btn-secondary">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `),

  "24_form_landing_page": dashboardWrapper("http://siprasa.com/admin/landing-page/faq/1/edit", "Form Landing Page", "Admin", "master", `
    <div class="wf-card" style="max-width: 700px; margin: 0 auto;">
      <div style="background-color: #000; color: #fff; padding: 14px 20px; border-radius: 4px 4px 0 0; margin: -20px -20px 20px -20px;">
        <h6 class="mb-0 fw-semibold" style="font-size: 15px;">Edit Data Tentang</h6>
      </div>
      
      <form onsubmit="return false;" style="margin-top: 20px;">
        <div class="wf-form-group">
          <label class="wf-label">Key *</label>
          <input type="text" class="wf-input" value="alamat">
        </div>
        <div class="wf-form-group">
          <label class="wf-label">Value *</label>
          <textarea class="wf-textarea" rows="4">Jl. Landasan Ulin, Banjarbaru, Kalimantan Selatan</textarea>
          <small class="wf-text-muted">Maksimal 10000 karakter</small>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button class="wf-btn wf-btn-primary">Simpan</button>
          <button class="wf-btn wf-btn-secondary">Kembali</button>
        </div>
      </form>
    </div>
  `),

  "25_validasi_peminjaman": dashboardWrapper("http://siprasa.com/admin/transaksi/PJM/20260618/1128", "Validasi Peminjaman", "Admin", "peminjaman", `
    <div style="margin-bottom: 15px;">
      <button class="wf-btn wf-btn-secondary">&larr; Kembali ke Daftar</button>
    </div>
    
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #000; padding-bottom: 15px; margin-bottom: 15px;">
        <h2 class="wf-title-md" style="margin-bottom: 0;">Detail Peminjaman</h2>
        <span class="wf-badge wf-badge-shaded" style="background-color: orange; color: white;">Menunggu Persetujuan</span>
      </div>
      
      <div style="border: 1px solid #000; padding: 10px; background-color: #f9f9f9; font-size: 12px; margin-bottom: 15px;">
        Peminjaman ini belum disetujui. Silakan review dan ambil keputusan di bawah.
      </div>
      
      <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Informasi Tamu/Peminjam</h4>
        <div class="wf-grid wf-col-2" style="font-size: 13px;">
          <div><small>NAMA TAMU/PEMINJAM</small><br><strong>Rudi Hidayat</strong></div>
          <div><small>NIK</small><br><strong>6371051882840001</strong></div>
          <div><small>JENIS KELAMIN</small><br><strong>Laki-laki</strong></div>
          <div><small>ALAMAT</small><br><strong>Jl. Diponegoro No. 26, Tanjung</strong></div>
        </div>
      </div>
      
      <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Informasi Fasilitas</h4>
        <div class="wf-grid wf-col-2" style="font-size: 13px;">
          <div><small>NAMA FASILITAS</small><br><strong>Kamar Arafah 109</strong></div>
          <div><small>TIPE RUANGAN</small><br><strong>KAMAR STANDAR</strong></div>
          <div><small>GEDUNG</small><br><strong>Gedung Arafah</strong></div>
          <div><small>PAKET</small><br><strong>Sewa Harian Arafah</strong></div>
        </div>
      </div>
      
      <div class="wf-card" style="background-color: var(--wf-bg-shaded);">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Informasi Peminjaman</h4>
        <div class="wf-grid wf-col-2" style="font-size: 13px;">
          <div><small>KODE PEMINJAMAN</small><br><strong>PJM/20260618/1128</strong></div>
          <div><small>TANGGAL PEMINJAMAN</small><br><strong>18 June 2026</strong></div>
          <div><small>JAM MULAI</small><br><strong>11:00 WIB</strong></div>
          <div><small>DURASI</small><br><strong>24 jam</strong></div>
        </div>
        <div style="margin-top: 10px; font-size: 13px;">
          <small>KETERANGAN</small><br>
          <strong>diklat pengajar</strong>
        </div>
      </div>
      
      <div style="display: flex; gap: 10px; margin-top: 20px;">
        <button class="wf-btn wf-btn-primary" style="background-color: green; color: white;">Setujui</button>
        <button class="wf-btn" style="background-color: red; color: white; border: none;">X Tolak</button>
        <button class="wf-btn" style="background-color: orange; color: white; border: none;">Lihat Invoice</button>
      </div>
      
      <!-- Modal setujui peminjaman popup mockup -->
      <div style="border: 1px dashed #000; padding: 20px; margin-top: 30px; background-color: var(--wf-bg-main);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 15px;">
          <h4 style="font-weight: bold; margin-bottom: 0;">Setujui Peminjaman</h4>
          <span>X</span>
        </div>
        <div class="wf-form-group">
          <label class="wf-label">CATATAN (OPSIONAL)</label>
          <textarea class="wf-textarea" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
          <small class="wf-text-muted">Maksimal 10000 karakter</small>
        </div>
        <div class="wf-form-group">
          <label class="wf-label">BIAYA TAMBAHAN (IDR - OPSIONAL)</label>
          <input type="text" class="wf-input" placeholder="Contoh: 50000">
          <small class="wf-text-muted">Isi jika ada biaya tambahan untuk sarana pendukung</small>
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
          <button class="wf-btn wf-btn-secondary">Batal</button>
          <button class="wf-btn wf-btn-primary" style="background-color: green; color: white;">Setujui</button>
        </div>
      </div>
    </div>
  `),

  "26_statistik_laporan": dashboardWrapper("http://siprasa.com/admin/laporan", "Statistik & Laporan", "Admin", "laporan", `
    <div class="wf-card">
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #000; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 class="wf-title-lg" style="margin-bottom: 0;">Laporan & Statistik Peminjaman</h2>
        <button class="wf-btn wf-btn-primary" style="background-color: orange; color: white;">Ekspor Excel</button>
      </div>
      
      <div class="wf-grid wf-col-4" style="margin-bottom: 20px;">
        <div class="wf-card" style="padding: 15px; background: #e3f2fd; display: flex; justify-content: space-between; align-items: center;">
          <div>
            <small class="wf-text-muted">TOTAL TRANSAKSI</small>
            <h2 style="font-size: 28px; margin: 5px 0;">103</h2>
          </div>
          <div>[ ICON ]</div>
        </div>
        <div class="wf-card" style="padding: 15px; background: #e8f5e9; display: flex; justify-content: space-between; align-items: center;">
          <div>
            <small class="wf-text-muted">DISETUJUI (APPROVED)</small>
            <h2 style="font-size: 28px; margin: 5px 0;">92</h2>
          </div>
          <div>[ ICON ]</div>
        </div>
        <div class="wf-card" style="padding: 15px; background: #fff3e0; display: flex; justify-content: space-between; align-items: center;">
          <div>
            <small class="wf-text-muted">MENUNGGU (PENDING)</small>
            <h2 style="font-size: 28px; margin: 5px 0;">8</h2>
          </div>
          <div>[ ICON ]</div>
        </div>
        <div class="wf-card" style="padding: 15px; background: #eceff1; display: flex; justify-content: space-between; align-items: center;">
          <div>
            <small class="wf-text-muted">TOTAL PENDAPATAN</small>
            <h2 style="font-size: 20px; margin: 12px 0 5px 0; font-weight: bold;">Rp 60.550.000</h2>
          </div>
          <div>[ ICON ]</div>
        </div>
      </div>
      
      <div class="wf-card">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Statistik Transaksi Bulanan (12 Bulan Terakhir)</h4>
        <div class="wf-placeholder-image" style="min-height: 250px;">
          [ DIAGRAM BATANG TREN SEWA BULANAN ]
        </div>
      </div>
    </div>
  `)
};

// Generate files
Object.entries(views).forEach(([filename, htmlContent]) => {
  const filepath = path.join(outDir, `${filename}.html`);
  const obfuscatedContent = obfuscateHTML(htmlContent.trim());
  fs.writeFileSync(filepath, obfuscatedContent);
  console.log(`Generated and Obfuscated: ${filepath}`);
});

console.log("All 26 Wireframes generated and obfuscated successfully according to PDF with browser address bar and layouts.");
