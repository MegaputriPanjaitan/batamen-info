<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta name="description" content="Persyaratan, tarif, dan alur {{ $service['name'] }} BHP Medan">
  <title>{{ $service['name'] }} | BHP Medan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=140">
  @include('partials.pwa-head')
</head>
<body class="inner-page building-background-page interactive-service-page public-service-detail-page">
  @include('partials.inner-header', ['backUrl' => route('public-services.index'), 'backLabel' => 'Kembali'])
  <main>
    <section class="page-hero service-form-hero"><div class="container"><div class="service-hero-copy">
      <span class="kicker page-hero-kicker">Standar Pelayanan BHP Medan</span>
      <h1>{{ $service['name'] }}</h1>
      <p>{{ $service['description'] }}</p>
    </div></div></section>
    <section class="page-content public-service-detail-content"><div class="container">
      <div class="service-detail-grid">
        <article class="service-detail-panel"><div class="service-detail-heading"><span class="service-detail-index">01</span><h2>Dokumen persyaratan</h2></div><ol>@foreach($service['requirements'] as $requirement)<li>{{ $requirement }}</li>@endforeach</ol></article>
        <article class="service-detail-panel service-detail-tariff"><div class="service-detail-heading"><span class="service-detail-index">02</span><h2>Biaya dan tarif</h2></div><ul>@foreach($service['tariffs'] as $tariff)<li>{{ $tariff }}</li>@endforeach</ul></article>
        <article class="service-detail-panel service-detail-flow"><div class="service-detail-heading"><span class="service-detail-index">03</span><h2>Alur pengurusan</h2></div><ol>@foreach($service['procedure'] as $step)<li>{{ $step }}</li>@endforeach</ol></article>
      </div>
    </div></section>
  </main>
  <script src="{{ asset('assets/script.js') }}?v=123"></script>
  <script src="{{ asset('assets/pwa.js') }}?v=125" defer></script>
</body>
</html>
