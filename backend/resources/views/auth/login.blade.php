<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | BHP Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}?v=71">
@include('partials.pwa-head')</head>
<body class="admin-login">
    <main class="login-card">
        <a class="brand login-brand" href="{{ route('home') }}">
            <img class="brand-logo" src="{{ asset('assets/logo-bhp-medan-display.png') }}" alt="Logo BHP Medan">
            <span class="brand-text"><strong>Balai Harta Peninggalan Medan</strong><small>Portal Administrator</small></span>
        </a>

        <h1>Masuk ke Dashboard</h1>
        <p>Gunakan akun administrator untuk mengelola data survei dan pengaduan.</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="form-field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-field">
                <label for="password">Kata Sandi</label>
                <input id="password" name="password" type="password" required autocomplete="current-password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <label class="remember-field"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <button class="button button-primary" type="submit">Masuk</button>
        </form>
        <a class="login-back" href="{{ route('home') }}">← Kembali ke halaman utama</a>
    </main>
<script src="{{ asset('assets/pwa.js') }}?v=51" defer></script></body>
</html>
