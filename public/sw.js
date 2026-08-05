// public/sw.js
self.addEventListener('install', event => {
    self.skipWaiting(); // Langsung aktifkan SW baru
});

self.addEventListener('activate', event => {
    event.waitUntil(clients.claim());
});

// Strategi Network-First (Mencegah user melihat produk yang sudah terjual/stale)
self.addEventListener('fetch', event => {
    event.respondWith(fetch(event.request));
});