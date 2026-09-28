# Aplikasi Layanan BHP Medan

Aplikasi lintas-platform berbasis Vite dan Capacitor. Laravel tetap menjadi backend dan sumber halaman layanan.

## Menjalankan secara lokal

1. Jalankan Laravel agar dapat diakses jaringan lokal:

   ```powershell
   cd ..\backend
   php artisan serve --host=0.0.0.0 --port=8000
   ```

2. Temukan IPv4 komputer dengan `ipconfig`.
3. Ubah `VITE_BHP_BASE_URL` di `.env.development` sesuai IPv4 komputer.
4. Jalankan aplikasi web:

   ```powershell
   npm.cmd install
   npm.cmd run dev
   ```

## Android

```powershell
npm.cmd run build
npm.cmd run cap:sync
npm.cmd run android
```

## iOS

Build iOS memerlukan macOS dan Xcode:

```bash
npm run build
npm run cap:sync
npm run ios
```

## Sebelum rilis

Buat `.env.production` berdasarkan `.env.production.example`, isi dengan domain HTTPS produksi, lalu ganti `server.url` pada `capacitor.config.json` dengan domain yang sama. Nonaktifkan `server.cleartext` dan `allowMixedContent` sebelum rilis.
