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
            <div class="col-12 col-md-6 right-side">
                <div class="text-center">
                    <h3 class="text-primary">Bem-vindo de volta!</h3>
                    <h3 class="title mb-1">Entrar na minha conta</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-10 col-xl-10">
                        <div class="card mt-2">
                            <div class="card-body p-4">
                                <div class="p-2 mt-4">
                                    <?php if (session()->getFlashdata('error')) : ?>
                                    <div class="alert alert-danger text-center">
                                        <b><?= session()->getFlashdata('error'); ?></b>
                                    </div>
                                    <?php endif; ?>
                                    <?= form_open("api/v1/authenticate?redirect=" . (isset($_GET['redirect']) ? $_GET['redirect'] : null)) ?>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Informe seu e-mail</label>
                                        <input type="email" class="form-control" id="username"
                                            placeholder="exemplo@email.com" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a href="/recuperacao" class="text-muted">Recupere sua senha aqui!</a>
                                        </div>
                                        <label class="form-label" for="password-input">Senha</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5 password-input"
                                                placeholder="Digite sua senha" name="senha" id="password-input" required
                                                autocomplete="off">
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                type="button" id="password-addon"><i
                                                    class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check">Manter
                                            conectado</label>
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn btn-dark w-100" type="submit">Entrar</button>
                                    </div>
                                    <p class="text-center w-100 mt-2 mb-2">Ou</p>
                                    <div class="mt-4 text-center">
                                        <a href="/nova-conta" class="btn btn-primary w-100">Criar uma
                                            nova conta</a>
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
    <!-- password-addon init -->
    <script src="/assets/js/pages/password-addon.init.js"></script>
</body>

</html>