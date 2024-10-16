<div id="divPastor" style="display: none;">
    <?= form_open("api/v1/public/pastor", 'class="needs-validation formSend" novalidate') ?>
    <div class="mb-3">
        <label for="selectSupervisor" class="form-label">Supervisor <span class="text-danger">*</span></label>
        <select name="selectSupervisor" id="selectSupervisor" class="form-select selectSupervisor" required>
            <option value="">Carregando...</option>
        </select>
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
        <label for="p_cpf" class="form-label">CPF <span class="text-danger">*</span></label>
        <input type="text" class="form-control cpf" id="p_cpf" name="cpf" placeholder="Enter CPF" required>
        <div class="invalid-feedback">
            Informe seu CPF
        </div>
    </div>

    <div class="mb-3">
        <label for="nascimento" class="form-label">Data de nascimento <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="nascimento" name="nascimento" placeholder="<?= date('d/m/Y') ?>"
            required>
        <div class="invalid-feedback">
            Informe sua data de nascimento
        </div>
    </div>

    <div class="mb-3">
        <label for="useremail" class="form-label">E-mail <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="useremail" name="email" placeholder="Enter email address" required
            autocomplete="on">
        <div class="invalid-feedback">
            Informe seu e-mail
        </div>
    </div>

    <div class="mb-3">
        <label for="p_cep">CEP <span class="text-danger">*</span></label>
        <input type="text" name="cep" id="p_cep" class="form-control cep" placeholder="00000-000" required>
        <div class="invalid-feedback">
            Informe seu CEP
        </div>
    </div>

    <div class="mb-3">
        <label for="uf">Estado <span class="text-danger">*</span></label>
        <input type="text" name="uf" id="uf" class="form-control uf" placeholder="UF" maxlength="2" required>
        <div class="invalid-feedback">
            Informe seu estado/UF
        </div>
    </div>

    <div class="mb-3">
        <label for="cidade">Cidade <span class="text-danger">*</span></label>
        <input type="text" name="cidade" id="cidade" class="form-control cidade" placeholder="Nome da cidade" required>
        <div class="invalid-feedback">
            Informe sua cidade
        </div>
    </div>

    <div class="mb-3">
        <label for="bairro">Bairro <span class="text-danger">*</span></label>
        <input type="text" name="bairro" id="bairro" class="form-control bairro" placeholder="Nome do bairro" required>
        <div class="invalid-feedback">
            Informe seu bairro
        </div>
    </div>
    <div class="mb-3">
        <label for="ruaPastor">Rua <span class="text-danger">*</span></label>
        <input type="text" name="ruaPastor" id="ruaPastor" class="form-control ruaPastor" placeholder="Sua rua"
            required>
        <div class="invalid-feedback">
            Informe a sua rua
        </div>
    </div>
    <div class="mb-3">
        <label for="numeroPastor">Número <span class="text-danger">*</span></label>
        <input type="text" name="numeroPastor" id="numeroPastor" class="form-control rua"
            placeholder="Número da sua casa" required>
        <div class="invalid-feedback">
            Informe o numero da sua casa
        </div>
    </div>

    <div class="mb-3">
        <label for="complemento">Complemento <span class="text-danger">*</span></label>
        <input type="text" name="complemento" id="complemento" class="form-control rua"
            placeholder="O restante do endereço" required>
        <div class="invalid-feedback">
            Informe o restante do seu endereço
        </div>
    </div>

    <input type="hidden" name="paisPastor" value="Brasil">

    <div class="mb-3">
        <label for="p_whatsapp">Telefone/WhatsApp <span class="text-danger">*</span></label>
        <div class="input-group" data-input-flag>
            <button class="btn btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/assets/images/flags/br.svg" alt="flag img" height="20" class="country-flagimg rounded">
                <span class="ms-2 country-codeno">+ 55</span>
            </button>
            <input type="text" class="form-control rounded-end flag-input" name="p_whatsapp" id="p_whatsapp"
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
        <label for="dia">Melhor dia do mês para dizimar<span class="text-danger">*</span></label>
        <input type="number" name="dia" id="dia" class="form-control" placeholder="5" max="31" min="1" required>
        <div class="invalid-feedback">
            Informe o melhor dia para dizimar
        </div>
    </div>
    <input type="hidden" value="123456" name="password">
    <?= $this->include('login/includes/terms.php') ?>
    <div class="mt-4">
        <button class="btn btn-success w-100" type="submit" id="btn-send-pastor">Criar conta</button>
    </div>
    </form>
</div>