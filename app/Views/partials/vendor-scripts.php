<!-- JAVASCRIPT -->
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/libs/feather-icons/feather.min.js"></script>
<script src="/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="/assets/js/plugins.js"></script>

<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('<?= base_url('service-worker.js') ?>')
            .then(registration => {
                console.log('ServiceWorker registrado com sucesso: ', registration.scope);
            })
            .catch(error => {
                console.log('Falha ao registrar o ServiceWorker: ', error);
            });
    }
</script>