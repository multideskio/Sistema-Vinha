<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>
<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">Gerenciamento de igrejas</h1>
</div>
<div class="row">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <?= form_open_multipart('api/v1/igrejas/update/upload/' . $idSearch, 'class="formUpload"') ?>
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <img src="https://placehold.co/50/00000/FFF?text=V" id="fotoPerfil" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                            <input id="profile-img-file-input" type="file" class="profile-img-file-input" name="foto">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    </form>
                    <h5 class="fs-16 mb-1" id="viewNameUser">Carregando...</h5>
                    <p class="text-muted mb-0">IGREJA</p>
                </div>
            </div>
        </div>
        <!--end card-->
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">Redes sociais</h5>
                    </div>
                </div>
                <?= form_open('api/v1/igrejas/update/links/' . $idSearch, 'class="formTexts"') ?>
                <div class="alert alert-success alertAlterado bg-success text-white" role="alert" style="display: none;">
                    <b>Alterado com sucesso</b>
                </div>
                <div class="mb-3 d-flex">
                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                        <span class="avatar-title rounded-circle fs-16 bg-primary">
                            <i class="ri-facebook-fill"></i>
                        </span>
                    </div>
                    <input type="url" class="form-control enviaLinks" name="linkFacebook" id="facebook" placeholder="Link facebook" value="">
                </div>
                <div class="mb-3 d-flex">
                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                        <span class="avatar-title rounded-circle fs-16 bg-dark">
                            <i class="ri-global-fill"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control enviaLinks" id="website" name="linkWebsite" placeholder="Link site" value="">
                </div>
                <div class="mb-3 d-flex">
                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                        <span class="avatar-title rounded-circle fs-16 bg-danger">
                            <i class="ri-instagram-fill"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control enviaLinks" id="instagram" name="linkInstagram" placeholder="Link instagram" value="">
                </div>
                </form>
            </div>
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                            <i class="fas fa-home"></i> Dados do perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#transacoes" role="tab">
                            <i class="far fa-envelope"></i> Transações do usuário
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#preferencias" role="tab">
                            <i class="far fa-envelope"></i> Preferências
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#preferencias" role="tab">
                            <i class="far fa-envelope"></i> Logs
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <?= form_open('api/v1/igrejas/' . $idSearch, 'class="formGeral" autocomplete="off"') ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="selectSupervisor" class="text-danger">Selecione um supervisor</label>
                                    <select name="selectSupervisor" id="selectSupervisor" class="form-select" required></select>
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cnpj" class="form-label text-danger">CNPJ</label>
                                    <input type="text" class="form-control cnpj" id="cnpj" name="cnpj" placeholder="CNPJ..." required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="razaosocial" class="form-label text-danger">Razão social</label>
                                    <input type="text" class="form-control" id="razaosocial" name="razaosocial" placeholder="Razão social..." required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="fantasia" class="form-label text-danger">Nome fantasia</label>
                                    <input type="text" class="form-control" id="fantasia" name="fantasia" placeholder="Nome fantasia..." required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="nome" class="form-label text-danger">Nome tesoureiro</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome..." required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="sobrenome" class="form-label text-danger">Sobre-nome tesoureiro</label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Sobrenome..." required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cpf" class="form-label">CPF tesoureiro</label>
                                    <input type="text" class="form-control cpf" id="cpf" name="cpf" placeholder="000.000.000-00">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cel" class="form-label text-danger">Celular</label>
                                    <input type="text" class="form-control celular" id="cel" name="cel" placeholder="(00) 0000-0000" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="tel" class="form-label">Telefone 2</label>
                                    <input type="text" class="form-control telFixo" id="tel" name="tel" placeholder="(00) 0000-0000" autocomplete="on">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label text-danger">Email</label><br>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="exemplo@email.com" disabled autocomplete="off">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control cep" id="cep" name="cep" placeholder="00000-000">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="uf" class="form-label">Estado/UF</label>
                                    <input type="text" class="form-control" name="uf" id="uf" placeholder="UF">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Cidade...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" name="complemento" id="complemento" placeholder="complemento...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="dizimo" class="form-label text-danger">Dia do dízimo</label>
                                    <input type="number" max="31" class="form-control" id="dizimo" name="dia" placeholder="Dia dizimo" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="fundacao" class="form-label">Fundação</label>
                                    <input type="date" max="31" class="form-control" id="fundacao" name="fundacao" placeholder="Fundação" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Alterar</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        </form>
                    </div>

                    <!--end tab-pane-->
                    <div class="tab-pane" id="transacoes" role="tabpanel">
                        <?= $this->include('admin/pages/includes/transacoes.php') ?>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="preferencias" role="tabpanel">
                        <?= $this->include('dev/nvModulo') ?>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="preferencias" role="tabpanel">
                        <?= $this->include('dev/nvModulo') ?>
                    </div>
                    <!--end tab-pane-->
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
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
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- profile-setting init js -->
<script src="/assets/js/pages/profile-setting.init.js"></script>
<script src="/assets/js/custom/functions.min.js"></script>
<script src="/assets/js/custom/listaTransacoes.min.js"></script>
<script src="/assets/js/custom/igrejaUpdate.js"></script>
<?= $this->endSection() ?>