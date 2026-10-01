<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | BHP Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=118">
    @include('partials.pwa-head')
</head>
<body class="admin-shell admin-dashboard-page">
    @include('admin.partials.header')
    <main class="admin-main">
        @include('admin.partials.back-button', ['backUrl' => route('home'), 'label' => 'Kembali ke website'])
        <section class="dashboard-overview-grid">
            <div class="admin-heading admin-welcome"><div><span class="kicker">Ringkasan layanan</span><h1>Dashboard Admin</h1><p>Pantau pengaduan dan survei pelayanan petugas dalam satu tempat.</p></div><time>{{ now()->translatedFormat('d F Y') }}</time></div>
        </section>

        <section class="dashboard-primary-metrics" aria-label="Statistik utama">
            <a href="{{ route('admin.complaints.index') }}"><article><span>Total Pengaduan</span><strong>{{ $statistics['complaints'] }}</strong><small>Laporan terkirim</small></article></a>
            <a href="{{ route('admin.surveys.index') }}"><article><span>Total Survei Petugas</span><strong>{{ $statistics['surveys'] }}</strong><small>Survei terkirim</small></article></a>
            <article><span>Total SPKP / SPAK</span><strong>{{ $statistics['spkp_spak'] }}</strong><small>Akses layanan</small></article>
            <article><span>Total Survei Integritas</span><strong>{{ $statistics['internal_integrity'] }}</strong><small>Akses layanan</small></article>
            <a href="{{ route('admin.surveys.index') }}"><article><span>Rata-rata Nilai Petugas</span><strong>{{ number_format($statistics['average_rating'], 2, ',', '.') }} <small>/ 5</small></strong><small>Lihat peringkat petugas</small></article></a>
        </section>

        <section class="admin-panel chart-panel">
            <div class="panel-heading"><div><h2>Jumlah Pengaduan dalam Enam Bulan Terakhir</h2></div><div class="chart-legend"><span class="legend-complaint">Pengaduan</span></div></div>
            <div class="chart-wrap">
                <canvas id="service-trend-chart" role="button" tabindex="0" aria-label="Grafik batang jumlah pengaduan selama enam bulan terakhir. Tekan salah satu batang untuk melihat datanya."></canvas>
                <div class="chart-point-detail" id="service-trend-detail" role="status" aria-live="polite" hidden>
                    <div><span>Periode</span><strong data-chart-period>-</strong></div>
                    <div class="chart-detail-complaint"><span>Pengaduan</span><strong data-chart-complaints>0</strong></div>
                </div>
            </div>
        </section>

        <section class="admin-panel dashboard-recent-panel">
            <div class="panel-heading"><h2>Survei Petugas Terbaru</h2><a href="{{ route('admin.surveys.index') }}">Lihat seluruh survei →</a></div>
            <div class="table-scroll"><table class="admin-table"><thead><tr><th>No.</th><th>Tanggal</th><th>Nomor HP</th><th>Petugas</th><th>Jenis Layanan</th><th>Rata-rata</th><th></th></tr></thead><tbody>
                @forelse($recentSurveys as $survey)
                    <tr><td>{{ $loop->iteration }}</td><td>{{ $survey->created_at->format('d/m/Y H:i') }}</td><td>{{ $survey->respondent_phone ?: '—' }}</td><td><strong>{{ $survey->staffMember->name }}</strong></td><td>{{ config("public_services.{$survey->service_slug}.name", $survey->service_slug ?: '—') }}</td><td>{{ number_format((float) $survey->ratings_avg_score, 2, ',', '.') }} / 5</td><td><a class="table-link" href="{{ route('admin.surveys.show', $survey) }}">Detail</a></td></tr>
                @empty
                    <tr><td colspan="7" class="empty-state">Belum ada survei petugas.</td></tr>
                @endforelse
            </tbody></table></div>
        </section>

        <section class="admin-panel dashboard-recent-panel">
            <div class="panel-heading"><h2>Pengaduan Terbaru</h2><a href="{{ route('admin.complaints.index') }}">Lihat seluruh pengaduan →</a></div>
            <div class="table-scroll"><table class="admin-table"><thead><tr><th>No.</th><th>Tanggal</th><th>Nomor Pengaduan</th><th>Jenis Pengaduan</th><th></th></tr></thead><tbody>
                @forelse($recentComplaints as $complaint)
                    <tr><td>{{ $loop->iteration }}</td><td>{{ $complaint->created_at->format('d/m/Y H:i') }}</td><td><strong>{{ $complaint->ticket_number }}</strong></td><td>{{ $complaint->type }}</td><td><a class="table-link" href="{{ route('admin.complaints.show', $complaint) }}">Detail</a></td></tr>
                @empty
                    <tr><td colspan="5" class="empty-state">Belum ada pengaduan.</td></tr>
                @endforelse
            </tbody></table></div>
        </section>
    </main>
    <script id="dashboard-chart-data" type="application/json">@json($chartData)</script>
    <script src="{{ asset('assets/admin-dashboard.js') }}?v=115" defer></script>
    <script src="{{ asset('assets/pwa.js') }}?v=51" defer></script>
</body>
</html>
