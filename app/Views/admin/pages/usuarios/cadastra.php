<div class="modal fade" id="cadastrarUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastrarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarUsuarioLabel">Cadastro de usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <?= form_open_multipart('api/v1/usuarios') ?>
            <div class="modal-body">
                <div class="row gx-1">
                    <div class="col-md-6">
                        <label for="selectSupervisor" class="text-danger">Selecione um supervisor</label>
                        <select name="selectSupervisor" id="selectSupervisor" class="form-select" required></select>
                    </div>
                    <div class="col-md-6">
                        <label for="grupoPermissao" class="text-danger">Selecione um grupo</label>
                        <select name="grupoPermissao" id="grupoPermissao" class="form-select" required>
                            <option selected value="">Escolha uma opção</option>
                            <option value="2">Usuário</option>
                            <option value="1">Administrador</option>
                        </select>
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
                        <label for="cpf">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control cpf" placeholder="000.000.000-00">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@gmail.com">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control cep" placeholder="00000-000">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="uf">Estado</label>
                        <input type="text" name="uf" id="uf" class="form-control" placeholder="UF" maxlength="2">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Nome da cidade">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Nome do bairro">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-8 mt-2">
                        <label for="complemento">Endereço</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" placeholder="O restante do endereço">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="dia" class="text-danger">Dia do dízimo</label>
                        <input type="number" name="dia" id="dia" class="form-control" max="31" min="1" required placeholder="1 á 31">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="tel">Telefone</label>
                        <input type="text" name="tel" id="tel" class="form-control telFixo" placeholder="(00) 0000-0000">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="cel">Celular</label>
                        <input type="text" name="cel" id="cel" class="form-control celular" placeholder="+55 (00) 0 0000-0000">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="dtNascimento">Dt Nascimento</label>
                        <input type="date" name="dtNascimento" id="dtNascimento" class="form-control" placeholder="00/00/0000">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="senha">Senha</label>
                        <input type="text" name="senha" id="senha" class="form-control" placeholder="Informe uma senha">
                    </div>
                </div>
                <div class="mt-2">
                    <label for="filepond">Foto</label>
                    <div class="avatar-xl mx-auto">
                        <input type="file" class="filepond filepond-input-circle" name="filepond" id="filepond" accept="image/png, image/jpeg, image/gif" />
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