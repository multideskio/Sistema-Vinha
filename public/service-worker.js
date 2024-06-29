self.addEventListener('install', event => {
    console.log('Service Worker instalado.');
});

self.addEventListener('activate', event => {
    console.log('Service Worker ativado.');
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.open('VINHA-cache').then(cache => {
            return cache.match(event.request).then(response => {
                return response || fetch(event.request).then(fetchResponse => {
                    cache.put(event.request, fetchResponse.clone());
                    return fetchResponse;
                });
            });
        })
    );
});

// Exemplo de como lidar com notificações
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

// Exemplo de como integrar com métodos de pagamento
self.addEventListener('paymentrequest', event => {
    // Lógica para lidar com solicitações de pagamento
});

// Exemplo de como manter um histórico de transações
function logTransaction(transactionData) {
    // Lógica para registrar transações em um banco de dados ou serviço externo
}
