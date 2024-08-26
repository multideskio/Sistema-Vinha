<div id="divIgreja" style="display: none;">
    <?= form_open("api/v1/public/igreja", 'class="needs-validation formSend" novalidate') ?>
        <div class="mb-3">
            <label for="selectSupervisorIgreja" class="form-label">Supervisor <span class="text-danger">*</span></label>
            <select name="selectSupervisor" id="selectSupervisorIgreja" class="form-select selectSupervisor" required></select>
            <div class="invalid-feedback">
                Escolha um supervisor
            </div>
        </div>
        <div class="mb-3">
            <label for="cnpj" class="form-label">CNPJ <span class="text-danger">*</span><small class="text-muted">Apenas números</small></label>
            <input type="text" class="form-control cnpj" id="cnpj" name="cnpj" placeholder="Enter cnpj" required>
            <div class="invalid-feedback">
                Informe seu CPF
            </div>
        </div>
        <div class="mb-3">
            <label for="razaosocial" class="form-label">Razão social <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="razaosocial" name="razaosocial" placeholder="Enter corporate reason" required>
            <div class="invalid-feedback">
                Informe a razão social
            </div>
        </div>
        <div class="mb-3">
            <label for="fantasia" class="form-label">Nome Fantasia <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="fantasia" name="fantasia" placeholder="Enter fantasy name" required>
            <div class="invalid-feedback">
                Informe o nome fantasia
            </div>
        </div>
        <div class="mb-3">
            <label for="useremailIgreja" class="form-label">E-mail <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="useremailIgreja" name="email" placeholder="Enter email address" required autocomplete="on">
            <div class="invalid-feedback">
                Informe seu e-mail
            </div>
        </div>
        <div class="mb-3">
            <label for="i_cep">CEP <span class="text-danger">*</span></label>
            <input type="text" name="cep" id="i_cep" class="form-control cep" placeholder="00000-000" required>
            <div class="invalid-feedback">
                Informe seu CEP
            </div>
        </div>
        <div class="mb-3">
            <label for="ufIgreja">Estado <span class="text-danger">*</span></label>
            <input type="text" name="uf" id="ufIgreja" class="form-control uf" placeholder="UF" maxlength="2" required>
            <div class="invalid-feedback">
                Informe seu estado/UF
            </div>
        </div>
        <div class="mb-3">
            <label for="cidadeIgreja">Cidade <span class="text-danger">*</span></label>
            <input type="text" name="cidade" id="cidadeIgreja" class="form-control cidade" placeholder="Nome da cidade" required>
            <div class="invalid-feedback">
                Informe sua cidade
            </div>
        </div>
        <div class="mb-3">
            <label for="bairroIgreja">Bairro <span class="text-danger">*</span></label>
            <input type="text" name="bairro" id="bairroIgreja" class="form-control bairro" placeholder="Nome do bairro" required>
            <div class="invalid-feedback">
                Informe seu bairro
            </div>
        </div>
        <div class="mb-3">
            <label for="complementoIgreja">Endereço <span class="text-danger">*</span></label>
            <input type="text" name="complemento" id="complementoIgreja" class="form-control rua" placeholder="O restante do endereço" required>
            <div class="invalid-feedback">
                Informe o restante do seu endereço
            </div>
        </div>
        <div class="mb-3">
            <label for="i_whatsapp">Telefone/WhatsApp <span class="text-danger">*</span></label>
            <input type="text" name="whatsapp" id="i_whatsapp" class="form-control whatsapp" placeholder="+55 (00) 9 0000-0000" required>
            <div class="invalid-feedback">
                Informe seu número de whatsApp
            </div>
        </div>
        <div class="mb-3">
            <label for="diaIgreja">Melhor dia do mês para dizimar<span class="text-danger">*</span></label>
            <input type="number" name="dia" id="diaIgreja" class="form-control" placeholder="5" max="31" min="1" required>
            <div class="invalid-feedback">
                Informe o melhor dia para dizimar
            </div>
        </div>
        <div class="mb-3">
            <label for="nomeTesoureiro">Primeiro nome tesoureiro <span class="text-danger">*</span></label>
            <input type="text" name="nomeTesoureiro" id="nomeTesoureiro" class="form-control" placeholder="First name treasure" required>
            <div class="invalid-feedback">
                Informe oprimeiro nome tesoureiro
            </div>
        </div>
        <div class="mb-3">
            <label for="sobreTesoureiro">Sobrenome tesoureiro <span class="text-danger">*</span></label>
            <input type="text" name="sobreTesoureiro" id="sobreTesoureiro" class="form-control" placeholder="Last name treasure" required>
            <div class="invalid-feedback">
                Informe o sobrenome tesoureiro
            </div>
        </div>
        <div class="mb-3">
            <label for="i_cpf" class="form-label">CPF Tesoureiro<span class="text-danger">*</span></label>
            <input type="text" class="form-control cpf" id="i_cpf" name="cpfTesoureiro" placeholder="Enter CPF" required>
            <div class="invalid-feedback">
                Informe o CPF do tesoureiro
            </div>
        </div>
        <div class="mb-3">
            <label for="dataFundacao" class="form-label">Data de fundação<span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="dataFundacao" name="dataFundacao" placeholder="Date of foundation" required>
            <div class="invalid-feedback">
                Informe a data da fundação
            </div>
        </div>
        <input type="hidden" name="password" value="123456">
        <?= $this->include('login/includes/terms.php') ?>
        <div class="mt-4">
            <button class="btn btn-success w-100" type="submit">Criar conta</button>
        </div>
    </form>
</div>