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
            <input type="text" class="form-control" id="p_cpf" name="cpf" placeholder="Enter CPF" required>
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
            <input type="email" class="form-control" id="useremail" name="email" placeholder="Enter email address" required autocomplete="on">
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
            <label for="p_whatsapp">Telefone/WhatsApp <span class="text-danger">*</span></label>
            <input type="text" name="whatsapp" id="p_whatsapp" class="form-control whatsapp" placeholder="+55 (00) 9 0000-0000" required>
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
            <label class="form-label" for="password-input-pastor">Senha <span class="text-danger">*</span></label>
            <div class="position-relative auth-pass-inputgroup">
                <input type="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input-pastor" name="password" aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required autocomplete="off">
                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button"><i class="ri-eye-fill align-middle"></i></button>
                <div class="invalid-feedback">
                    Informe uma senha
                </div>
            </div>
        </div>
        <?= $this->include('login/includes/validacao.php') ?>
        <?= $this->include('login/includes/terms.php') ?>
        <div class="mt-4">
            <button class="btn btn-success w-100" type="submit" id="btn-send-pastor">Criar conta</button>
        </div>
    </form>
</div>