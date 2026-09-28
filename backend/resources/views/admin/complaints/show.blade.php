<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaduan {{ $complaint->ticket_number }} | Admin BHP Medan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=88">
  @include('partials.pwa-head')
</head>
<body class="admin-shell admin-detail-page complaint-detail-page">
  @include('admin.partials.header')
  <main class="admin-main">
    @include('admin.partials.back-button', ['backUrl' => route('admin.complaints.index')])
    <div class="admin-detail-grid complaint-document-grid">
      <section class="admin-panel detail-panel">
        <span class="kicker">{{ $complaint->ticket_number }}</span>
        <h2>Informasi Laporan</h2>
        <dl class="detail-list">
          <div><dt>No. HP</dt><dd>{{ $complaint->phone }}</dd></div>
          <div><dt>Email</dt><dd>{{ $complaint->email }}</dd></div>
          <div><dt>Jenis Aduan</dt><dd>{{ $complaint->type }}</dd></div>
          <div><dt>Dikirim</dt><dd>{{ $complaint->created_at->format('d/m/Y H:i') }} WIB</dd></div>
          <div class="full"><dt>Uraian Laporan</dt><dd class="report-text">{{ $complaint->report }}</dd></div>
        </dl>
      </section>

      <section class="admin-panel detail-panel complaint-download-panel">
        <span class="kicker">Dokumen pengaduan</span>
        <h2>Unduh Berkas</h2>
        <p>Unduh formulir pengaduan yang telah terisi atau lampiran bukti yang dikirim oleh pelapor.</p>
        <div class="complaint-download-actions">
          <a class="button button-primary" href="{{ route('admin.complaints.form', $complaint) }}">Unduh Form Pengaduan</a>
          <a class="button button-ghost" href="{{ route('admin.complaints.evidence', $complaint) }}">Unduh Lampiran Bukti</a>
        </div>
      </section>
    </div>
  </main>
  <script src="{{ asset('assets/pwa.js') }}?v=88" defer></script>
</body>
</html>
