<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pusat Informasi | BHP Medan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=129">
  @include('partials.pwa-head')
</head>
<body class="inner-page building-background-page interactive-information-page">
  @include('partials.inner-header')
  <main>
    <section class="page-content section">
      <div class="container">
        <div class="information-panel">
          <div class="information-heading">
            <div>
              <span class="kicker">Pusat Informasi Pelayanan Publik</span>
              <h2>Informasi publik dalam satu akses</h2>
              <p>Temukan dokumen akuntabilitas, kanal resmi, dan kontak BHP Medan dengan lebih cepat.</p>
            </div>
          </div>

          <div class="information-section-title document-section-title">
            <span>Dokumen publik</span>
          </div>
          <div class="document-links" aria-label="Dokumen informasi publik">
            <a class="document-button light-document" href="https://online.fliphtml5.com/bhpmedan/SK-STANDAR-PELAYANAN-2026/" target="_blank" rel="noopener"><span class="document-icon">▣</span><span>SK Standar Pelayanan BHP Medan</span><b>↗</b></a>
            <a class="document-button blue-document" href="https://online.fliphtml5.com/bhpmedan/LKJIP-2025-pFVN/" target="_blank" rel="noopener"><span class="document-icon">▤</span><span>LKjIP T.A. 2025</span><b>↗</b></a>
            <a class="document-button blue-document" href="https://online.fliphtml5.com/bhpmedan/ghrb/" target="_blank" rel="noopener"><span class="document-icon">✎</span><span>DIPA T.A. 2026</span><b>↗</b></a>
            <a class="document-button blue-document" href="https://online.fliphtml5.com/bhpmedan/RENCANA-AKSI-BHP-TAHUN-2026/" target="_blank" rel="noopener"><span class="document-icon">▰</span><span>Rencana Aksi T.A. 2026</span><b>↗</b></a>
            <a class="document-button gold-document" href="https://online.fliphtml5.com/bhpmedan/RENSTRA-BHP-MEDAN-2025-2029_rev01/" target="_blank" rel="noopener"><span class="document-icon">▦</span><span>Rencana Strategis 2025–2029</span><b>↗</b></a>
            <a class="document-button gold-document" href="https://online.fliphtml5.com/bhpmedan/Dokumen-Perjanjian-Kinerja/" target="_blank" rel="noopener"><span class="document-icon">▧</span><span>Perjanjian Kinerja 2026</span><b>↗</b></a>
            <a class="document-button gold-document" href="https://online.fliphtml5.com/bhpmedan/rmkh/" target="_blank" rel="noopener"><span class="document-icon">◫</span><span>Manual IKU Kemenkum 2025–2029</span><b>↗</b></a>
          </div>

          <div class="information-channels">
            <div class="information-section-title">
              <span>Terhubung dengan kami</span>
              <p>Ikuti informasi dan pembaruan terbaru melalui kanal resmi.</p>
            </div>
            <div class="social-links" aria-label="Media sosial BHP Medan">
              <a class="channel-button facebook" href="https://www.facebook.com/share/1WzaEvYy4q/" target="_blank" rel="noopener" aria-label="Facebook" title="Facebook"><img src="https://cdn.simpleicons.org/facebook/ffffff" alt=""></a>
              <a class="channel-button x-social" href="https://x.com/bhpmedan" target="_blank" rel="noopener" aria-label="X" title="X"><img src="https://cdn.simpleicons.org/x/ffffff" alt=""></a>
              <a class="channel-button tiktok" href="https://www.tiktok.com/@bhpmedan_kemenkum?_r=1&amp;_t=ZS-94AuYqDmARG" target="_blank" rel="noopener" aria-label="TikTok" title="TikTok"><img src="https://cdn.simpleicons.org/tiktok/ffffff" alt=""></a>
              <a class="channel-button instagram" href="https://www.instagram.com/bhpmedan_kemenkum?igsh=MWd1c3h5amFwa3lmZQ==" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram"><img src="https://cdn.simpleicons.org/instagram/ffffff" alt=""></a>
              <a class="channel-button youtube" href="https://youtube.com/@bhpmedankemenkum?si=THy4diMTvTbtlkxG" target="_blank" rel="noopener" aria-label="YouTube" title="YouTube"><img src="https://cdn.simpleicons.org/youtube/ffffff" alt=""></a>
              <a class="channel-button threads" href="https://www.threads.com/@bhpmedan_kemenkum" target="_blank" rel="noopener" aria-label="Threads" title="Threads"><img src="https://cdn.simpleicons.org/threads/ffffff" alt=""></a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <a class="floating-whatsapp-button" href="https://wa.me/6281361444551" target="_blank" rel="noopener" aria-label="Hubungi BHP Medan melalui WhatsApp" title="Hubungi melalui WhatsApp">
    <img src="https://cdn.simpleicons.org/whatsapp/ffffff" alt="" aria-hidden="true">
  </a>

  <script src="{{ asset('assets/script.js') }}?v=122"></script>
  <script src="{{ asset('assets/pwa.js') }}?v=89" defer></script>
</body>
</html>
