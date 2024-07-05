const CACHE_NAME = 'vinha-cache';
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll([
                //'/',
                //'/index.php',
                //'/assets/',
                '/offline.html',
                '/assets/pwa/android-chrome-192x192.png',
                '/assets/pwa/android-chrome-512x512.png',
                '/assets/pwa/screenshot-desktop.png',
                '/assets/pwa/screenshot-mobile.png'
            ]);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request).then((response) => {
                return response || caches.match(OFFLINE_URL);
            });
        })
    );
});
