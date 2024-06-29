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
    /* Estilo para dispositivos móveis */
    .pwa-install-prompt {
        position: fixed;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #644cc1;
        /* Cor de fundo da notificação */
        color: #ffffff;
        /* Cor do texto da notificação */
        padding: 10px;
        text-align: center;
        z-index: 1000;
        /* Certifique-se de que está acima de outros elementos */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        /* Sombra para destacar a notificação */
    }
</style>