const CACHE = 'smart-school-public-v1';
const OFFLINE = '/offline.html';
self.addEventListener('install', event => {
  event.waitUntil(caches.open(CACHE).then(cache => cache.add(OFFLINE)));
  self.skipWaiting();
});
self.addEventListener('activate', event => {
  event.waitUntil(caches.keys().then(keys => Promise.all(keys.filter(k => k.startsWith('smart-school-public-') && k !== CACHE).map(k => caches.delete(k)))));
  self.clients.claim();
});
// Authenticated pages and all writes are network-only: no student data in caches.
self.addEventListener('fetch', event => {
  if (event.request.mode === 'navigate' && event.request.method === 'GET') {
    event.respondWith(fetch(event.request).catch(() => caches.match(OFFLINE)));
  }
});
