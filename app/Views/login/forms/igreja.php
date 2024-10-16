<div class="row justify-content-center igrejaCad" style="display: none;">
    <div class="col-md-12 col-lg-10 col-xl-10">
        <div class="card mt-2">
            <div class="card-body p-4">
                <div class="p-2 mt-4">
                    <?= form_open("api/v1/public/igreja", 'class="needs-validation formSend" novalidate id="multi-step-form"') ?>
                    <!-- Primeira Etapa -->
                    <div class="step active" id="step-1">
                        <h5 class="mb-4">Informações Iniciais:</h5>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="cnpj" class="form-label">CNPJ/CPF<span class="text-danger">*</span>
                                    <small class="text-muted">Apenas números</small></label>
                                <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="" required
                                    minlength="11" maxlength="14">
                                <div class="invalid-feedback">
                                    Informe seu CNPJ/CPF
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="razaosocial" class="form-label">Razão social <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="razaosocial" name="razaosocial"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe a razão social
                                </div>
                            </div>
                        </div>

                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="fantasia" class="form-label">Nome Fantasia <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fantasia" name="fantasia" placeholder=""
                                    required>
                                <div class="invalid-feedback">
                                    Informe o nome fantasia
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="selectSupervisorIgreja" class="form-label">Supervisor<span
                                        class="text-danger">*</span></label>
                                <select name="selectSupervisor" id="selectSupervisorIgreja"
                                    class="form-select selectSupervisor" required></select>
                                <div class="invalid-feedback">
                                    Escolha um supervisor
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 mb-sm-2">
                            <div class="col-md-12">
                                <label for="useremailIgreja" class="form-label">E-mail <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="useremailIgreja" name="useremailIgreja"
                                    placeholder="exemplo@gmail.com" required autocomplete="off" value="">
                                <div class="invalid-feedback">
                                    Informe seu e-mail
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-dark" onclick="nextStep()">Próximo</button>
                        </div>
                    </div>

                    <!-- Segunda Etapa -->
                    <div class="step" id="step-2">
                        <h5>Endereço:</h5>
                        <div class="row mb-sm-2">
                            <div class="col-md-6  mb-sm-2">
                                <label for="i_cep" class="form-label">CEP <span class="text-danger">*</span></label>
                                <input type="text" name="cep" id="i_cep" class="form-control cep" placeholder=""
                                    required>
                                <div class="invalid-feedback">
                                    Informe seu CEP
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="ufIgreja" class="form-label">Estado <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="uf" id="ufIgreja" class="form-control uf" placeholder=""
                                    maxlength="2" required>
                                <div class="invalid-feedback">
                                    Informe seu estado/UF
                                </div>
                            </div>
                        </div>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="cidadeIgreja" class="form-label">Cidade <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="cidade" id="cidadeIgreja" class="form-control cidade"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe sua cidade
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="bairroIgreja" class="form-label">Bairro <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="bairro" id="bairroIgreja" class="form-control bairro"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe seu bairro
                                </div>
                            </div>
                        </div>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="ruaIgreja" class="form-label">Rua <span class="text-danger">*</span></label>
                                <input type="text" name="ruaIgreja" id="ruaIgreja" class="form-control rua"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe a sua rua
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="numeroIgreja" class="form-label">Número <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="numeroIgreja" id="numeroIgreja"
                                    class="form-control numeroIgreja" placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe o numero da sua casa
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label for="complementoIgreja" class="form-label">Complemento<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="complementoIgreja" id="complementoIgreja" class="form-control"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe o restante do seu endereço
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="paisIgreja" value="Brasil">
                        <div class="step-buttons">
                            <button type="button" class="btn btn-danger btn-lg" onclick="prevStep()">Voltar</button>
                            <button type="button" class="btn btn-dark btn-lg" onclick="nextStep()">Próximo</button>
                        </div>
                    </div>

                    <!-- Terceira Etapa -->
                    <div class="step" id="step-3">
                        <h5>Informações do Tesoureiro:</h5>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="phone" class="form-label"> Telefone/WhatsApp <span
                                        class="phone">*</span></label>
                                <div class="row">
                                    <input type="tel" id="phone" name="phone" class="form-control phone" required>
                                </div>
                                <div class="invalid-feedback">
                                    Informe seu número de WhatsApp
                                </div>
                                <!-- Input onde o DDI será inserido -->
                                <input type="hidden" id="full_phone" name="full_phone">
                            </div>
                            <div class="col-md-6">
                                <label for="diaIgreja" class="form-label">Melhor dia do mês para dizimar<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="dia" id="diaIgreja" class="form-control" placeholder="5"
                                    max="31" min="1" required>
                                <div class="invalid-feedback">
                                    Informe o melhor dia para dizimar
                                </div>
                            </div>
                        </div>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="nomeTesoureiro" class="form-label">Primeiro nome tesoureiro <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nomeTesoureiro" id="nomeTesoureiro" class="form-control"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe oprimeiro nome tesoureiro
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="sobreTesoureiro" class="form-label">Sobrenome tesoureiro <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="sobreTesoureiro" id="sobreTesoureiro" class="form-control"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe o sobrenome tesoureiro
                                </div>
                            </div>
                        </div>
                        <div class="row mb-sm-2">
                            <div class="col-md-6 mb-sm-2">
                                <label for="i_cpf" class="form-label">CPF Tesoureiro<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control cpf" id="i_cpf" name="cpfTesoureiro"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe o CPF do tesoureiro
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="dataFundacao" class="form-label">Data de fundação<span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dataFundacao" name="dataFundacao"
                                    placeholder="" required>
                                <div class="invalid-feedback">
                                    Informe a data da fundação
                                </div>
                            </div>
                        </div>

                        <div class="row mb-sm-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Informe uma senha<span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder=""
                                    required autocomplete="off">
                                <div class="invalid-feedback">
                                    A senha não é valída
                                </div>
                            </div>
                        </div>

                        <div class="step-buttons">
                            <button type="button" class="btn btn-danger btn-lg" onclick="prevStep()">Voltar</button>
                            <button type="submit" class="btn btn-dark btn-lg">Cadastrar</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>