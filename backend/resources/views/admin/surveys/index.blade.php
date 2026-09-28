<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Petugas | Admin BHP Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=115">
    @include('partials.pwa-head')
</head>
<body class="admin-shell admin-surveys-page">
    @include('admin.partials.header')
    <main class="admin-main">
        @include('admin.partials.back-button', ['backUrl' => route('admin.dashboard')])
        <section class="admin-page-banner">
            <div><span class="kicker">Manajemen survei</span><h1>Survei Pelayanan Petugas</h1><p>Pantau hasil penilaian dan buka grafik performa setiap petugas.</p></div>
            <a class="button banner-button" href="{{ route('admin.staff.index') }}">Kelola Petugas</a>
        </section>

        <section class="admin-panel performance-table-panel">
            <div class="panel-heading"><h2>Peringkat Performa Petugas</h2></div>
            <div class="table-scroll"><table class="admin-table"><thead><tr><th>Peringkat</th><th>Petugas</th><th>Jabatan</th><th>Jumlah Survei</th><th>Rata-rata</th><th></th></tr></thead><tbody>
                @forelse($staffMembers as $staff)
                    <tr><td><strong class="staff-rank">#{{ $loop->iteration }}</strong></td><td><span class="staff-table-identity performance-identity">@include('admin.partials.staff-photo', ['staffMember' => $staff])<strong>{{ $staff->name }}</strong></span></td><td>{{ $staff->position }}</td><td>{{ $staff->survey_responses_count }}</td><td>{{ $staff->ratings_avg_score ? number_format($staff->ratings_avg_score, 2, ',', '.') : '—' }} / 5</td><td><a class="table-link" href="{{ route('admin.staff.performance', $staff) }}">Lihat grafik</a></td></tr>
                @empty
                    <tr><td colspan="6" class="empty-state">Belum ada data petugas.</td></tr>
                @endforelse
            </tbody></table></div>
        </section>

        <section class="admin-list-section survey-history-section">
            <form class="admin-filter" method="GET"><select name="staff_member_id"><option value="">Semua petugas</option>@foreach($staffMembers as $staff)<option value="{{ $staff->id }}" @selected((string) request('staff_member_id') === (string) $staff->id)>{{ $staff->name }}</option>@endforeach</select><button class="button button-primary">Terapkan</button><a href="{{ route('admin.surveys.index') }}">Reset</a></form>
            <section class="admin-panel"><div class="table-scroll"><table class="admin-table"><thead><tr><th>No.</th><th>Tanggal</th><th>Petugas</th><th>Jabatan</th><th>Rata-rata</th><th></th></tr></thead><tbody>@forelse($surveys as $survey)<tr><td>{{ $surveys->firstItem() + $loop->index }}</td><td>{{ $survey->created_at->format('d/m/Y H:i') }}</td><td><strong>{{ $survey->staffMember->name }}</strong></td><td>{{ $survey->staffMember->position }}</td><td>{{ number_format((float) $survey->ratings_avg_score, 2, ',', '.') }} / 5</td><td><a class="table-link" href="{{ route('admin.surveys.show', $survey) }}">Detail</a></td></tr>@empty<tr><td colspan="6" class="empty-state">Data survei tidak ditemukan.</td></tr>@endforelse</tbody></table></div></section>
            <div class="admin-pagination">{{ $surveys->links() }}</div>
        </section>
    </main>
    <script src="{{ asset('assets/pwa.js') }}?v=51" defer></script>
</body>
</html>
