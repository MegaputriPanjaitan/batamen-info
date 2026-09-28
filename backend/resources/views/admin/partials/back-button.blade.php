<a class="admin-back-button" href="{{ $backUrl ?? url()->previous() }}">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m10.7 17.3-1.4 1.4L2.6 12l6.7-6.7 1.4 1.4L6.4 11H21v2H6.4l4.3 4.3Z"/></svg>
    <span>{{ $label ?? 'Kembali' }}</span>
</a>
