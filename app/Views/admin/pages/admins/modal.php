<!-- Modal -->
<div class="modal fade" id="cadastrarAdmin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastrarAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarAdminLabel">Cadastro de administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <?= form_open_multipart('api/v1/administradores', ['id' => 'form']); ?>
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
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@gmail.com" required>
                    </div>

                    <div class="col-lg-3 mt-2">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control cep" placeholder="00000-000">
                    </div>
                    
                    <div class="col-lg-2 mt-2">
                        <label for="uf">Estado</label>
                        <input type="text" name="uf" id="uf" class="form-control" placeholder="UF" maxlength="2">
                    </div>

                    <div class="col-lg-3 mt-2">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Nome da cidade">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Nome do bairro">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label for="complemento">Endereço</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" placeholder="O restante do endereço">
                    </div>

                    <div class="col-lg-4 mt-2">
                        <label for="tel">Telefone</label>
                        <input type="text" name="tel" id="tel" class="form-control telFixo" placeholder="(00) 0000-0000">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="cel">Celular</label>
                        <input type="text" name="cel" id="cel" class="form-control celular" placeholder="+55 (00) 0 0000-0000">
                    </div>
                    <div class="col-lg-4 mt-2">
                        <label for="password">Informe uma senha <small>Padrão <b>123456</b></small></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="123456" autocomplete="off">
                    </div>
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