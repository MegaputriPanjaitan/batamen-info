let installPrompt;
const installButton = document.querySelector('[data-pwa-install]');
const installPanel = document.querySelector('[data-pwa-install-panel]');
const installStatus = document.querySelector('[data-pwa-install-status]');
const iosInstructions = document.querySelector('[data-pwa-ios-instructions]');
const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent);
const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
const installRecorded = localStorage.getItem('pasti-batamen-installed') === 'true';

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/service-worker.js'));
}

if (isStandalone || installRecorded) {
  if (installPanel) installPanel.hidden = true;
  if (isStandalone) localStorage.setItem('pasti-batamen-installed', 'true');
} else if (isIos) {
  if (installPanel) installPanel.hidden = false;
  if (installButton) {
    installButton.disabled = false;
    installButton.setAttribute('aria-label', 'Lihat cara memasang PASTI Batamen');
    installButton.setAttribute('title', 'Lihat cara memasang PASTI Batamen');
  }
} else if (installStatus && !window.isSecureContext) {
  installStatus.textContent = 'Fitur instalasi aktif setelah website menggunakan HTTPS.';
}

window.addEventListener('beforeinstallprompt', (event) => {
  event.preventDefault();
  installPrompt = event;
  if (installPanel) installPanel.hidden = false;
  if (installButton) installButton.disabled = false;
  if (installStatus) installStatus.textContent = 'Aplikasi siap dipasang di perangkat ini.';
});

installButton?.addEventListener('click', async () => {
  if (isIos) {
    if (iosInstructions) iosInstructions.hidden = !iosInstructions.hidden;
    return;
  }

  if (!installPrompt) return;
  installPrompt.prompt();
  const choice = await installPrompt.userChoice;
  installPrompt = null;
  installButton.disabled = true;
  if (installStatus) {
    installStatus.textContent = choice.outcome === 'accepted'
      ? 'Pemasangan aplikasi sedang diproses.'
      : 'Pemasangan dibatalkan. Anda dapat mencobanya kembali nanti.';
  }
});

window.addEventListener('appinstalled', () => {
  localStorage.setItem('pasti-batamen-installed', 'true');
  if (installPanel) installPanel.hidden = true;
});
