import './styles.css';

const configuredBaseUrl = import.meta.env.VITE_BHP_BASE_URL;

if (!configuredBaseUrl) {
  throw new Error('VITE_BHP_BASE_URL belum dikonfigurasi. Isi alamat Laravel pada file environment aplikasi.');
}
const baseUrl = configuredBaseUrl.replace(/\/$/, '');

const services = [
  {
    key: 'information',
    title: 'Pusat Informasi',
    description: 'Dokumen publik, media sosial, dan kanal resmi BHP Medan.',
    path: '/pusat-informasi',
    color: 'blue',
    icon: '<path d="M5 3h11a3 3 0 0 1 3 3v15H8a3 3 0 0 1-3-3V3Zm3 2v11.2c.3-.1.7-.2 1-.2h8V6a1 1 0 0 0-1-1H8Zm1 13a1 1 0 0 0 0 2h8v-2H9Zm2-10h3v2h-3V8Zm0 4h4v2h-4v-2Z"/>',
  },
  {
    key: 'surveys',
    title: 'Survei',
    description: 'Pilih jenis survei dan sampaikan penilaian Anda.',
    path: '/survei',
    color: 'gold',
    icon: '<path d="M9 3h6a2 2 0 0 1 2 2h2a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2a2 2 0 0 1 2-2Zm1 2v2h4V5h-4Zm-2 7 2.2 2.2 5-5 1.4 1.4-6.4 6.4-3.6-3.6L8 12Z"/>',
  },
  {
    key: 'staff',
    title: 'Survei Petugas',
    description: 'Pilih petugas pelayanan dan berikan penilaian langsung.',
    path: '/survei-petugas',
    color: 'green',
    icon: '<path d="M10 11a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0 2c-5 0-9 2.2-9 5v3h14.2a7 7 0 0 1 2.2-6.9A17.8 17.8 0 0 0 10 13Zm9.5 1 .8 2.6H23l-2.2 1.6.9 2.5-2.2-1.6-2.2 1.6.9-2.5-2.2-1.6h2.7l.8-2.6Z"/>',
  },
  {
    key: 'complaints',
    title: 'Pengaduan',
    description: 'Sampaikan laporan secara aman, jelas, dan terarah.',
    path: '/pengaduan',
    color: 'red',
    icon: '<path d="M12 2a10 10 0 0 1 8.6 15L22 22l-5-1.4A10 10 0 1 1 12 2Zm0 2a8 8 0 1 0 4.6 14.5l.4-.2 2.1.5-.6-2.1.3-.3A8 8 0 0 0 12 4Zm-1 3h2v6h-2V7Zm0 8h2v2h-2v-2Z"/>',
  },
];

const serviceCards = services.map((service) => `
  <a class="service-card ${service.color}" href="${baseUrl}${service.path}" data-service-link>
    <span class="service-icon"><svg viewBox="0 0 24 24" aria-hidden="true">${service.icon}</svg></span>
    <span class="service-copy"><strong>${service.title}</strong><small>${service.description}</small></span>
    <span class="service-arrow" aria-hidden="true">→</span>
  </a>
`).join('');

document.querySelector('#app').innerHTML = `
  <main class="app-shell">
    <header class="app-header">
      <img src="/logo-bhp.png" alt="Logo Balai Harta Peninggalan Medan">
      <span><strong>Balai Harta Peninggalan</strong><small>Medan</small></span>
      <span class="connection-status" data-connection-status aria-live="polite"></span>
    </header>

    <section class="hero-panel">
      <div>
        <span class="eyebrow">Layanan BHP Medan</span>
        <h1>Layanan publik dalam satu aplikasi.</h1>
        <p>Pilih layanan yang Anda perlukan untuk terhubung langsung dengan portal resmi BHP Medan.</p>
      </div>
      <span class="hero-mark">BHP</span>
    </section>

    <section class="services" aria-labelledby="services-title">
      <div class="section-heading">
        <div><span>Menu utama</span><h2 id="services-title">Pilih layanan</h2></div>
        <button type="button" data-refresh-status>Periksa koneksi</button>
      </div>
      <div class="service-list">${serviceCards}</div>
    </section>

    <aside class="server-note">
      <span>i</span>
      <p><strong>Server pengembangan</strong><small>${baseUrl}</small></p>
    </aside>

    <footer>© <span data-year></span> Balai Harta Peninggalan Medan</footer>
  </main>
`;

const statusElement = document.querySelector('[data-connection-status]');
const serviceLinks = [...document.querySelectorAll('[data-service-link]')];

function updateConnectionStatus() {
  const online = navigator.onLine;
  statusElement.textContent = online ? 'Online' : 'Offline';
  statusElement.classList.toggle('offline', !online);
  serviceLinks.forEach((link) => link.setAttribute('aria-disabled', String(!online)));
}

window.addEventListener('online', updateConnectionStatus);
window.addEventListener('offline', updateConnectionStatus);
document.querySelector('[data-refresh-status]').addEventListener('click', updateConnectionStatus);
document.querySelector('[data-year]').textContent = new Date().getFullYear();
updateConnectionStatus();
