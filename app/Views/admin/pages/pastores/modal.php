<div class="modal fade" id="cadastrarPastor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastrarPastorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarPastorLabel">Cadastro de pastores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <?= form_open('api/v1/pastores', ['id' => 'formCad', 'autocomplete' => 'off']) ?>
            <div class="modal-body">
                <div class="row gx-1">
                    <div class="col-md-12">
                        <label for="selectSupervisor" class="text-danger">Selecione um supervisor</label>
                        <select name="selectSupervisor" id="selectSupervisor" class="form-select" required></select>
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="nome" class="text-danger">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control" required placeholder="Primeiro nome">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="sobrenome" class="text-danger">Sobrenome</label>
                        <input type="text" name="sobrenome" id="sobrenome" class="form-control" required placeholder="Sobrenome">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cpf" class="text-danger">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control cpf" placeholder="000.000.000-00" required minlength="14">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="email" class="text-danger">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@gmail.com" autocomplete="off" required>
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control cep" placeholder="00000-000">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="uf">Estado</label>
                        <input type="text" name="uf" id="uf" class="form-control uf" placeholder="UF" maxlength="2">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control cidade" placeholder="Nome da cidade">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control bairro" placeholder="Nome do bairro">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-4 mt-2">
                        <label for="complemento">Endereço</label>
                        <input type="text" name="complemento" id="complemento" class="form-control rua" placeholder="O restante do endereço">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="nascimento">Data de nascimento</label>
                        <input type="date" name="nascimento" id="nascimento" class="form-control" placeholder="O restante do endereço">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="dia" class="text-danger">Dia do dízimo</label>
                        <input type="number" name="dia" id="dia" class="form-control" max="31" min="1" required placeholder="1 á 31">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="tel">Telefone</label>
                        <input type="text" name="tel" id="tel" class="form-control telFixo" placeholder="(00) 0000-0000" autocomplete="off">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="cel" class="text-danger">Celular</label>
                        <input type="text" name="cel" id="cel" class="form-control celular" placeholder="+55 (00) 0 0000-0000" required maxlength="19">
                    </div>
                    <input type="hidden" value="123456" name="password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
            </form>
        </div>
    </div>
</div>