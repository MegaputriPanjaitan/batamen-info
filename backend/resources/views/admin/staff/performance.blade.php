<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performa {{ $staffMember->name }} | Admin BHP Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=124">
    @include('partials.pwa-head')
</head>
<body class="admin-shell staff-performance-page">
    @include('admin.partials.header')
    <main class="admin-main">
        @include('admin.partials.back-button', ['backUrl' => route('admin.surveys.index')])
        <div class="staff-performance-overview">
        <section class="staff-profile-heading">
            @include('admin.partials.staff-photo', ['staffMember' => $staffMember, 'class' => 'staff-profile-avatar'])
            <div><span class="kicker">Performa petugas</span><h1>{{ $staffMember->name }}</h1><p>{{ $staffMember->position }}</p></div>
        </section>

        <section class="staff-summary" aria-label="Ringkasan performa petugas">
            <article><span>Total Survei</span><strong>{{ $staffMember->survey_responses_count }}</strong></article>
            <article><span>Rata-rata Keseluruhan</span><strong>{{ $staffMember->ratings_avg_score ? number_format($staffMember->ratings_avg_score, 2, ',', '.') : '—' }} <small>/ 5</small></strong></article>
        </section>
        </div>

        <div class="staff-chart-grid">
            <section class="admin-panel chart-panel">
                <div class="panel-heading"><div><span class="kicker">Enam bulan terakhir</span><h2>Tren Nilai Petugas</h2></div></div>
                <div class="chart-wrap"><canvas id="staff-trend-chart" role="img" aria-label="Grafik tren nilai bulanan {{ $staffMember->name }}"></canvas></div>
            </section>
            <section class="admin-panel chart-panel">
                <div class="panel-heading"><div><span class="kicker">Lima aspek pelayanan</span><h2>Nilai per Kategori</h2></div></div>
                <div class="chart-wrap"><canvas id="staff-category-chart" role="img" aria-label="Grafik nilai kategori {{ $staffMember->name }}"></canvas></div>
            </section>
        </div>
    </main>
    <script id="staff-monthly-data" type="application/json">@json($monthlyPerformance)</script>
    <script id="staff-category-data" type="application/json">@json($categoryPerformance)</script>
    <script src="{{ asset('assets/staff-performance.js') }}?v=51" defer></script>
    <script src="{{ asset('assets/pwa.js') }}?v=51" defer></script>
</body>
</html>
