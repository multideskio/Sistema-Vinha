<!-- Modal -->
<div class="modal fade" id="cadastrarGerente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastrarGerenteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarGerenteLabel">Cadastro de gerente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <?= form_open_multipart('api/v1/gerentes', ['id' => 'formCad']); ?>
            <div class="modal-body ">
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
                    <div class="col-md-4 mt-2">
                        <label for="tel">Telefone</label>
                        <input type="text" name="tel" id="tel" class="form-control telFixo" placeholder="(00) 0000-0000">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="cel">Celular</label>
                        <input type="text" name="cel" id="cel" class="form-control celular" placeholder="+55 (00) 0 0000-0000">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="password">Informe uma senha <small>Padrão <b>123456</b></small></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="123456">
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



<!-- Modal -->
<div class="modal fade" id="updateGerente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateGerenteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateGerenteLabel">Alterar gerente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <?= form_open('api/v1/gerentes', ['id' => 'formUpdate']); ?>
            <div class="modal-body ">
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="nomeUpdate">Nome</label>
                        <input type="text" id="nomeUpdate" name="nome" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="sobrenomeUpdate">Sobrenome</label>
                        <input type="text" name="sobrenome" id="sobrenomeUpdate" class="form-control" required>
                    </div>
                </div>

                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cpfUpdate">CPF</label>
                        <input type="text" name="cpf" id="cpfUpdate" class="form-control" placeholder="000.000.000-00">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="emailUpdate">E-mail</label>
                        <input type="email" id="emailUpdate" name="email" class="form-control">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cepUpdate">CEP</label>
                        <input type="text" name="cep" id="cepUpdate" class="form-control cep" placeholder="00000-000">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="ufUpdate">Estado</label>
                        <input type="text" name="uf" id="ufUpdate" class="form-control">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="cidadeUpdate">Cidade</label>
                        <input type="text" name="cidade" id="cidadeUpdate" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="bairroUpdate">Bairro</label>
                        <input type="text" name="bairro" id="bairroUpdate" class="form-control">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-8 mt-2">
                        <label for="complementoUpdate">Endereço</label>
                        <input type="text" name="complemento" id="complementoUpdate" class="form-control">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="diaUpdate">Dia do dízimo</label>
                        <input type="number" name="dia" id="diaUpdate" class="form-control" max="31" min="1" required placeholder="1 á 31">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-6 mt-2">
                        <label for="telUpdate">Telefone</label>
                        <input type="text" name="tel" id="telUpdate" class="form-control telFixo">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="celUpdate">Celular</label>
                        <input type="text" name="cel" id="celUpdate" class="form-control celular">
                    </div>
                </div>
                <div class="mt-3">
                    <label for="filepond">Foto</label>
                    <div class="avatar-xl mx-auto">
                        <input type="file" class="filepond filepond-input-circle-2" name="filepond" accept="image/png, image/jpeg, image/gif" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
            </form>
        </div>
    </div>
</div>