<div class="row justify-content-center pastorCad" style="display: none;">
    <div class="col-md-10 col-lg-10">
        <div class="card mt-2">
            <div class="card-body p-4">
                <div class="p-2">
                    <?= form_open("api/v1/public/pastor", 'class="needs-validation formSend" novalidate id="multi-step-form-2"') ?>
                    <!-- Etapas do Formulário Pastor -->
                    <div class="step active pastor-step" id="pastor-step-1">
                        <h5 class="mb-4 fw-bold">Informações Iniciais:</h5>
                        <div class="row mb-2 mb-sm-3">
                            <div class="col-md-6 mb-sm-2">
                                <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                    placeholder="Enter first name" required>
                                <div class="invalid-feedback">
                                    Informe seu nome
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="sobrenome" class="form-label">Sobrenome <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sobrenome" name="sobrenome"
                                    placeholder="Enter last name" required>
                                <div class="invalid-feedback">
                                    Informe seu sobrenome
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2 mb-sm-3">
                            <div class="col-md-6 mb-sm-2">
                                <label for="p_cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control cpf" id="p_cpf" name="cpf"
                                    placeholder="Enter CPF" required>
                                <div class="invalid-feedback">
                                    Informe seu CPF
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="nascimento" class="form-label">Data de nascimento <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="nascimento" name="nascimento"
                                    placeholder="<?= date('d/m/Y') ?>" required>
                                <div class="invalid-feedback">
                                    Informe sua data de nascimento
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2 mb-sm-3">
                            <div class="col-md-6 mb-sm-2">
                                <label for="userEmailPastor" class="form-label">E-mail <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control searchEmail" name="email"
                                    placeholder="Enter email address" required autocomplete="on" id="userEmailPastor">
                                <div class="invalid-feedback">
                                    Informe seu e-mail
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="selectSupervisor" class="form-label">Supervisor <span
                                        class="text-danger">*</span></label>
                                <select name="selectSupervisor" id="selectSupervisor"
                                    class="form-select selectSupervisor" required>
                                    <option value="">Carregando...</option>
                                </select>
                                <div class="invalid-feedback">
                                    Escolha um supervisor
                                </div>
                            </div>
                        </div>
                        <!-- Primeira Etapa do formulário pastor -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-dark" onclick="nextStepPastor()">Próximo</button>
                        </div>
                    </div>
                    <div class="step pastor-step" id="pastor-step-2">
                        <h5>Endereço:</h5>
                        <div class="row mb-sm-2">
                            <div class="col-md-6  mb-sm-2">
                                <label for="pastorCep" class="form-label">CEP <span class="text-danger">*</span></label>
                                <input type="text" name="cep" id="pastorCep" class="form-control cep" placeholder=""
                                    required>
                                <div class="invalid-feedback">
                                    Informe seu CEP
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="ufPastor" class="form-label">Estado <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="uf" id="ufPastor" class="form-control uf" placeholder=""
                                    maxlength="2" required>
                                <div class="invalid-feedback">
                                    Informe seu estado/UF
                                </div>
                            </div>
                        </div>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="cidadePastor" class="form-label">Cidade <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="cidade" id="cidadePastor" class="form-control cidade"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe sua cidade
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="bairroPastor" class="form-label">Bairro <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="bairro" id="bairroPastor" class="form-control bairro"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe seu bairro
                                </div>
                            </div>
                        </div>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="ruaPastor" class="form-label">Rua <span class="text-danger">*</span></label>
                                <input type="text" name="ruaPastor" id="ruaPastor" class="form-control rua"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe a sua rua
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="numeroPastor" class="form-label">Número <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="numeroPastor" id="numeroPastor" class="form-control"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe o numero da sua casa
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label for="complementoPastor" class="form-label">Complemento<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="complementoPastor" id="complementoPastor" class="form-control"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe o restante do seu endereço
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="paisPastor" value="Brasil">
                        <!-- Segunda Etapa do formulário pastor -->
                        <div class="step-buttons">
                            <button type="button" class="btn btn-danger btn-lg"
                                onclick="prevStepPastor()">Voltar</button>
                            <button type="button" class="btn btn-dark btn-lg"
                                onclick="nextStepPastor()">Próximo</button>
                        </div>
                    </div>
                    <div class="step pastor-step" id="pastor-step-3">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-sm-3">
                                <label for="phonePastor" class="form-label">Telefone/WhatsApp *</label>
                                <div class="row">
                                    <input type="tel" id="phonePastor" name="phone" class="form-control phone" required
                                        autocomplete="off">
                                </div>
                                <div class="invalid-feedback">
                                    Informe seu número de WhatsApp
                                </div>
                                <!-- Input onde o DDI será inserido -->
                                <input type="hidden" id="full_phone1" name="full_phone" class="full_phone">
                            </div>
                            <div class="col-md-6">
                                <label for="diaPastor" class="form-label">Melhor dia do mês para dizimar<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="dia" id="diaPastor" class="form-control" placeholder="5"
                                    max="31" min="1" required>
                                <div class="invalid-feedback">
                                    Informe o melhor dia para dizimar
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="passwordPastor" class="form-label">Informe uma senha<span
                                        class="text-danger">*</span></label>
                                <div class="position-relative mb-3 auth-pass-inputgroup">
                                    <input type="password" class="form-control pe-5 password-input"
                                        placeholder="Digite sua nova senha" name="password" required autocomplete="off"
                                        id="passwordPastor">
                                    <button
                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                        type="button">
                                        <i class="ri-eye-fill align-middle"></i></button>
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
                            </div>
                        </div>
                        <!-- Terceira Etapa do formulário pastor -->
                        <div class="step-buttons">
                            <button type="button" class="btn btn-danger btn-lg"
                                onclick="prevStepPastor()">Voltar</button>
                            <button type="submit" class="btn btn-dark btn-lg btn-send">Cadastrar</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>