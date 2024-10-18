<!doctype html>
<html lang="pt-BR">

<head>
    <?php echo view('partials/title-meta', ['title' => $titlePage]); ?>
    <?= $this->include('partials/head-css') ?>
    <link rel="stylesheet" href="/assets/css/custom/nova-conta.css" rel="stylesheet" type="text/css">
</head>

<body>
    <main class="container-fluid">
        <div class="row">
            <!-- Coluna esquerda -->
            <div class="col-12 col-md-6 left-side" id="auth-particles"></div>
            <!-- Coluna direita com formulário de múltiplas etapas -->
            <div class="col-12 col-md-6 right-side mt-5 pt-5">
                <div class="text-center">
                    <img src="<?= $data['logo'] ?>" class="rounded mb-3" width="90px">
                    <h3 class="text-primary">Bem-vindo de volta!</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-10 col-xl-10">
                        <div class="card mt-2">
                            <div class="card-body p-4">
                                <div class="p-2 mt-4">
                                    <div class="avatar-lg mx-auto mt-2">
                                        <div class="avatar-title bg-light text-success display-3 rounded-circle">
                                            <i class="ri-checkbox-circle-fill"></i>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-2 text-center">
                                        <h2 class="fw-bold">CONTA CONFIRMADA</h2>
                                        <p class="text-muted mx-4 fs-4">Obrigado por confirmar seu endereço de e-mail
                                        </p>
                                        <div class="mt-4">
                                            <a href="/" class="btn btn-dark w-100 btn-lg">FAZER LOGIN</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                </div>
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
                    document.getElementById('currentYear').textContent = new Date().getFullYear();
                    </script>
                    <div class="p-3 m-3">
                        <a
                            href="https://wakatime.com/badge/user/d4bcc2ba-885d-4896-ab8c-4edfac2362f7/project/40ab49fb-725c-46cc-a663-41a17d032de0"><img
                                src="https://wakatime.com/badge/user/d4bcc2ba-885d-4896-ab8c-4edfac2362f7/project/40ab49fb-725c-46cc-a663-41a17d032de0.svg"
                                alt="wakatime"></a>
                        <img src="https://monitor.conect.app/api/badge/6/uptime/24?style=plastic" alt="Uptime Badge" />
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
    <!-- password-addon init -->
</body>

</html>