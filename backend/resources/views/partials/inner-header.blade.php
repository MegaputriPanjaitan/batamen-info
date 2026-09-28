@include('partials.site-header', ['activeNavigation' => 'services'])
@php($serviceBackUrl = $backUrl ?? (url()->previous() !== url()->current() ? url()->previous() : route('home')))
<div class="container service-back-wrap">
    <a class="service-back-button" href="{{ $serviceBackUrl }}">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m10.7 17.3-1.4 1.4L2.6 12l6.7-6.7 1.4 1.4L6.4 11H21v2H6.4l4.3 4.3Z"/></svg>
        <span>{{ $backLabel ?? 'Kembali' }}</span>
    </a>
</div>
