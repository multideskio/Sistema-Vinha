const CACHE_NAME = 'VINHA-cache-v1';
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll([
                OFFLINE_URL,
                '/', // Reativado
                '/index.html',
                '/assets/css/style.css',
                '/assets/js/app.js'
            ]);
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(cacheName => {
                    return cacheName.startsWith('VINHA-cache-') && cacheName !== CACHE_NAME;
                }).map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request).catch(() => caches.match(OFFLINE_URL));
        })
    );
});

self.addEventListener('push', event => {
    const options = {
        body: 'Você tem uma nova notificação do VINHA!',
        icon: '/assets/pwa/android-chrome-192x192.png',
        badge: '/assets/pwa/android-chrome-192x192.png'
    };

    event.waitUntil(
        self.registration.showNotification('Notificação do VINHA', options)
    );
});

// Exemplo de como integrar com métodos de pagamento e registrar transações
self.addEventListener('paymentrequest', event => {
    // Lógica para lidar com solicitações de pagamento
});

function logTransaction(transactionData) {
    // Lógica para registrar transações em um banco de dados ou serviço externo
}