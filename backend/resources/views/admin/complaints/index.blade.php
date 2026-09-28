<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan | Admin BHP Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=108">
    @include('partials.pwa-head')
</head>
<body class="admin-shell admin-complaints-page">
    @include('admin.partials.header')
    <main class="admin-main">
        @include('admin.partials.back-button', ['backUrl' => route('admin.dashboard')])
        <section class="admin-page-banner complaint-banner">
            <div><span class="kicker">Manajemen pengaduan</span><h1>Data Pengaduan</h1><p>Periksa laporan masyarakat dan unduh formulir pengaduannya.</p></div>
        </section>

        <section class="admin-list-section">
            <form class="admin-filter" method="GET"><input name="search" value="{{ request('search') }}" placeholder="Cari tiket atau email"><select name="type"><option value="">Semua jenis pengaduan</option>@foreach($complaintTypes as $complaintType)<option value="{{ $complaintType }}" @selected(request('type') === $complaintType)>{{ $complaintType }}</option>@endforeach</select><button class="button button-primary">Terapkan</button><a href="{{ route('admin.complaints.index') }}">Reset</a></form>
            <section class="admin-panel"><div class="table-scroll"><table class="admin-table"><thead><tr><th>No.</th><th>Tiket</th><th>Pelapor</th><th>Jenis</th><th>Tanggal</th><th></th></tr></thead><tbody>@forelse($complaints as $complaint)<tr><td>{{ $complaints->firstItem() + $loop->index }}</td><td><strong>{{ $complaint->ticket_number }}</strong></td><td>{{ $complaint->email }}<br><small>{{ $complaint->phone }}</small></td><td>{{ $complaint->type }}</td><td>{{ $complaint->created_at->format('d/m/Y H:i') }}</td><td><a class="table-link" href="{{ route('admin.complaints.show', $complaint) }}">Detail</a></td></tr>@empty<tr><td colspan="6" class="empty-state">Data pengaduan tidak ditemukan.</td></tr>@endforelse</tbody></table></div></section>
            <div class="admin-pagination">{{ $complaints->links() }}</div>
        </section>
    </main>
    <script src="{{ asset('assets/pwa.js') }}?v=51" defer></script>
</body>
</html>
