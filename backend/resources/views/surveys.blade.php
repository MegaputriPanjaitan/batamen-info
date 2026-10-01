<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Survei | BHP Medan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=153">
  @include('partials.pwa-head')
</head>
<body class="inner-page building-background-page interactive-service-page survey-landing-page">
  @include('partials.inner-header', ['backUrl' => route('home').'#layanan', 'backLabel' => 'Kembali'])
  <main class="survey-page">
    <section class="page-hero service-form-hero survey-page-hero"><div class="container"><div class="service-hero-copy"><span class="kicker page-hero-kicker">Survei Layanan</span><h1>Pendapat Anda, dasar kami untuk <span class="survey-title-accent">melayani lebih baik</span></h1><p>Berikan penilaian secara jujur berdasarkan pengalaman Anda. Setiap tanggapan membantu kami mengevaluasi dan meningkatkan mutu pelayanan.</p></div></div></section>
    <section class="page-content survey-page-content"><div class="container"><div class="survey-choice-panel">
      <div class="survey-options">
        <div class="survey-option survey-soon" aria-disabled="true"><span class="survey-option-number">01</span><div><small>Segera hadir</small><h3>Survei Tuntas Waris</h3><p>Layanan sedang dipersiapkan.</p></div><span class="soon-badge">SOON</span></div>
        <a class="survey-option" href="{{ route('spkp-spak.access') }}"><span class="survey-option-number">02</span><div><small>Survei eksternal</small><h3>Survei SPAK</h3><p>Buka survei pada tautan resmi.</p></div><b>↗</b></a>
        <a class="survey-option" href="{{ route('staff-surveys.create') }}"><span class="survey-option-number">03</span><div><small>Penilaian langsung</small><h3>Survei Petugas Layanan</h3><p>Pilih petugas dan beri penilaian.</p></div><b>↗</b></a>
        <a class="survey-option" href="{{ route('internal-surveys.login') }}"><span class="survey-option-number">04</span><div><small>Survei internal</small><h3>Survei Integritas</h3><p>Login menggunakan NIP pegawai.</p></div><b>↗</b></a>
      </div>
    </div></div></section>
  </main>
  <dialog class="service-dialog" data-internal-survey-dialog @if(session('open_internal_survey_login')) data-open-on-load @endif>
    <form class="service-dialog-card" method="POST" action="{{ route('internal-surveys.access') }}">
      @csrf
      <button class="service-dialog-close" type="button" data-dialog-close aria-label="Tutup">&times;</button>
      <span class="service-dialog-icon" aria-hidden="true">ID</span>
      <div class="service-dialog-copy"><small>KHUSUS PEGAWAI</small><h2>Login Survei Integritas</h2><p>Masukkan NIP untuk melanjutkan ke survei internal.</p></div>
      <label class="service-dialog-field"><span>NIP</span><input type="text" name="nip" inputmode="numeric" autocomplete="username" maxlength="20" placeholder="Masukkan NIP pegawai" required autofocus></label>
      @if($errors->internalSurvey->any())<p class="service-dialog-error" role="alert">{{ $errors->internalSurvey->first('nip') }}</p>@endif
      <div class="service-dialog-actions"><button class="button button-secondary" type="button" data-dialog-close>Batal</button><button class="button button-primary" type="submit">Lanjutkan <span>→</span></button></div>
    </form>
  </dialog>
  <script src="{{ asset('assets/script.js') }}?v=114"></script>
  <script src="{{ asset('assets/pwa.js') }}?v=53" defer></script>
</body>
</html>
