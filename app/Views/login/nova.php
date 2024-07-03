<?= $this->include('partials/main') ?>

<head>
    <?php echo view('partials/title-meta', array('title' => $titlePage)); ?>
    <?= $this->include('partials/head-css') ?>
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
                                    <h5 class="text-primary">Criar nova conta</h5>
                                </div>
                                <div class="p-2 mt-4">
                                    <div class="mb-3">
                                        <label for="tipoCadastro" class="form-label">Tipo de cadastro <span class="text-danger">*</span></label>
                                        <select name="tipoCadastro" id="tipoCadastro" class="form-select" required>
                                            <option value="" selected>Escolha uma opção</option>
                                            <option value="1">Pastor</option>
                                            <option value="2">Igreja</option>
                                        </select>
                                    </div>
                                    <div id="divPastor" style="display: none;">

                                        <?= form_open("api/v1/public/pastor", 'class="needs-validation formSend" novalidate') ?>

                                        <div class="mb-3">
                                            <label for="selectSupervisor" class="form-label">Supervisor <span class="text-danger">*</span></label>
                                            <select name="selectSupervisor" id="selectSupervisor" class="form-select" required></select>
                                            <div class="invalid-feedback">
                                                Escolha um supervisor
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Enter first name" required>
                                            <div class="invalid-feedback">
                                                Informe seu nome
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="sobrenome" class="form-label">Sobrenome <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Enter last name" required>
                                            <div class="invalid-feedback">
                                                Informe seu sobrenome
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control cpf" id="cpf" name="cpf" placeholder="Enter CPF" required>
                                            <div class="invalid-feedback">
                                                Informe seu CPF
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="nascimento" class="form-label">Data de nascimento <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="nascimento" name="nascimento" placeholder="<?= date('d/m/Y') ?>" required>
                                            <div class="invalid-feedback">
                                                Informe sua data de nascimento
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">E-mail <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="useremail" name="email" placeholder="Enter email address" required>
                                            <div class="invalid-feedback">
                                                Informe seu e-mail
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="cep">CEP <span class="text-danger">*</span></label>
                                            <input type="text" name="cep" id="cep" class="form-control cep" placeholder="00000-000" required>
                                            <div class="invalid-feedback">
                                                Informe seu CEP
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="uf">Estado <span class="text-danger">*</span></label>
                                            <input type="text" name="uf" id="uf" class="form-control" placeholder="UF" maxlength="2" required>
                                            <div class="invalid-feedback">
                                                Informe seu estado/UF
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cidade">Cidade <span class="text-danger">*</span></label>
                                            <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Nome da cidade" required>
                                            <div class="invalid-feedback">
                                                Informe sua cidade
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="bairro">Bairro <span class="text-danger">*</span></label>
                                            <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Nome do bairro" required>
                                            <div class="invalid-feedback">
                                                Informe seu bairro
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="complemento">Endereço <span class="text-danger">*</span></label>
                                            <input type="text" name="complemento" id="complemento" class="form-control" placeholder="O restante do endereço" required>
                                            <div class="invalid-feedback">
                                                Informe o restante do seu endereço
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="whatsapp">Telefone/WhatsApp <span class="text-danger">*</span></label>
                                            <input type="text" name="whatsapp" id="whatsapp" class="form-control whatsapp" placeholder="+55 (00) 9 0000-0000" required>
                                            <div class="invalid-feedback">
                                                Informe seu número de whatsApp
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="dia">Melhor dia do mês para dizimar<span class="text-danger">*</span></label>
                                            <input type="number" name="dia" id="dia" class="form-control" placeholder="5" max="31" min="1" required>
                                            <div class="invalid-feedback">
                                                Informe o melhor dia para dizimar
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Senha <span class="text-danger">*</span></label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input" name="password" aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                <div class="invalid-feedback">
                                                    Informe uma senha
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="mb-0 fs-12 text-muted fst-italic">Ao se registrar, você concorda com os <a href="/terms" class="text-primary text-decoration-underline fst-normal fw-medium">termos de uso</a></p>
                                        </div>

                                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                            <h5 class="fs-13">Password must contain:</h5>
                                            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
                                            <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)</p>
                                            <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter (A-Z)</p>
                                            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Criar conta</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Já tem uma conta?<a href="/" class="fw-semibold text-primary text-decoration-underline"> Entrar </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                                Desenvolvido com <i class="mdi mdi-heart text-danger"></i> por Multidesk.io
                            </p>
                            <div class="p-3 m-3">
                                <a href="https://wakatime.com/badge/user/d4bcc2ba-885d-4896-ab8c-4edfac2362f7/project/40ab49fb-725c-46cc-a663-41a17d032de0">
                                    <img src="https://wakatime.com/badge/user/d4bcc2ba-885d-4896-ab8c-4edfac2362f7/project/40ab49fb-725c-46cc-a663-41a17d032de0.svg" alt="wakatime">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <?= $this->include('partials/vendor-scripts') ?>

    <!-- particles js -->
    <script src="/assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="/assets/js/pages/particles.app.js"></script>
    <!-- validation init -->
    <script src="/assets/js/pages/form-validation.init.js"></script>
    <!-- password create init -->
    <script src="/assets/js/pages/passowrd-create.init.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Sweet Alerts js -->
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Plugin adicionais -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="/assets/js/custom/functions.min.js"></script>

    <!-- cleave.js -->
    <script src="/assets/libs/cleave.js/cleave.min.js"></script>

    <script>
        function searchSupervisor() {
            var option = "<option selected value=''>Escolha um supervisor</option>";
            $('#selectSupervisor').empty().removeAttr('required');
            $.getJSON(_baseUrl + "api/v1/public/supervisor", function(data) {
                $.each(data, function(index, supervisor) {
                    option += `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
                });
                $('#selectSupervisor').append(option);
                $('#selectSupervisor').attr('required', true).attr('data-choices', true);
                new Choices('#selectSupervisor');
            }).fail(() => {
                Swal.fire({
                    title: 'Ainda não tem supervisores cadastrados',
                    icon: 'error'
                }).then((result) => {
                    history.back();
                });
            });;
        }

        function formataInputs() {
            var cleaveCep = new Cleave('.cep', {
                numericOnly: true,
                delimiters: ['-'],
                blocks: [5, 3],
                uppercase: true
            });

            var cleaveCpf = new Cleave('.cpf', {
                numericOnly: true,
                delimiters: ['.', '.', '-'],
                blocks: [3, 3, 3, 2],
                uppercase: true
            });

            var cleaveCelular = new Cleave('.whatsapp', {
                numericOnly: true,
                delimiters: ['+', ' (', ') ', ' ', '-'],
                blocks: [0, 2, 2, 1, 4, 4]
            });
        }

        function formSend() {
            $('.formSend').ajaxForm({
                beforeSubmit: function(formData, jqForm, options) {

                },
                success: function(responseText, statusText, xhr, $form) {

                    Swal.fire({
                        text: 'Cadastrado com sucesso!',
                        icon: 'success'
                    })
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.messages) {
                        exibirMensagem('error', xhr.responseJSON);
                    } else {
                        exibirMensagem('error', {
                            messages: {
                                error: 'Erro desconhecido.'
                            }
                        });
                    }
                }
            });
        }

        // Função para exibir mensagens
        function exibirMensagem(type, error) {
            // Extrai as mensagens de erro do objeto 'error'
            let messages = error.messages;
            // Inicializa uma string para armazenar as mensagens formatadas
            let errorMessage = '';
            // Itera sobre as mensagens de erro e as formata
            for (let key in messages) {
                if (messages.hasOwnProperty(key)) {
                    errorMessage += `${messages[key]}\n`;
                }
            }

            // Exibe a mensagem de erro formatada
            Swal.fire({
                title: type === 'error' ? "Erro ao incluir registro" : "Mensagem",
                text: errorMessage,
                icon: type
            });

        }

        $(document).ready(function() {
            searchSupervisor();
            formSend();

            $('#tipoCadastro').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue == '1') {
                    formataInputs();
                    $('#divPastor').show();
                    $('#divIgreja').hide();
                } else if (selectedValue == '2') {
                    $('#divPastor').hide();
                    $('#divIgreja').show();
                } else {
                    $('#divPastor').hide();
                    $('#divIgreja').hide();
                }
            });
        });
    </script>

</body>

</html>