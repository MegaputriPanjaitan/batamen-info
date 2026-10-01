<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="Layanan utama Balai Harta Peninggalan Medan">
    <title>PASTI Batamen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=150">
    @include('partials.pwa-head')
</head>
<body class="home-app-page">
    @include('partials.site-header')
    <main class="app-launcher" aria-label="Layanan utama PASTI Batamen">
        <div class="app-launcher-content">
            <header class="app-welcome">
                <h1>Portal Akses Sistem Informasi BHP Medan</h1>
                <p>Layanan publik terintegrasi dan transparan</p>
                <div class="operational-notice" role="note" aria-label="Jam operasional BHP Medan">
                    <span class="operational-notice-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M3 10v4h3l4 4V6l-4 4H3Zm9-3.5v11l6 2.5V4l-6 2.5Zm7.5 2.09v6.82a3.5 3.5 0 0 0 0-6.82Z"/></svg>
                    </span>
                    <div class="operational-notice-copy">
                        <strong>Jam operasional</strong>
                        <div class="operational-notice-schedule">
                            <p>Senin–Kamis 08.00–16.00 WIB</p>
                            <p>Jumat 08.00–16.30 WIB</p>
                        </div>
                    </div>
                    <span class="operational-notice-arrow" aria-hidden="true"></span>
                </div>
            </header>
            <nav class="app-service-grid" aria-label="Pilih layanan">
            <a class="app-service-button" href="{{ route('public-services.index') }}">
                <span class="app-service-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z"/></svg></span>
                <span>Layanan Publik</span>
            </a>
            <a class="app-service-button" href="{{ route('information.index') }}">
                <span class="app-service-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 3h11a3 3 0 0 1 3 3v15H8a3 3 0 0 1-3-3V3Zm3 2v11.17c.31-.11.65-.17 1-.17h8V6a1 1 0 0 0-1-1H8Zm1 13a1 1 0 0 0 0 2h8v-2H9Z"/></svg></span>
                <span>Sistem Informasi Pelayanan Publik</span>
            </a>
            <a class="app-service-button" href="{{ route('surveys.index') }}">
                <span class="app-service-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 3h6a2 2 0 0 1 2 2h2a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2a2 2 0 0 1 2-2Zm0 3v1h6V5H9v1Zm-2 5v2h2v-2H7Zm4 0v2h6v-2h-6Zm-4 5v2h2v-2H7Zm4 0v2h6v-2h-6Z"/></svg></span>
                <span>Survei Layanan</span>
            </a>
            <a class="app-service-button" href="{{ route('complaints.create') }}">
                <span class="app-service-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20Zm-1 5v7h2V7h-2Zm0 9v2h2v-2h-2Z"/></svg></span>
                <span>Layanan Pengaduan Masyarakat</span>
            </a>
            </nav>
            <section class="pwa-install-panel" data-pwa-install-panel hidden aria-live="polite">
                <button class="pwa-install-icon" type="button" data-pwa-install disabled aria-label="Pasang PASTI Batamen" title="Pasang PASTI Batamen">
                    <svg viewBox="0 0 24 24"><path d="M11 3h2v10.17l3.59-3.58L18 11l-6 6-6-6 1.41-1.41L11 13.17V3Zm-6 16h14v2H5v-2Z"/></svg>
                </button>
                <div class="pwa-install-copy">
                    <strong>Pasang PASTI Batamen</strong>
                    <span data-pwa-install-status>Instal aplikasi dari browser untuk akses yang lebih cepat.</span>
                </div>
                <div class="pwa-ios-instructions" data-pwa-ios-instructions hidden>
                    Di Safari, tekan <strong>Bagikan</strong>, lalu pilih <strong>Tambahkan ke Layar Utama</strong>.
                </div>
            </section>
        </div>
    </main>
    <aside class="welcome-mascot" aria-hidden="true">
        <div class="mascot-button mascot-static" aria-hidden="true">
            <img class="mascot-base-image" src="{{ asset('assets/maskot-bhp-transparent.png') }}?v=98" alt="Maskot lebah BHP Medan">
        </div>
    </aside>
    <a class="mascot-batamen-icon" href="https://www.batamen.com/" aria-label="Buka Batamen" title="Buka Batamen">
        <img src="{{ asset('assets/logo-bhp-medan.png') }}" alt="Logo Pengayoman">
    </a>
    <script src="{{ asset('assets/script.js') }}?v=143"></script>
    <script src="{{ asset('assets/pwa.js') }}?v=149" defer></script>
</body>
</html>
