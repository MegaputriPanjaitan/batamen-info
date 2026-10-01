<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Survei Petugas Pelayanan | BHP Medan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=136">
@include('partials.pwa-head')</head>
<body class="inner-page building-background-page interactive-service-page staff-survey-public-page">
  @include('partials.inner-header', ['backUrl' => route('surveys.index'), 'backLabel' => 'Kembali'])
  <main>
    <section class="page-hero service-form-hero"><div class="container"><div class="service-hero-copy"><span class="kicker page-hero-kicker">Survei Petugas Pelayanan</span><h1>Bagaimana pengalaman pelayanan Anda?</h1><p>Pilih petugas yang melayani, lalu berikan penilaian secara jujur pada setiap pernyataan.</p></div></div></section>
    <section class="page-content service-form-content"><div class="container">
    <form class="staff-survey standalone-survey service-form-card" id="staff-survey-form" method="POST" action="{{ route('survey-responses.store') }}" data-availability-url="{{ route('survey-responses.availability') }}">
      @csrf

      @if ($errors->any())<div class="form-message error" role="alert">{{ $errors->first() }}</div>@endif

      <dialog class="service-dialog survey-context-dialog" data-survey-context-dialog data-open-on-load>
        <div class="service-dialog-card">
          <span class="service-dialog-icon" aria-hidden="true">01</span>
          <div class="service-dialog-copy"><small>SEBELUM MEMULAI</small><h2>Informasi layanan Anda</h2><p>Masukkan nomor HP dan pilih layanan yang baru Anda terima untuk menampilkan petugas terkait.</p></div>
          <label class="service-dialog-field"><span>Nomor HP</span><input type="tel" name="respondent_phone" value="{{ old('respondent_phone') }}" inputmode="tel" autocomplete="tel" placeholder="Contoh: 081234567890" required></label>
          <label class="service-dialog-field"><span>Jenis layanan</span><select name="service_slug" required><option value="">Pilih jenis layanan</option>@foreach($publicServices as $slug => $service)<option value="{{ $slug }}" @selected(old('service_slug') === $slug)>{{ $service['name'] }}</option>@endforeach</select></label>
          <p class="service-dialog-error" data-survey-context-error hidden></p>
          <div class="service-dialog-actions"><a class="button button-secondary" href="{{ route('surveys.index') }}">Batal</a><button class="button button-primary" type="button" data-survey-context-continue>Lanjutkan</button></div>
        </div>
      </dialog>

      <section class="survey-step survey-staff-step" data-survey-step="staff" hidden>
      <fieldset class="staff-picker"><legend>Pilih petugas pelayanan <span>*</span></legend><p class="selected-service-context">Layanan: <strong data-selected-service>belum dipilih</strong></p><div class="staff-list">
        @forelse ($staffMembers as $staffMember)
          <div class="staff-choice" data-services='@json($staffMember->service_slugs ?? [])'>
            <input type="radio" name="staff_member_id" id="staff-{{ $staffMember->id }}" value="{{ $staffMember->id }}" @checked(old('staff_member_id') == $staffMember->id) required>
            <label for="staff-{{ $staffMember->id }}">
              @if ($staffMember->photo_path)
                <img class="staff-photo" src="{{ asset('storage/'.$staffMember->photo_path) }}" alt="Foto {{ $staffMember->name }}">
              @else
                <span class="staff-photo" aria-hidden="true">{{ mb_strtoupper(mb_substr($staffMember->name, 0, 2)) }}</span>
              @endif
              <span><strong>{{ $staffMember->name }}</strong><small>NIP {{ $staffMember->nip ?: 'belum diisi' }}</small></span>
            </label>
            <p class="staff-choice-unavailable" data-staff-unavailable role="alert" hidden>Anda tidak dapat memilih petugas ini lagi.</p>
          </div>
        @empty
          <p>Belum ada petugas aktif. Silakan hubungi administrator.</p>
        @endforelse
      </div><p class="staff-service-empty" data-staff-service-empty hidden>Belum ada petugas aktif untuk layanan ini. Silakan hubungi administrator.</p></fieldset>
      </section>

      <dialog class="service-dialog" data-staff-confirm-dialog>
        <div class="service-dialog-card">
          <button class="service-dialog-close" type="button" data-dialog-close aria-label="Tutup">&times;</button>
          <div class="staff-confirm-photo" data-confirm-staff-photo aria-hidden="true"></div>
          <div class="service-dialog-copy"><h2>Apakah ini petugasnya?</h2><strong class="staff-confirm-name" data-confirm-staff>petugas terpilih</strong><span class="staff-confirm-position" data-confirm-staff-nip></span></div>
          <div class="service-dialog-actions"><button class="button button-secondary" type="button" data-dialog-close>Batal</button><button class="button button-primary" type="button" data-confirm-staff-survey>Ya, Lanjutkan <span>→</span></button></div>
        </div>
      </dialog>

      <section class="survey-step survey-question-step" data-survey-step="questions" hidden>
      <div class="survey-question-toolbar"><p>Menilai: <strong data-selected-staff>petugas terpilih</strong></p></div>

      <aside class="rating-scale" aria-label="Keterangan skala penilaian">
        <div><span><b aria-label="1 bintang">★</b>Sangat Tidak Setuju</span><span><b aria-label="2 bintang">★★</b>Tidak Setuju</span><span><b aria-label="3 bintang">★★★</b>Cukup</span><span><b aria-label="4 bintang">★★★★</b>Setuju</span><span><b aria-label="5 bintang">★★★★★</b>Sangat Setuju</span></div>
      </aside>

      <div class="rating-sections">
        @php($questionIndex = 0)
        @foreach ($surveyGroups as $groupIndex => $group)
          <section class="rating-group">
            <div class="rating-group-heading"><span>{{ str_pad((string) ($groupIndex + 1), 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $group['title'] }}</h3></div>
            @foreach ($group['questions'] as $question)
              @php($questionIndex++)
              <div class="rating-row">
                <p>{{ $question }}</p>
                <input type="hidden" name="ratings[{{ $questionIndex - 1 }}][question_key]" value="q{{ $questionIndex }}">
                <div class="stars">
                  @foreach ([5, 4, 3, 2, 1] as $score)
                    <input type="radio" id="q{{ $questionIndex }}-{{ $score }}" name="ratings[{{ $questionIndex - 1 }}][score]" value="{{ $score }}" @checked(old("ratings.".($questionIndex - 1).'.score') == $score) required>
                    <label for="q{{ $questionIndex }}-{{ $score }}" title="{{ $score }} bintang" aria-label="{{ $score }} bintang">★</label>
                  @endforeach
                </div>
              </div>
            @endforeach
          </section>
        @endforeach
      </div>
      <div class="survey-submit"><p>Pastikan seluruh pernyataan sudah dinilai sebelum dikirim.</p><button class="button button-primary" type="submit" @disabled($staffMembers->isEmpty())>Kirim Penilaian <span>→</span></button></div>
      </section>
    </form>
  </div></section></main>
  <script src="{{ asset('assets/script.js') }}?v=137"></script>
<script src="{{ asset('assets/pwa.js') }}?v=88" defer></script></body></html>
