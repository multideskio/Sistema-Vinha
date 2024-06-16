<div class="modal fade" id="cadastrarIgreja" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastrarIgrejaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarIgrejaLabel">Cadastro de igrejas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <!-- -->
            <?= form_open('api/v1/igrejas', ['id' => 'formCad'] ) ?>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    <i class="ri-information-line ri-1x"></i> Ao preencher o CNPJ, alguns campos serão preenchidos automaticamente.
                </div>
                <div class="row gx-1">
                    <div class="col-md-12 col-lg-6 mt-2">
                        <label for="selectSupervisor" class="text-danger">Selecione um supervisor</label>
                        <select name="selectSupervisor" id="selectSupervisor" class="form-select" required></select>
                    </div>

                    <div class="col-md-12 col-lg-6 mt-2">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control cnpj" placeholder="00.000.000/0001-00">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="razaosocial" class="text-danger">Razão social</label>
                        <input type="text" id="razaosocial" name="razaosocial" class="form-control" required placeholder="Razão social">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="fantasia" class="text-danger">Nome fantasia</label>
                        <input type="text" name="fantasia" id="fantasia" class="form-control" required placeholder="Nome fantasia">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="email" class="text-danger">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@gmail.com" autocomplete="off" required>
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" class="form-control cep" placeholder="00000-000">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="uf">Estado</label>
                        <input type="text" name="uf" id="uf" class="form-control" placeholder="UF" maxlength="2">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Nome da cidade">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Nome do bairro">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="complemento">Endereço</label>
                        <input type="text" name="complemento" id="complemento" class="form-control" placeholder="O restante do endereço">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="fundacao">Data de fundacao</label>
                        <input type="date" name="fundacao" id="fundacao" class="form-control" placeholder="Data de fundação">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="dia" class="text-danger">Dia do dízimo</label>
                        <input type="number" name="dia" id="dia" class="form-control" max="31" min="1" required placeholder="1 á 31">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="tel">Telefone</label>
                        <input type="text" name="tel" id="tel" class="form-control telFixo" placeholder="(00) 0000-0000" autocomplete="off">
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="cel">Celular</label>
                        <input type="text" name="cel" id="cel" class="form-control celular" placeholder="+55 (00) 0 0000-0000">
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="nome_tesoureiro" class="text-danger">Nome tesoureiro</label>
                        <input type="text" name="nome_tesoureiro" id="nome_tesoureiro" class="form-control" placeholder="Nome tesoureiro" required>
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="sobrenome_tesoureiro" class="text-danger">Sobrenome Tesoureiro</label>
                        <input type="text" name="sobrenome_tesoureiro" id="sobrenome_tesoureiro" class="form-control" placeholder="Sobrenome tesoureiro" required>
                    </div>
                    <div class="col-md-12 col-lg-4 mt-2">
                        <label for="cpf" class="text-danger">CPF Tesoureiro</label>
                        <input type="text" name="cpf" id="cpf" class="form-control cpf" placeholder="000.000.000-00" required>
                    </div>
                </div>
                <div class="row gx-1">
                    <div class="col-md-12 col-lg-5 mt-2">
                        <label for="password" class="text-danger">Informe uma senha <small>Padrão <b>123456</b></small></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="123456" autocomplete="off" required>
                    </div>
                </div>
                <div class="mt-2">
                    <b>Foto</b>
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

<div class="modal fade" id="dadosCnpj" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dadosCnpjLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dadosCnpjLabel">Dados do CNPJ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>CNPJ Raiz</th>
                            <td id="cnpj_raiz"></td>
                        </tr>
                        <tr>
                            <th>Razão Social</th>
                            <td id="razao_social"></td>
                        </tr>
                        <tr>
                            <th>Capital Social</th>
                            <td id="capital_social"></td>
                        </tr>
                        <tr>
                            <th>Porte</th>
                            <td id="porte"></td>
                        </tr>
                        <tr>
                            <th>Natureza Jurídica</th>
                            <td id="natureza_juridica"></td>
                        </tr>
                        <tr>
                            <th>Qualificação do Responsável</th>
                            <td id="qualificacao_responsavel"></td>
                        </tr>
                        <tr>
                            <th>Simples Nacional</th>
                            <td id="simples"></td>
                        </tr>
                        <tr>
                            <th>Atividade Principal</th>
                            <td id="atividade_principal"></td>
                        </tr>
                        <tr>
                            <th>Endereço</th>
                            <td id="endereco"></td>
                        </tr>
                        <tr>
                            <th>Contato</th>
                            <td id="contato"></td>
                        </tr>
                    </tbody>
                </table>

                <h2>Sócios</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>CPF/CNPJ Sócio</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Data de Entrada</th>
                            <th>Qualificação</th>
                            <th>País</th>
                        </tr>
                    </thead>
                    <tbody id="socio_tbody">
                    </tbody>
                </table>

                <h2>Atividades Secundárias</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody id="atividades_tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>