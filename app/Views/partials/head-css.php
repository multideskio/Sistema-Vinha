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

<!-- Font bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">

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

    .pace {
        -webkit-pointer-events: none;
        pointer-events: none;

        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    .pace-inactive {
        display: none;
    }

    .pace .pace-progress {
        background: #229fdd;
        position: fixed;
        z-index: 2000;
        top: 0;
        right: 100%;
        width: 100%;
        height: 2px;
    }
</style>