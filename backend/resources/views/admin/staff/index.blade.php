<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas | Admin BHP Medan</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=124">
    @include('partials.pwa-head')
</head>
<body class="admin-shell staff-management-page">
    @include('admin.partials.header')
    <main class="admin-main">
        @include('admin.partials.back-button', ['backUrl' => route('admin.surveys.index')])
        <section class="admin-page-banner">
            <div><span class="kicker">Survei pelayanan</span><h1>Manajemen Data Petugas</h1><p>Tambahkan petugas, unggah foto, dan tentukan status tampil pada formulir survei.</p></div>
        </section>

        @if(session('success'))<div class="admin-alert">{{ session('success') }}</div>@endif

        <div class="staff-management-grid">
            <section class="admin-panel staff-form-panel">
                <div class="panel-heading"><h2>Tambah Petugas</h2></div>
                <form class="staff-admin-form" method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-field"><label for="name">Nama lengkap <span class="required">*</span></label><input id="name" name="name" value="{{ old('name') }}" required>@error('name')<p class="field-error">{{ $message }}</p>@enderror</div>
                    <div class="form-field"><label for="position">Jabatan <span class="required">*</span></label><input id="position" name="position" value="{{ old('position', 'Petugas Pelayanan') }}" required>@error('position')<p class="field-error">{{ $message }}</p>@enderror</div>
                    <div class="form-field"><label for="photo">Foto petugas</label><input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"><p class="field-help">JPG, PNG, atau WebP. Maksimal 2 MB.</p>@error('photo')<p class="field-error">{{ $message }}</p>@enderror</div>
                    <label class="staff-active-field"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Tampilkan pada formulir survei</label>
                    <button class="button button-primary" type="submit">Simpan Petugas</button>
                </form>
            </section>

            <section class="admin-panel staff-list-panel">
                <div class="panel-heading"><h2>Daftar Petugas</h2><span>{{ $staffMembers->total() }} petugas</span></div>
                <div class="table-scroll"><table class="admin-table"><thead><tr><th>No.</th><th>Petugas</th><th>Jabatan</th><th>Survei</th><th>Status</th><th></th></tr></thead><tbody>
                    @forelse($staffMembers as $staff)
                        <tr><td>{{ $staffMembers->firstItem() + $loop->index }}</td><td><span class="staff-table-identity">@if($staff->photo_path)<img src="{{ asset('storage/'.$staff->photo_path) }}" alt="Foto {{ $staff->name }}">@else<span>{{ mb_strtoupper(mb_substr($staff->name, 0, 2)) }}</span>@endif<strong>{{ $staff->name }}</strong></span></td><td>{{ $staff->position }}</td><td>{{ $staff->survey_responses_count }}</td><td><span class="status-badge {{ $staff->is_active ? 'status-resolved' : 'status-rejected' }}">{{ $staff->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td><div class="staff-row-actions"><a class="table-link" href="{{ route('admin.staff.edit', $staff) }}">Edit</a></div></td></tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">Belum ada data petugas.</td></tr>
                    @endforelse
                </tbody></table></div>
                <div class="admin-pagination">{{ $staffMembers->links() }}</div>
            </section>
        </div>
    </main>
    <script src="{{ asset('assets/pwa.js') }}?v=51" defer></script>
</body>
</html>
