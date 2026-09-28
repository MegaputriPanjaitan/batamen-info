@if ($staffMember->photo_path)
    <img class="{{ $class ?? '' }}" src="{{ asset('storage/'.$staffMember->photo_path) }}" alt="Foto {{ $staffMember->name }}">
@else
    <span class="{{ $class ?? '' }}" aria-label="Foto belum tersedia">{{ collect(explode(' ', $staffMember->name))->filter()->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->implode('') }}</span>
@endif
