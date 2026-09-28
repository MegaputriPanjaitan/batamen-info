const CACHE_NAME = 'bhp-medan-static-v129';
const OFFLINE_URL = '/offline.html';
const STATIC_FILES = [OFFLINE_URL, '/assets/styles.css', '/assets/pwa.js', '/assets/logo-bhp-medan-display.png', '/assets/bhp-medan-building.jpeg', '/assets/bhp-building-about.jpeg', '/assets/layanan-informasi.jpeg', '/assets/layanan-survei.jpeg', '/assets/layanan-pengaduan.jpeg', '/assets/maskot-bhp-transparent.png', '/assets/app-icon-192.png', '/assets/app-icon-512.png'];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_FILES)));
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))))
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const request = event.request;

  if (request.method !== 'GET') return;

  if (request.mode === 'navigate') {
    event.respondWith(fetch(request).catch(() => caches.match(OFFLINE_URL)));
    return;
  }

  const url = new URL(request.url);
  if (url.origin !== self.location.origin || !url.pathname.startsWith('/assets/')) return;

  event.respondWith(
    fetch(request).then((response) => {
      if (response.ok) caches.open(CACHE_NAME).then((cache) => cache.put(request, response.clone()));
      return response;
    }).catch(() => caches.match(request))
  );
});
