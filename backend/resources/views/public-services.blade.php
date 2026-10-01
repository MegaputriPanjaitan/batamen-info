<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta name="description" content="Informasi delapan layanan publik Balai Harta Peninggalan Medan">
  <title>Layanan Publik | BHP Medan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=138">
  @include('partials.pwa-head')
</head>
<body class="inner-page building-background-page interactive-service-page public-services-page">
  @include('partials.inner-header', ['backUrl' => route('home'), 'backLabel' => 'Kembali'])
  <main>
    <section class="page-hero service-form-hero"><div class="container"><div class="service-hero-copy">
      <span class="kicker page-hero-kicker">Layanan Publik BHP Medan</span>
      <h1>Temukan layanan yang Anda perlukan</h1>
      <p>Pilih jenis layanan untuk membuka halaman khusus berisi persyaratan dokumen, biaya, dan alur pengurusan.</p>
    </div></div></section>
    <section class="page-content public-services-content"><div class="container">
      <div class="public-service-list">
        @foreach($services as $slug => $service)
          <a class="public-service-card" href="{{ route('public-services.show', $slug) }}">
            <span class="public-service-number">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span>
            <span class="public-service-card-copy"><strong>{{ $service['name'] }}</strong><em>{{ $service['description'] }}</em></span>
            <span class="public-service-open" aria-hidden="true">›</span>
          </a>
        @endforeach
      </div>
      <p class="public-service-source">Sumber: SK Kepala BHP Medan Nomor W.2.AHU.AHU.1-UM.01.01-523 Tahun 2026 tentang Standar Pelayanan.</p>
    </div></section>
  </main>
  <script src="{{ asset('assets/script.js') }}?v=123"></script>
  <script src="{{ asset('assets/pwa.js') }}?v=125" defer></script>
</body>
</html>
