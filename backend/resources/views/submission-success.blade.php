<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title }} | BHP Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=129">
    @include('partials.pwa-head')
</head>
<body class="submission-success-page">
    <main class="submission-success-layout">
        <section class="submission-success-card" role="status" aria-live="polite">
            <span class="submission-success-mark" aria-hidden="true">✓</span>
            <h1>{{ $title }}</h1>
            <p>{{ $message }}</p>
            @if ($ticketNumber)
                <div class="submission-success-ticket"><span>Nomor pengaduan</span><strong>{{ $ticketNumber }}</strong><small>Simpan nomor ini sebagai referensi.</small></div>
            @endif
            <a class="button button-primary submission-success-home" href="{{ route('home') }}">Kembali ke Beranda</a>
        </section>
    </main>
</body>
</html>
