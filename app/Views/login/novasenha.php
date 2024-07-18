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
        .password-contain {
            display: none;
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

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Recuperação de senha</h5>
                                    <p class="text-muted">Informe seu e-mail para recuperar sua senha</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <?php if (session()->getFlashdata('error')) : ?>
                                        <div class="alert alert-danger text-center">
                                            <b><?= session()->getFlashdata('error'); ?></b>
                                        </div>
                                    <?php endif; ?>

                                    <?= form_open('api/v1/public/newpass', 'class="formSend"') ?>
                                    <label class="form-label" for="password-input">Digite sua nova senha</label>
                                    <div class="position-relative mb-3" id="auth-pass-inputgroup">
                                        <input type="password" class="form-control pe-5" placeholder="Digite sua nova senha" name="senha" id="password-input" required autocomplete="off">
                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                    </div>
                                    <div class="p-3 bg-light mb-2 rounded password-contain">
                                        <h5 class="fs-13">A senha deve conter:</h5>
                                        <ul class="list-group ps-3 pe-3">
                                            <li class="text-danger fs-12 mb-2 pass-length">Mínimo <b>8 caracteres</b></li>
                                            <li class="text-danger fs-12 mb-2 pass-lower">Em letras <b>minúsculas</b> (a-z)</li>
                                            <li class="text-danger fs-12 mb-2 pass-upper">Pelo menos letra <b>maiúscula</b> (A-Z)</li>
                                            <li class="text-danger fs-12 mb-2 pass-special">Pelo menos um <b>caractere especial</b></li>
                                            <li class="text-danger fs-12 mb-0 pass-number">Pelo menos um <b>número</b> (0-9)</li>
                                        </ul>
                                    </div>
                                    <input type="hidden" value="<?= $token ?>" name="token">
                                    <div>
                                        <button class="btn btn-success w-100" type="submit" id="btn-send">REDEFINIR SENHA</button>
                                    </div>
                                    </form>

                                </div>
                            </div>
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

    <!--  -->
    <script>
        // Alternar visibilidade da senha
        $('#auth-pass-inputgroup').each(function() {
            $(this).find('#password-addon').each(function() {
                $(this).on('click', function() {
                    const passwordInput = $(this).closest('#auth-pass-inputgroup').find('#password-input');
                    if (passwordInput.attr('type') === 'password') {
                        passwordInput.attr('type', 'text');
                    } else {
                        passwordInput.attr('type', 'password');
                    }
                });
            });
        });

        const passwordInput = $('#password-input');
        const messageBox = $('.password-contain');
        const letter = $('.pass-lower');
        const capital = $('.pass-upper');
        const number = $('.pass-number');
        const special = $('.pass-special'); // Adicionado para caracteres especiais
        const length = $('.pass-length');
        const btnSend = $('#btn-send');

        passwordInput.on('focus', function() {
            messageBox.show();
        });
        passwordInput.on('blur', function() {
            messageBox.hide();
        });
        passwordInput.on('keyup', function() {
            // Validar letras minúsculas
            const lowerCaseLetters = /[a-z]/g;
            if (passwordInput.val().match(lowerCaseLetters)) {
                letter.removeClass('text-danger').addClass('text-success');
            } else {
                letter.removeClass('text-success').addClass('text-danger');
            }

            // Validar letras maiúsculas
            const upperCaseLetters = /[A-Z]/g;
            if (passwordInput.val().match(upperCaseLetters)) {
                capital.removeClass('text-danger').addClass('text-success');
            } else {
                capital.removeClass('text-success').addClass('text-danger');
            }

            // Validar números
            const numbers = /[0-9]/g;
            if (passwordInput.val().match(numbers)) {
                number.removeClass('text-danger').addClass('text-success');
            } else {
                number.removeClass('text-success').addClass('text-danger');
            }

            // Validar caracteres especiais
            const specialCharacters = /[!@#$%^&*(),.?":{}|<>]/g; // Adicionado para caracteres especiais
            if (passwordInput.val().match(specialCharacters)) {
                special.removeClass('text-danger').addClass('text-success');
            } else {
                special.removeClass('text-success').addClass('text-danger');
            }

            // Validar comprimento
            if (passwordInput.val().length >= 8) {
                length.removeClass('text-danger').addClass('text-success');
            } else {
                length.removeClass('text-success').addClass('text-danger');
            }

            // Exibir o botão enviar se todas as condições forem atendidas
            if (passwordInput.val().match(lowerCaseLetters) &&
                passwordInput.val().match(upperCaseLetters) &&
                passwordInput.val().match(numbers) &&
                passwordInput.val().match(specialCharacters) &&
                passwordInput.val().length >= 8) {
                btnSend.show();
            } else {
                btnSend.hide();
            }
        });


        function formSend() {
            $('.formSend').ajaxForm({
                beforeSubmit: function(formData, jqForm, options) {
                    Swal.fire({
                        text: 'Enviando informações...',
                        icon: 'info'
                    })
                },
                success: function(responseText, statusText, xhr, $form) {
                    Swal.fire({
                        text: 'Senha alterada com sucesso!',
                        icon: 'success'
                    }).then(function(result) {
                        window.location.href = '/';
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        text: 'Erro ao atualizar, peça uma nova recuperação de senha.',
                        icon: 'error'
                    }).then(function(result) {
                        //window.location.href = '/';
                    });
                }
            });
        }


        $(document).ready(function() {
            formSend()
        });
    </script>
</body>

</html>