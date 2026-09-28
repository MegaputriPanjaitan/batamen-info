<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $staffMember->name }} | Admin BHP Medan</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=71">
    @include('partials.pwa-head')
</head>
<body class="admin-shell">
    @include('admin.partials.header')
    <main class="admin-main narrow-admin-main">
        @include('admin.partials.back-button', ['backUrl' => route('admin.staff.index')])
        <section class="admin-panel edit-staff-panel">
            <div class="edit-staff-heading">@if($staffMember->photo_path)<img src="{{ asset('storage/'.$staffMember->photo_path) }}" alt="Foto {{ $staffMember->name }}">@else<span>{{ mb_strtoupper(mb_substr($staffMember->name, 0, 2)) }}</span>@endif<div><span class="kicker">Edit petugas</span><h1>{{ $staffMember->name }}</h1></div></div>
            <form class="staff-admin-form" method="POST" action="{{ route('admin.staff.update', $staffMember) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-field"><label for="name">Nama lengkap <span class="required">*</span></label><input id="name" name="name" value="{{ old('name', $staffMember->name) }}" required>@error('name')<p class="field-error">{{ $message }}</p>@enderror</div>
                <div class="form-field"><label for="position">Jabatan <span class="required">*</span></label><input id="position" name="position" value="{{ old('position', $staffMember->position) }}" required>@error('position')<p class="field-error">{{ $message }}</p>@enderror</div>
                <div class="form-field"><label for="photo">Ganti foto</label><input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"><p class="field-help">Biarkan kosong jika tidak ingin mengganti foto.</p>@error('photo')<p class="field-error">{{ $message }}</p>@enderror</div>
                <label class="staff-active-field"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $staffMember->is_active))> Tampilkan pada formulir survei</label>
                <button class="button button-primary" type="submit">Simpan Perubahan</button>
            </form>
        </section>
    </main>
    <script src="{{ asset('assets/pwa.js') }}?v=51" defer></script>
</body>
</html>
