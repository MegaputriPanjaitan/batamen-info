let installPrompt;
const installButton = document.querySelector('[data-pwa-install]');
const installPanel = document.querySelector('[data-pwa-install-panel]');
const installDismissButton = document.querySelector('[data-pwa-install-dismiss]');
const iosInstallButton = document.querySelector('[data-pwa-ios-install]');
const iosInstructions = document.querySelector('[data-pwa-ios-instructions]');
const isNativeApp = Boolean(window.Capacitor?.isNativePlatform?.());
const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent);

const hideInstallPanel = () => {
  if (installPanel) installPanel.hidden = true;
};

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/service-worker.js'));
}

window.addEventListener('beforeinstallprompt', (event) => {
  if (!installButton || isNativeApp || isStandalone) return;
  event.preventDefault();
  installPrompt = event;
  installButton.hidden = false;
  if (installPanel) installPanel.hidden = false;
});

installButton?.addEventListener('click', async () => {
  if (!installPrompt) return;
  installPrompt.prompt();
  await installPrompt.userChoice;
  installPrompt = null;
  installButton.hidden = true;
  hideInstallPanel();
});

if (installPanel && iosInstallButton && isIos && !isNativeApp && !isStandalone) {
  installPanel.hidden = false;
  iosInstallButton.hidden = false;
}

iosInstallButton?.addEventListener('click', () => {
  if (!iosInstructions) return;
  iosInstructions.hidden = !iosInstructions.hidden;
  iosInstallButton.textContent = iosInstructions.hidden ? 'Cara pasang' : 'Tutup petunjuk';
});

installDismissButton?.addEventListener('click', hideInstallPanel);

window.addEventListener('appinstalled', () => {
  installPrompt = null;
  if (installButton) installButton.hidden = true;
  hideInstallPanel();
});
