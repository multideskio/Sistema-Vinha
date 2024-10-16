<div id="divIgreja" style="display: none;">
    <?= form_open("api/v1/public/igreja", 'class="needs-validation formSend" novalidate') ?>
    <div class="mb-3">
        <label for="selectSupervisorIgreja" class="form-label">Supervisor <span class="text-danger">*</span></label>
        <select name="selectSupervisor" id="selectSupervisorIgreja" class="form-select selectSupervisor"
            required></select>
        <div class="invalid-feedback">
            Escolha um supervisor
        </div>
    </div>
    <div class="mb-3">
        <label for="cnpj" class="form-label">CNPJ/CPF<span class="text-danger">*</span>
            <small class="text-muted">Apenas números</small></label>
        <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="CPF ou CNPJ" required minlength="11"
            maxlength="14">
        <div class="invalid-feedback">
            Informe seu CPF
        </div>
    </div>
    <div class="mb-3">
        <label for="razaosocial" class="form-label">Razão social <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="razaosocial" name="razaosocial" placeholder="Enter corporate reason"
            required>
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
        <input type="email" class="form-control" id="useremailIgreja" name="email" placeholder="Enter email address"
            required autocomplete="on">
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
        <input type="text" name="cidade" id="cidadeIgreja" class="form-control cidade" placeholder="Nome da cidade"
            required>
        <div class="invalid-feedback">
            Informe sua cidade
        </div>
    </div>
    <div class="mb-3">
        <label for="bairroIgreja">Bairro <span class="text-danger">*</span></label>
        <input type="text" name="bairro" id="bairroIgreja" class="form-control bairro" placeholder="Nome do bairro"
            required>
        <div class="invalid-feedback">
            Informe seu bairro
        </div>
    </div>
    <div class="mb-3">
        <label for="ruaIgreja">Rua <span class="text-danger">*</span></label>
        <input type="text" name="ruaIgreja" id="ruaIgreja" class="form-control ruaIgreja" placeholder="Sua rua"
            required>
        <div class="invalid-feedback">
            Informe a sua rua
        </div>
    </div>
    <div class="mb-3">
        <label for="numeroIgreja">Número <span class="text-danger">*</span></label>
        <input type="text" name="numeroIgreja" id="numeroIgreja" class="form-control numeroIgreja"
            placeholder="Número da sua casa" required>
        <div class="invalid-feedback">
            Informe o numero da sua casa
        </div>
    </div>
    <div class="mb-3">
        <label for="complementoIgreja">Complemento<span class="text-danger">*</span></label>
        <input type="text" name="complementoIgreja" id="complementoIgreja" class="form-control rua"
            placeholder="O restante do endereço" required>
        <div class="invalid-feedback">
            Informe o restante do seu endereço
        </div>
    </div>

    <input type="hidden" name="paisIgreja" value="Brasil">
    <div class="mb-3">
        <label for="i_whatsapp">Telefone/WhatsApp<span class="text-danger">*</span></label>
        <div class="input-group" data-input-flag>
            <button class="btn btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/assets/images/flags/br.svg" alt="flag img" height="20" class="country-flagimg rounded">
                <span class="ms-2 country-codeno">+ 55</span>
            </button>
            <input type="text" class="form-control rounded-end flag-input" name="i_whatsapp" id="i_whatsapp"
                placeholder="Enter number"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
            <div class="dropdown-menu w-100">
                <div class="p-2 px-3 pt-1 searchlist-input">
                    <input type="text" class="form-control form-control-sm border search-countryList"
                        placeholder="Search country name or country code..." />
                </div>
                <ul class="list-unstyled dropdown-menu-list mb-0"></ul>
            </div>
        </div>
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
        <input type="text" name="nomeTesoureiro" id="nomeTesoureiro" class="form-control"
            placeholder="First name treasure" required>
        <div class="invalid-feedback">
            Informe oprimeiro nome tesoureiro
        </div>
    </div>
    <div class="mb-3">
        <label for="sobreTesoureiro">Sobrenome tesoureiro <span class="text-danger">*</span></label>
        <input type="text" name="sobreTesoureiro" id="sobreTesoureiro" class="form-control"
            placeholder="Last name treasure" required>
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
        <input type="date" class="form-control" id="dataFundacao" name="dataFundacao" placeholder="Date of foundation"
            required>
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