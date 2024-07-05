<!-- JAVASCRIPT -->
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/libs/feather-icons/feather.min.js"></script>
<script src="/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="/assets/js/plugins.js"></script>

<div id="pwa-install-banner">
    Instale nosso aplicativo!
    <button id="install-button">Instalar</button>
</div>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                //console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }

    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        // Previne o mini-infobar de aparecer no mobile
        e.preventDefault();
        // Guarda o evento para ser acionado mais tarde
        deferredPrompt = e;

        // Exibe o banner de instalação após 20 segundos
        setTimeout(() => {
            document.getElementById('pwa-install-banner').style.display = 'block';
        }, 20000);
    });

    document.getElementById('install-button').addEventListener('click', async () => {
        if (deferredPrompt) {
            // Mostra o prompt de instalação
            deferredPrompt.prompt();
            // Espera pelo usuário responder ao prompt
            const {
                outcome
            } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                console.log('Usuário aceitou a instalação');
            } else {
                console.log('Usuário recusou a instalação');
            }
            // Resetar deferredPrompt para null
            deferredPrompt = null;
        }
        // Esconde o banner após a interação
        document.getElementById('pwa-install-banner').style.display = 'none';
    });
</script>