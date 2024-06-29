<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                © Vinha.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by Multidesk.io
                </div>
            </div>
        </div>
    </div>
</footer>

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