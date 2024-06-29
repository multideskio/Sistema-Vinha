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
    #pwa-install-banner {
        display: none;
        position: fixed;
        top: 0;
        width: 100%;
        background-color: #4CAF50;
        color: white;
        text-align: center;
        padding: 1em;
        z-index: 1000;
    }

    #pwa-install-banner button {
        background-color: #fff;
        color: #4CAF50;
        border: none;
        padding: 0.5em 1em;
        margin-left: 1em;
        cursor: pointer;
    }
</style>