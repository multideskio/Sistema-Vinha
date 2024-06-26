<?= $this->extend('admin/template') ?>
<?= $this->section('css'); ?>
<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>
<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Gerenciamento do perfil da empresa</p>
</div>

<div class="row">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <?= form_open_multipart('api/v1/administracao/update/upload/' . $idSearch, 'class="formUpload"') ?>
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
                    <p class="text-muted mb-0">Administração</p>
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
                <?= form_open('api/v1/administracao/update/links/' . $idSearch, 'class="formTexts"') ?>
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
                        <a class="nav-link" data-bs-toggle="tab" href="#smtp" role="tab">
                            <i class="far fa-envelope"></i> Configuração de SMTP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#wa" role="tab">
                            <i class="far fa-envelope"></i> Configuração de WhatApp
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#mensagens" role="tab">
                            <i class="far fa-envelope"></i> Mensagens WhatsApp
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <?= form_open('api/v1/administracao/update/info/' . $idSearch, ['class' => 'formGeral']) ?>
                        <div class="mb-3 row">
                            <div class="col-md-5">
                                <label for="cnpj">CNPJ</label>
                                <input type="text" class="form-control" name="cnpj" id="cnpj" placeholder="CNPJ" required>
                            </div>

                            <div class="col-md-7">
                                <label for="razaosocial">Nome do estabelecimento</label>
                                <input type="text" class="form-control" name="empresa" id="razaosocial" placeholder="VINHA" required>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="email">E-mail de suporte</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="email@exemplo.com" required>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="fixo">Telefone fixo</label>
                                <input type="text" class="form-control" name="fixo" id="fixo" placeholder="(00) 0000-0000">
                            </div>
                            <div class="col-md-6">
                                <label for="celular">Celular/WhatsApp</label>
                                <input type="text" class="form-control" name="celular" id="celular" placeholder="+55 (00) 0 0000-0000" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <label for="cep">CEP</label>
                                <input type="text" class="form-control" name="cep" id="cep" placeholder="00000-000" required>
                            </div>
                            <div class="col-md-4">
                                <label for="uf">UF</label>
                                <input type="text" class="form-control" name="uf" id="uf" placeholder="UF" maxlength="2" required>
                            </div>
                            <div class="col-md-4">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Cidade" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-5">
                                <label for="bairro">Bairro</label>
                                <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro..." required>
                            </div>
                            <div class="col-md-7">
                                <label for="complemento">Complemento</label>
                                <input type="text" class="form-control" name="complemento" id="complemento" placeholder="Quadra, Lote, Número" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-info" type="submit">Atulizar perfil</button>
                        </div>
                        </form>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="smtp" role="tabpanel">

                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>Atenção!</strong> Essas informações são necessárias para melhor desempendo do sistema.
                        </div>
                        <?= form_open('api/v1/administracao/update/smtp/' . $idSearch, ['class' => 'formGeral']) ?>
                        <div class="mb-3">
                            <label for="nomeRemetente">Nome remetente</label>
                            <input type="text" name="nomeRemetente" id="nomeRemetente" placeholder="Nome de rementente para seus e-mails" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailRemetente">Email remetente</label>
                            <input type="email" name="emailRemetente" id="emailRemetente" placeholder="E-mail de remetente para seus e-mails" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="smtpHOST">SMTP Host</label>
                            <input type="text" name="smtpHOST" id="smtpHOST" placeholder="smtp.gmail.com" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="smtpLOGIN">SMTP Login</label>
                            <input type="text" name="smtpLOGIN" id="smtpLOGIN" placeholder="seu-email@email.com" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="smtpPASS">SMTP Password</label>
                            <input type="password" name="smtpPASS" id="smtpPASS" placeholder="Sua senha" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="smtpPORT">SMTP Porta</label>
                            <input type="text" name="smtpPORT" id="smtpPORT" placeholder="587" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="smtpCRYPT">Protocolo</label>
                            <select name="smtpCRYPT" id="smtpCRYPT" class="form-select" required>
                                <option value="" selected>Escolha uma opção</option>
                                <option value="ssl">SSL</option>
                                <option value="tls">TLS</option>
                            </select>
                        </div>
                        <div class="mb-3 fw-bold">
                            Protocolo atual: <span class="text-uppercase text-primary" id="protocoloSMTP"></span>
                        </div>
                        <div class="col-lg-12 fs-6 mb-3">
                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                <input class="form-check-input" type="checkbox" role="switch" id="ativarSMTP" name="ativarSMTP" value="1">
                                <label class="form-check-label" for="ativarSMTP">Ativar botão de suporte do WhatsApp</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-info" type="submit">Atualizar SMTP</button>
                            <button class="btn btn-dark" type="button" id="testarEmail">Testar envio de e-mail</button>
                        </div>
                        </form>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="wa" role="tabpanel">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <b>Suporte da API <a href="mailto:multidesk.io@gmail.com">multidesk.io@gmail.com</a></b>
                        </div>
                        <div class="mb-3">
                            <label for="urlAPI">URL da API</label>
                            <input type="text" class="form-control" name="urlAPI" id="urlAPI" placeholder="https://api.conect.app" required>
                        </div>
                        <div class="mb-3">
                            <label for="instanceAPI">Instância</label>
                            <input type="text" class="form-control" name="instanceAPI" id="instanceAPI" placeholder="Nome da instância" required>
                        </div>
                        <div class="mb-3">
                            <label for="keyAPI">API KEY</label>
                            <input type="text" class="form-control" name="keyAPI" id="keyAPI" placeholder="API KEY" required>
                        </div>
                        <div class="col-lg-12 fs-6 mb-3">
                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                <input class="form-check-input" type="checkbox" role="switch" id="ativawa" name="ativawa" value="1">
                                <label class="form-check-label" for="ativawa">Ativar botão de suporte do WhatsApp</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-info" type="submit">Atualizar API WhatsApp</button>
                        </div>
                    </div>
                    <!--end tab-pane-->


                    <div class="tab-pane" id="mensagens" role="tabpanel">
                        <div class="text-center">
                            <h4>Configuração de mensagens que são enviadas por WhatsApp</h4>
                            <p class="text-info mt-3 fs-8">
                                Você pode criar mensagens como se fosse no WhastApp, por exemplo:<br>
                                Para marcar como negrito utilize *Palavra*, para marcar como italico utilize _Palavra_.<br>
                                <i>Fique avontade para criar suas mensagem personalizadas.</i>
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card border card-border-primary">
                                    <div class="card-header text-center">
                                        <p class="card-title mb-0">Mensagem para novo cadastro</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-danger">
                                            Utilize essas marcações para personalizar sua mensagem:
                                            <ul>
                                                <li>{NOME} = Nome do cliente</li>
                                                <li>|</li>
                                                <li>|</li>
                                            </ul>
                                        </div>
                                        <?= form_open() ?>
                                        <input type="hidden" value="novo_usuario" readonly name="tipo" class="form-control">
                                        <label for="mensageNovo">Mensagem</label>
                                        <textarea name="mensagemNovo" id="mensageNovo" class="form-control mb-3" rows="10"></textarea>
                                        <div class="col-lg-12 fs-6 mb-3">
                                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                                <input class="form-check-input" type="checkbox" role="switch" id="ativawa" name="ativawa" value="1">
                                                <label class="form-check-label" for="ativawa">Ativar o envio</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit">Atualizar mensagem</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border card-border-primary">
                                    <div class="card-header text-center">
                                        <p class="card-title mb-0">Confirmação de conta</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-danger">
                                            Utilize essas marcações para personalizar sua mensagem:
                                            <ul>
                                                <li>{NOME} = Nome do cliente</li>
                                                <li>{COD} = Código que o cliente vai informar para recuperar a conta.</li>
                                            </ul>
                                        </div>
                                        <?= form_open() ?>
                                        <input type="hidden" value="confirmacao_conta" readonly name="tipo" class="form-control">
                                        <label for="mensageNovo">Mensagem</label>
                                        <textarea name="mensagemNovo" id="mensageNovo" class="form-control mb-3" rows="10"></textarea>
                                        <div class="col-lg-12 fs-6 mb-3">
                                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                                <input class="form-check-input" type="checkbox" role="switch" id="ativawa" name="ativawa" value="1">
                                                <label class="form-check-label" for="ativawa">Ativar o envio</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit">Atualizar mensagem</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border card-border-primary">
                                    <div class="card-header text-center">
                                        <p class="card-title mb-0">Cobrança comum gerada</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-danger">
                                            Utilize essas marcações para personalizar sua mensagem:
                                            <ul>
                                                <li>{NOME} = Nome do cliente</li>
                                                <li>{GATEWAY} = Forma de pagamento</li>
                                                <li>{VALOR} = Valor do pagamento</li>
                                            </ul>
                                        </div>
                                        <?= form_open() ?>
                                        <input type="hidden" value="cobranca_gerada" readonly name="tipo" class="form-control">
                                        <label for="mensageNovo">Mensagem</label>
                                        <textarea name="mensagemNovo" id="mensageNovo" class="form-control mb-3" rows="10"></textarea>
                                        <div class="col-lg-12 fs-6 mb-3">
                                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                                <input class="form-check-input" type="checkbox" role="switch" id="ativawa" name="ativawa" value="1">
                                                <label class="form-check-label" for="ativawa">Ativar o envio</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit">Atualizar mensagem</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border card-border-primary">
                                    <div class="card-header text-center">
                                        <p class="card-title mb-0">Cobrança por PIX gerada</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-danger">
                                            Utilize essas marcações para personalizar sua mensagem:
                                            <ul>
                                                <li>{NOME} = Nome do cliente</li>
                                                <li>{VALOR} = Valor do pagamento</li>
                                                <li>{COPIA} = O código copia e cola</li>
                                            </ul>
                                        </div>
                                        <?= form_open() ?>
                                        <input type="hidden" value="cobranca_gerada_pix" readonly name="tipo" class="form-control">
                                        <label for="mensageNovo">Mensagem</label>
                                        <textarea name="mensagemNovo" id="mensageNovo" class="form-control mb-3" rows="10"></textarea>
                                        <div class="col-lg-12 fs-6 mb-3">
                                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                                <input class="form-check-input" type="checkbox" role="switch" id="ativawa" name="ativawa" value="1">
                                                <label class="form-check-label" for="ativawa">Ativar o envio</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit">Atualizar mensagem</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border card-border-primary">
                                    <div class="card-header text-center">
                                        <p class="card-title mb-0">Pagamento realizado</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-danger">
                                            Utilize essas marcações para personalizar sua mensagem:
                                            <ul>
                                                <li>{NOME} = Nome do cliente</li>
                                                <li>{VALOR} = Valor do pagamento</li>
                                            </ul>
                                        </div>
                                        <?= form_open() ?>
                                        <input type="hidden" value="pagamento_realizado" readonly name="tipo" class="form-control">
                                        <label for="mensageNovo">Mensagem</label>
                                        <textarea name="mensagemNovo" id="mensageNovo" class="form-control mb-3" rows="10"></textarea>
                                        <div class="col-lg-12 fs-6 mb-3">
                                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                                <input class="form-check-input" type="checkbox" role="switch" id="ativawa" name="ativawa" value="1">
                                                <label class="form-check-label" for="ativawa">Ativar o envio</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit">Atualizar mensagem</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border card-border-primary">
                                    <div class="card-header text-center">
                                        <p class="card-title mb-0">Pagamento em atraso</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-danger">
                                            Utilize essas marcações para personalizar sua mensagem:
                                            <ul>
                                                <li>{NOME} = Nome do cliente</li>
                                                <li>{DIA_DIZIMO} = Dia do dízimo</li>
                                            </ul>
                                        </div>
                                        <?= form_open() ?>
                                        <input type="hidden" value="pagamento_atrasado" readonly name="tipo" class="form-control">
                                        <label for="mensageNovo">Mensagem</label>
                                        <textarea name="mensagemNovo" id="mensageNovo" class="form-control mb-3" rows="10"></textarea>
                                        <div class="col-lg-12 fs-6 mb-3">
                                            <div class="form-check form-switch form-switch-success form-switch-lg">
                                                <input class="form-check-input" type="checkbox" role="switch" id="ativawa" name="ativawa" value="1">
                                                <label class="form-check-label" for="ativawa">Ativar o envio</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit">Atualizar mensagem</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--end tab-pane-->
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?= $this->endSection(); ?>

<?= $this->section('js') ?>
<!-- profile-setting init js -->
<script src="/assets/js/pages/profile-setting.init.js"></script>
<script src="/assets/js/custom/functions.min.js"></script>
<script>
    const idEmp = "<?= session('data')['idAdm'] ?>";
</script>
<script src="/assets/js/custom/config.js"></script>
<?= $this->endSection(); ?>