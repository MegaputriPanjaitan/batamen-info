<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#073b67">
  <title>Pengaduan | BHP Medan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=129">
@include('partials.pwa-head')</head>
<body class="inner-page building-background-page interactive-service-page complaint-public-page">
  @include('partials.inner-header')
  <main>
    <section class="page-hero service-form-hero"><div class="container"><div class="service-hero-copy"><span class="kicker page-hero-kicker">Layanan Pengaduan Masyarakat</span><h1>Sampaikan laporan dengan aman</h1><p>Lengkapi informasi dengan benar agar laporan dapat ditinjau dan ditindaklanjuti secara tepat.</p></div></div></section>
    <section class="page-content service-form-content"><div class="container">
      <form class="complaint-form service-form-card" id="complaint-form" method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
          <div class="form-message error" role="alert">Mohon periksa kembali data pengaduan Anda.</div>
        @endif
        <div class="form-grid">
          <div class="form-field"><label for="phone">No. HP <span class="required">*</span></label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" pattern="[0-9+() -]{9,18}" placeholder="Contoh: 081234567890" required>@error('phone')<p class="field-error">{{ $message }}</p>@enderror</div>
          <div class="form-field"><label for="email">Email Aktif <span class="required">*</span></label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="Contoh: nama@email.com" required>@error('email')<p class="field-error">{{ $message }}</p>@enderror</div>
          <div class="form-field full"><label for="complaint-type">Jenis Aduan <span class="required">*</span></label><select id="complaint-type" name="complaint_type" required><option value="" disabled @selected(!old('complaint_type'))>Pilih jenis aduan</option>@foreach (config('complaints.types') as $complaintType)<option @selected(old('complaint_type') === $complaintType)>{{ $complaintType }}</option>@endforeach</select>@error('complaint_type')<p class="field-error">{{ $message }}</p>@enderror</div>
          <div class="form-field full"><label for="report">Uraian Laporan <span class="required">*</span></label><textarea id="report" name="report" minlength="20" placeholder="Jelaskan waktu, tempat, pihak terkait, dan kronologi kejadian..." required>{{ old('report') }}</textarea><p class="field-help">Tuliskan sekurangnya 20 karakter.</p>@error('report')<p class="field-error">{{ $message }}</p>@enderror</div>
          <div class="form-field full"><label for="evidence">Lampiran Bukti Aduan <span class="required">*</span></label><input id="evidence" name="evidence" type="file" accept="image/*,.pdf,.doc,.docx" required><p class="field-help">Unggah satu file gambar, PDF, atau dokumen. Maksimal 10 MB.</p>@error('evidence')<p class="field-error">{{ $message }}</p>@enderror</div>
        </div>
        <div class="form-actions"><button class="button button-primary" type="submit">Kirim Pengaduan <span>→</span></button></div>
        <div class="form-message" id="complaint-message" role="status" aria-live="polite"></div>
      </form>
    </div></section>
  </main>
  <script src="{{ asset('assets/script.js') }}?v=53"></script><script src="{{ asset('assets/pengaduan.js') }}"></script>
<script src="{{ asset('assets/pwa.js') }}?v=53" defer></script></body></html>
