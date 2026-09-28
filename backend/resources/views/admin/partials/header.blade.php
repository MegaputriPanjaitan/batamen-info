<header class="admin-topbar">
    <a class="admin-logo-link" href="{{ route('admin.dashboard') }}" aria-label="BHP Medan - Dashboard">
        <img src="{{ asset('assets/logo-bhp-medan-display.png') }}" alt="Logo BHP Medan">
        <span class="admin-brand-copy"><strong>Balai Harta Peninggalan</strong><small>Medan</small></span>
    </a>
    <nav class="admin-nav" aria-label="Navigasi admin">
        <a @class(['active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a @class(['active' => request()->routeIs('admin.complaints.*')]) href="{{ route('admin.complaints.index') }}">Pengaduan</a>
        <a @class(['active' => request()->routeIs('admin.surveys.*', 'admin.staff.*')]) href="{{ route('admin.surveys.index') }}">Survei Petugas</a>
    </nav>
    <form class="admin-logout" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" title="Keluar" aria-label="Keluar dari dashboard">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h5v-2H5V5h5V3Zm4.59 4.59L16 6.17 21.83 12 16 17.83l-1.41-1.42L18 13H9v-2h9l-3.41-3.41Z"/></svg>
        </button>
    </form>
</header>
