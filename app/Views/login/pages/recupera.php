<!doctype html>
<html lang="pt-BR">

<head>
    <?php echo view('partials/title-meta', ['title' => $titlePage]); ?>
    <?= $this->include('partials/head-css') ?>
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/css/custom/nova-conta.css" rel="stylesheet" type="text/css">
</head>

<body>
    <main class="container-fluid">
        <div class="row">
            <!-- Coluna esquerda -->
            <div class="col-12 col-md-6 left-side" id="auth-particles"></div>
            <!-- Coluna direita com formulário de múltiplas etapas -->
            <div class="col-12 col-md-6 right-side p-5">
                <div class="text-center">
                    <img src="<?= $data['logo'] ?>" class="rounded mb-3" width="90px">
                    <h3 class="text-primary mt-3">Informe seu e-mail para recuperar sua senha</h3>
                    <h3 class="title mt-3 mb-3">Recuperação de senha</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="p-2 mt-4">
                                    <?= form_open('api/v1/public/recover', 'class="formSend"') ?>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Informe seu e-mail</label>
                                        <input type="email" class="form-control form-control-lg" id="email"
                                            placeholder="exemplo@email.com" name="email" required autocomplete="off">
                                    </div>
                                    <div>
                                        <button class="btn btn-dark btn-lg w-100" type="submit">Recuperar senha</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="mb-5">
                        <a href="/" class="fw-semibold text-primary text-decoration-underline">
                            Fazer login
                        </a>
                    </p>
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
    <?= $this->include('partials/vendor-scripts') ?>

    <!-- particles js -->
    <script src="/assets/libs/particles.js/particles.js"></script>

    <!-- particles app js -->
    <script src="/assets/js/pages/particles.app.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Sweet Alerts js -->
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Plugin adicionais -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"
        integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    function formSend() {
        $('.formSend').ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {
                Swal.fire({
                    text: 'Verificando endereço de e-mail',
                    icon: 'info'
                })
            },
            success: function(responseText, statusText, xhr, $form) {
                Swal.fire({
                    text: 'Enviamos um link de recuperação de conta para o seu e-mail!',
                    icon: 'success'
                }).then(function(result) {
                    window.location.href = '/';
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    text: 'Não encotramos uma conta associada a esse endereço de e-mail.',
                    icon: 'error'
                }).then(function(result) {
                    //window.location.href = '/';
                });
            }
        });
    }

    $(document).ready(function() {
        formSend();
    });
    </script>

</body>

</html>