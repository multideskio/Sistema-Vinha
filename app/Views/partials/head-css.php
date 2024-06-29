<!-- Layout config Js -->
<script src="/assets/js/layout.js"></script>
<!-- Bootstrap Css -->
<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<!-- Icons Css -->
<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css">
<!-- App Css-->
<link href="/assets/css/app.min.css" rel="stylesheet" type="text/css">
<!-- custom Css-->
<link href="/assets/css/custom.min.css" rel="stylesheet" type="text/css">
<!-- Variaveis -->
<script>
    const _baseUrl = "<?= site_url() ?>"
</script>

<style>
    .pwa-install-notification {
        position: fixed;
        top: 20px;
        /* Ajuste conforme necessário */
        right: 20px;
        /* Ajuste conforme necessário */
        z-index: 1000;
        /* Garante que a notificação esteja acima de outros elementos */
    }
</style>