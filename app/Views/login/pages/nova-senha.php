<!doctype html>
<html lang="pt-BR">

<head>
    <?php echo view('partials/title-meta', ['title' => $titlePage]); ?>
    <?= $this->include('partials/head-css') ?>
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/css/custom/nova-conta.css" rel="stylesheet" type="text/css">
    <style>
    .password-contain {
        display: none;
    }
    </style>
</head>

<body>
    <main class="container-fluid">
        <div class="row">
            <!-- Coluna esquerda -->
            <div class="col-lg-6 col-md-6 left-side" id="auth-particles"></div>
            <!-- Coluna direita com formulário de múltiplas etapas -->
            <div class="col-lg-6 col-md-6 right-side p-5">
                <div class="text-center">
                    <img src="<?= $data['logo'] ?>" class="rounded" width="90px">
                    <h3 class="text-primary mt-3">Recuperação de conta</h3>
                    <h3 class="title mt-3 mb-3">Informe a nova senha</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="p-2 mt-4">
                                    <?= form_open('api/v1/public/newpass', 'class="formSend"') ?>
                                    <label class="form-label" for="password-input">Digite sua nova senha</label>
                                    <div class="position-relative mb-3" id="auth-pass-inputgroup">
                                        <input type="password" class="form-control pe-5"
                                            placeholder="Digite sua nova senha" name="senha" id="password-input"
                                            required autocomplete="off">
                                        <button
                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                            type="button" id="password-addon"><i
                                                class="ri-eye-fill align-middle"></i></button>
                                    </div>
                                    <div class="p-3 bg-light mb-2 rounded password-contain">
                                        <h5 class="fs-13">A senha deve conter:</h5>
                                        <ul class="list-group ps-3 pe-3">
                                            <li class="text-danger fs-12 mb-2 pass-length">Mínimo <b>8 caracteres</b>
                                            </li>
                                            <li class="text-danger fs-12 mb-2 pass-lower">Em letras <b>minúsculas</b>
                                                (a-z)</li>
                                            <li class="text-danger fs-12 mb-2 pass-upper">Pelo menos letra
                                                <b>maiúscula</b> (A-Z)
                                            </li>
                                            <li class="text-danger fs-12 mb-2 pass-special">Pelo menos um <b>caractere
                                                    especial</b></li>
                                            <li class="text-danger fs-12 mb-0 pass-number">Pelo menos um <b>número</b>
                                                (0-9)</li>
                                        </ul>
                                    </div>
                                    <input type="hidden" value="<?= $token ?>" name="token">
                                    <div>
                                        <button class="btn btn-success w-100" type="submit" id="btn-send">REDEFINIR
                                            SENHA</button>
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
    <!-- end auth-page-wrapper -->

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

    <!--  -->
    <script>
    // Alternar visibilidade da senha
    $('#auth-pass-inputgroup').each(function() {
        $(this).find('#password-addon').each(function() {
            $(this).on('click', function() {
                const passwordInput = $(this).closest('#auth-pass-inputgroup').find(
                    '#password-input');
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