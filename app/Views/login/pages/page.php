<!doctype html>
<html lang="pt-BR">

<head>
    <?php echo view('partials/title-meta', ['title' => $titlePage]); ?>
    <?= $this->include('partials/head-css') ?>
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <link rel="stylesheet" href="/assets/css/custom/nova-conta.css" rel="stylesheet" type="text/css">
</head>

<body>
    <main class="container-fluid">
        <div class="row">
            <!-- Coluna esquerda -->
            <div class="col-lg-6 left-side" id="auth-particles"></div>

            <!-- Coluna direita com formulário de múltiplas etapas -->
            <div class="col-lg-6 right-side">
                <h3 class="text-center title mb-3">Crie uma conta</h3>
                <!-- Barra de progresso -->
                <p class="text-center fs-4 mb-4 fw-100">Qual cadastro iremos fazer hoje?</p>
                <div class="row mb-2 ps-5 pe-5">
                    <div class="col-sm-6 d-grid gap-2">
                        <button class="btn btnMostraPastor text-white color-btn btn-dark">
                            Cadastro de Pastor
                        </button>
                    </div>
                    <div class="col-sm-6 d-grid gap-2">
                        <button class="btn btnMostraIgreja text-white color-btn btn-dark">
                            Cadastro de igreja
                        </button>
                    </div>
                </div>



                <?= $this->include("login/forms/igreja") ?>

                <div class="row">
                    <div class="col-lg-12 text-center mb-3 mt-4">
                        <p>Já tem uma conta? <a href="<?= site_url() ?>" class="text-info">Faça o
                                login aqui</a>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted t-foot">&copy;
                                <span id="currentYear"></span> Desenvolvido com
                                <i class="mdi mdi-heart text-danger"></i> por Multidesk.io -
                                <b>Time:</b> {elapsed_time} - <b>Memory:</b> {memory_usage}
                            </p>
                            <script>
                            document.getElementById('currentYear').textContent = new Date()
                                .getFullYear();
                            </script>
                            <div class="p-3 m-3">
                                <a
                                    href="https://wakatime.com/badge/user/d4bcc2ba-885d-4896-ab8c-4edfac2362f7/project/40ab49fb-725c-46cc-a663-41a17d032de0"><img
                                        src="https://wakatime.com/badge/user/d4bcc2ba-885d-4896-ab8c-4edfac2362f7/project/40ab49fb-725c-46cc-a663-41a17d032de0.svg"
                                        alt="wakatime"></a>
                                <img src="https://monitor.conect.app/api/badge/6/uptime/24?style=plastic"
                                    alt="Uptime Badge" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- end auth-page-wrapper -->
    <?= $this->include('partials/vendor-scripts') ?>
    <!-- particles js -->
    <script src="/assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="/assets/js/pages/particles.app.js"></script>
    <!-- validation init -->
    <script src="/assets/js/pages/form-validation.init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Sweet Alerts js -->
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <!-- Plugin adicionais -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"
        integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/assets/js/custom/functions.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <!-- intl-tel-input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <!-- Utils script (para validação de números) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
    <script src="/assets/js/custom/newlogin.js"></script>
</body>

</html>