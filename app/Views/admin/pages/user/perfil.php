<?= $this->extend('admin/template') ?>

<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Gerenciamento de administradores</p>
</div>
<div class="row">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <?= form_open_multipart('api/v1/administradores/update/upload/' . $idSearch, 'class="formUpload"') ?>
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
                    <p class="text-muted mb-0">Administrador</p>
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
                <?= form_open('api/v1/administradores/update/links/' . $idSearch, 'class="formTexts"') ?>
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
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#preferencias" role="tab">
                            <i class="far fa-envelope"></i> Preferências
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#logs" role="tab">
                            <i class="far fa-envelope"></i> Logs
                        </a>
                    </li> -->
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <?= form_open('api/v1/administradores/' . $idSearch, 'class="formGeral"') ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="sobrenome" class="form-label">Sobre-nome</label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Sobrenome...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control cpf" id="cpf" name="cpf" placeholder="000.000.000-00">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <small class="text-danger">Não é possivel atualizar o e-mail, o usuário deve criar uma nova conta com o novo e-mail</small>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cel" class="form-label">Celular</label>
                                    <input type="text" class="form-control celular" id="cel" name="cel" placeholder="(00) 0000-0000">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="tel" class="form-label">Telefone 2</label>
                                    <input type="text" class="form-control telFixo" id="tel" name="tel" placeholder="(00) 0000-0000">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label><br>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="exemplo@email.com" readonly>
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
                    <div class="tab-pane" id="preferencias" role="tabpanel">
                        <h1>Em desenvolvimento</h1>
                    </div>
                    <!--end tab-pane-->
                    <!--end tab-pane-->
                    <div class="tab-pane" id="logs" role="tabpanel">
                        <h1>Em desenvolvimento</h1>
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
<script src="/assets/js/pages/profile-setting.init.js"></script>
<script src="/assets/js/custom/functions.min.js"></script>
<script src="/assets/js/custom/adminUpdate.min.js"></script>
<?= $this->endSection(); ?>