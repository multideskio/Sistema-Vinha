<?= $this->include('partials/main') ?>
<?php
$rowConfig = $data->searchCacheData(1);
?>
<head>
    <?php echo view('partials/title-meta', array('title' => $titlePage )); ?>
    <?= $this->include('partials/head-css') ?>
    <style>
        @media only screen and (max-width: 768px) {
            /* Inserir estilos específicos para dispositivos móveis aqui */
            .mobileMarginTop {
                margin-top: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>
        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row mobileMarginTop">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-2 text-white-50">
                            <div>
                                <a href="/" class="d-inline-block auth-logo">
                                    <img src="/assets/images/logo-light.png" alt="logo" height="35">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary"><?= lang('Paginas.login.titulo') ?></h5>
                                    <p class="text-muted"><?= lang('Paginas.login.descricao') ?></p>
                                </div>
                                <div class="p-2 mt-4">
                                    <?php if (session()->getFlashdata('error')) : ?>
                                        <div class="alert alert-danger text-center">
                                            <b><?= session()->getFlashdata('error'); ?></b>
                                        </div>
                                    <?php endif; ?>
                                    <?= form_open("api/v1/authenticate?redirect=" . (isset($_GET['redirect']) ? $_GET['redirect'] : null)) ?>
                                    <div class="mb-3">
                                        <label for="username" class="form-label"><?= lang('Paginas.login.usuario.label') ?></label>
                                        <input type="email" class="form-control" id="username" placeholder="<?= lang('Paginas.login.usuario.placeholder') ?>" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a href="auth-pass-reset-basic" class="text-muted"><?= lang('Paginas.login.recuperar') ?></a>
                                        </div>
                                        <label class="form-label" for="password-input"><?= lang('Paginas.login.senha.label') ?></label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5 password-input" placeholder="<?= lang('Paginas.login.senha.placeholder') ?>" name="senha" id="password-input" required autocomplete="off">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check"><?= lang('Paginas.login.lembrar') ?></label>
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit"><?= lang('Paginas.login.botao') ?></button>
                                    </div>
                                    <!--
                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title"><?= lang('Paginas.login.opcoes') ?></h5>
                                        </div>
                                        <div>
                                            <a href="<?= site_url('api/v1/google') ?>" class="btn btn-danger waves-effect waves-light">
                                                <i class="ri-google-fill fs-16"></i>oogle
                                            </a>
                                        </div>
                                    </div> -->
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                        <div class="mt-4 text-center">
                            <!--  //site_url('login/nova-conta')  -->
                            <p class="mb-0">
                                <?= lang('Paginas.login.criar.texto') ?> <a href="/nova-conta" class="fw-semibold text-primary text-decoration-underline"><?= lang('Paginas.login.criar.link') ?></a>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <?= $this->include('login/footer.php') ?>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
    <?= $this->include('partials/vendor-scripts') ?>
    <!-- particles js -->
    <script src="/assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="/assets/js/pages/particles.app.js"></script>
    <!-- password-addon init -->
    <script src="/assets/js/pages/password-addon.init.js"></script>
</body>
</html>