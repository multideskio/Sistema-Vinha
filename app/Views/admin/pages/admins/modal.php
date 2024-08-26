<!-- Modal -->
<div class="modal fade" id="cadastrarAdmin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastrarAdminLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarAdminLabel">Cadastro de administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <?= form_open_multipart('api/v1/administradores', ['id' => 'form', 'autocomplete' =>  'off']); ?>
            <div class="modal-body">
                <div class="row gx-2">
                    <div class="col-lg-6 mt-2">
                        <label for="nome" class="text-danger">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control" required placeholder="Primeiro nome">
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="sobrenome" class="text-danger">Sobrenome</label>
                        <input type="text" name="sobrenome" id="sobrenome" class="form-control" required placeholder="Sobrenome">
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="cpf">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control cpf" placeholder="000.000.000-00">
                    </div>
                    <div class="col-lg-6 mt-2">
                        <label for="email" class="text-danger">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@gmail.com" required autocomplete="off">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control cep" placeholder="00000-000">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="uf">Estado</label>
                        <input type="text" name="uf" id="uf" class="form-control uf" placeholder="UF" maxlength="2">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control cidade" placeholder="Nome da cidade">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control bairro" placeholder="Nome do bairro">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="complemento">Endereço</label>
                        <input type="text" name="complemento" id="complemento" class="form-control rua" placeholder="O restante do endereço">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="cel" class="text-danger">Celular</label>
                        <input type="text" name="cel" id="cel" class="form-control celular" placeholder="+55 (00) 0 0000-0000" required>
                    </div>
                    <input type="hidden" name="password" value="123456">
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
