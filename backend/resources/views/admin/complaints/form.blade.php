<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pengaduan {{ $complaint->ticket_number }}</title>
    <style>
        @page { size: A4; margin: 25mm 22mm; }
        body { margin: 0; color: #111; font-family: Arial, Helvetica, sans-serif; font-size: 11pt; line-height: 1.5; }
        .document-header { margin: 0 0 38px; text-align: center; }
        .document-header h1 { margin: 0; font-size: 16pt; line-height: 1.3; font-weight: bold; }
        .document-header p { margin: 3px 0 0; font-size: 12pt; font-weight: bold; }
        .section { margin: 0 0 27px; page-break-inside: avoid; }
        .section-title { margin: 0 0 12px; font-size: 12pt; font-weight: bold; }
        .section-title .number { display: inline-block; width: 27px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px 0; vertical-align: top; }
        .label { width: 43%; padding-left: 27px; }
        .separator { width: 18px; }
        .value { overflow-wrap: break-word; word-wrap: break-word; }
        .report { margin: 3px 0 0 27px; white-space: normal; overflow-wrap: break-word; }
    </style>
</head>
<body>
    <header class="document-header">
        <h1>FORMULIR PENGADUAN MASYARAKAT</h1>
        <p>Balai Harta Peninggalan Medan</p>
    </header>

    <section class="section">
        <h2 class="section-title"><span class="number">A.</span> Informasi Pengaduan</h2>
        <table>
            <tr><td class="label">Nomor Pengaduan</td><td class="separator">:</td><td class="value">{{ $complaint->ticket_number }}</td></tr>
            <tr><td class="label">Tanggal Pengaduan</td><td class="separator">:</td><td class="value">{{ $complaint->created_at->timezone(config('app.timezone'))->translatedFormat('d F Y, H:i') }} WIB</td></tr>
            <tr><td class="label">Jenis Pengaduan</td><td class="separator">:</td><td class="value">{{ $complaint->type }}</td></tr>
        </table>
    </section>

    <section class="section">
        <h2 class="section-title"><span class="number">B.</span> Data Kontak Pelapor</h2>
        <table>
            <tr><td class="label">Nomor HP</td><td class="separator">:</td><td class="value">{{ $complaint->phone }}</td></tr>
            <tr><td class="label">Email</td><td class="separator">:</td><td class="value">{{ $complaint->email }}</td></tr>
        </table>
    </section>

    <section class="section">
        <h2 class="section-title"><span class="number">C.</span> Uraian Pengaduan</h2>
        <div class="report">{!! nl2br(e($complaint->report)) !!}</div>
    </section>

</body>
</html>
