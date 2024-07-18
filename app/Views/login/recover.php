<?= $this->include('partials/main') ?>

<head>
    <?php echo view('partials/title-meta', array('title' => $titlePage)); ?>
    <?= $this->include('partials/head-css') ?>
    <style>
        @media only screen and (max-width: 768px) {

            /* Inserir estilos específicos para dispositivos móveis aqui */
            .mobileMarginTop {
                margin-top: 60px;
            }
        }
    </style>
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
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
                                    <h5 class="text-primary">Recuperação de senha</h5>
                                    <p class="text-muted">Informe seu e-mail para recuperar sua senha</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <?= form_open('api/v1/public/recover', 'class="formSend"') ?>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Informe seu e-mail</label>
                                            <input type="email" class="form-control" id="email" placeholder="exemplo@email.com" name="email" required autocomplete="off">
                                        </div>
                                        <div>
                                            <button class="btn btn-success w-100" type="submit">RECUPERAR SENHA</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                        <div class="mt-4 text-center">
                            <p class="mb-0">
                                <a href="/" class="fw-semibold text-primary text-decoration-underline">
                                    Fazer login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- footer -->
        <?= $this->include('login/includes/footer.php') ?>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <?= $this->include('partials/vendor-scripts') ?>

    <!-- particles js -->
    <script src="/assets/libs/particles.js/particles.js"></script>

    <!-- particles app js -->
    <script src="/assets/js/pages/particles.app.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Sweet Alerts js -->
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Plugin adicionais -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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

        $(document).ready(function () {
            formSend();
        });
    </script>

</body>

</html>