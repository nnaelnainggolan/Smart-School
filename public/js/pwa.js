if ('serviceWorker' in navigator && window.isSecureContext) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
}
let installPrompt;
window.addEventListener('beforeinstallprompt', event => {
  event.preventDefault(); installPrompt = event;
  document.querySelectorAll('[data-install-school]').forEach(b => b.hidden = false);
});
document.addEventListener('click', async event => {
  if (!event.target.closest('[data-install-school]') || !installPrompt) return;
  await installPrompt.prompt(); installPrompt = null;
  document.querySelectorAll('[data-install-school]').forEach(b => b.hidden = true);
});
function connectionState() {
  document.querySelectorAll('[data-connection-warning]').forEach(el => el.hidden = navigator.onLine);
}
window.addEventListener('online', connectionState);
window.addEventListener('offline', connectionState);
connectionState();
