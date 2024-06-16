<?= $this->extend('admin/template') ?>

<?= $this->section('css'); ?>
<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<!-- Filepond css -->
<link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css" type="text/css" />
<link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">

<?= $this->endSection(); ?>

<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Gerenciamento de gerentes</p>
</div>

<div class="row">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <?= form_open_multipart('api/v1/gerentes/update/upload/' . $idSearch, 'class="formUpload"') ?>
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
                    <p class="text-muted mb-0">Gerente/Confirmado</p>
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
                <?= form_open('api/v1/gerentes/update/links/' . $idSearch, 'class="formTexts"') ?>
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
                        <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                            <i class="far fa-user"></i> Alterar senha
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                            <i class="far fa-envelope"></i> Transações do usuário
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <?= form_open('api/v1/gerentes/'. $idSearch, 'class="formGeral"') ?>
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
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cel" class="form-label">Celular</label>
                                    <input type="text" class="form-control" id="cel" name="cel" placeholder="(00) 0000-0000">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="tel" class="form-label">Telefone 2</label>
                                    <input type="text" class="form-control" id="tel" name="tel" placeholder="(00) 0000-0000">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="exemplo@email.com">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000">
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
                                    <label for="dizimo" class="form-label">Dia do dízimo</label>
                                    <input type="number" max="31" class="form-control" id="dizimo" name="dia" placeholder="Dia dizimo">
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
                    <div class="tab-pane" id="changePassword" role="tabpanel">
                        <form action="javascript:void(0);">
                            <div class="row g-2">
                                <div class="col-lg-4">
                                    <div>
                                        <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                        <input type="password" class="form-control" id="oldpasswordInput" placeholder="Enter current password">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4">
                                    <div>
                                        <label for="newpasswordInput" class="form-label">New Password*</label>
                                        <input type="password" class="form-control" id="newpasswordInput" placeholder="Enter new password">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-4">
                                    <div>
                                        <label for="confirmpasswordInput" class="form-label">Confirm Password*</label>
                                        <input type="password" class="form-control" id="confirmpasswordInput" placeholder="Confirm password">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <a href="javascript:void(0);" class="link-primary text-decoration-underline">Forgot Password ?</a>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">Change Password</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="privacy" role="tabpanel">
                        <div class="mb-4 pb-2">
                            <h5 class="card-title text-decoration-underline mb-3">Security:</h5>
                            <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0">
                                <div class="flex-grow-1">
                                    <h6 class="fs-14 mb-1">Two-factor Authentication</h6>
                                    <p class="text-muted">Two-factor authentication is an enhanced security meansur. Once enabled, you'll be required to give two types of identification when you log into Google Authentication and SMS are Supported.</p>
                                </div>
                                <div class="flex-shrink-0 ms-sm-3">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary">Enable Two-facor Authentication</a>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0 mt-2">
                                <div class="flex-grow-1">
                                    <h6 class="fs-14 mb-1">Secondary Verification</h6>
                                    <p class="text-muted">The first factor is a password and the second commonly includes a text with a code sent to your smartphone, or biometrics using your fingerprint, face, or retina.</p>
                                </div>
                                <div class="flex-shrink-0 ms-sm-3">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary">Set up secondary method</a>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0 mt-2">
                                <div class="flex-grow-1">
                                    <h6 class="fs-14 mb-1">Backup Codes</h6>
                                    <p class="text-muted mb-sm-0">A backup code is automatically generated for you when you turn on two-factor authentication through your iOS or Android Twitter app. You can also generate a backup code on twitter.com.</p>
                                </div>
                                <div class="flex-shrink-0 ms-sm-3">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary">Generate backup codes</a>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h5 class="card-title text-decoration-underline mb-3">Application Notifications:</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex">
                                    <div class="flex-grow-1">
                                        <label for="directMessage" class="form-check-label fs-14">Direct messages</label>
                                        <p class="text-muted">Messages from people you follow</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="directMessage" checked />
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mt-2">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fs-14" for="desktopNotification">
                                            Show desktop notifications
                                        </label>
                                        <p class="text-muted">Choose the option you want as your default setting. Block a site: Next to "Not allowed to send notifications," click Add.</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="desktopNotification" checked />
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mt-2">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fs-14" for="emailNotification">
                                            Show email notifications
                                        </label>
                                        <p class="text-muted"> Under Settings, choose Notifications. Under Select an account, choose the account to enable notifications for. </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="emailNotification" />
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mt-2">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fs-14" for="chatNotification">
                                            Show chat notifications
                                        </label>
                                        <p class="text-muted">To prevent duplicate mobile notifications from the Gmail and Chat apps, in settings, turn off Chat notifications.</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="chatNotification" />
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mt-2">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fs-14" for="purchaesNotification">
                                            Show purchase notifications
                                        </label>
                                        <p class="text-muted">Get real-time purchase alerts to protect yourself from fraudulent charges.</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="purchaesNotification" />
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="card-title text-decoration-underline mb-3">Delete This Account:</h5>
                            <p class="text-muted">Go to the Data & Privacy section of your profile Account. Scroll to "Your data & privacy options." Delete your Profile Account. Follow the instructions to delete your account :</p>
                            <div>
                                <input type="password" class="form-control" id="passwordInput" placeholder="Enter your password" value="make@321654987" style="max-width: 265px;">
                            </div>
                            <div class="hstack gap-2 mt-3">
                                <a href="javascript:void(0);" class="btn btn-soft-danger">Close & Delete This Account</a>
                                <a href="javascript:void(0);" class="btn btn-light">Cancel</a>
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
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- profile-setting init js -->
<script src="/assets/js/pages/profile-setting.init.js"></script>
<script src="/assets/libs/filepond/filepond.min.js"></script>
<script src="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
<script src="/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
<script src="/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js"></script>
<script src="/assets/js/custom/functions.min.js"></script>



<script>
    $(document).ready(function() {
        searchUpdate(_idSearch)

        $(".enviaLinks").on('change', function() {
            $('.formTexts').submit();
        });

        /*var options = {
            type: 'PUT'
        }*/

        $('.formTexts').ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {
                options.type = 'PUT'
            },
            success: function(responseText, statusText, xhr, $form) {
                $(".alertAlterado").show(),
                    setTimeout(() => {
                        $(".alertAlterado").fadeOut()
                    }, 1200);
            },
            error: function(xhr, status, error) {
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        });


        $('.formGeral').ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {
                options.type = 'PUT'
            },
            success: function(responseText, statusText, xhr, $form) {
                Swal.fire({
                    title: 'OK!',
                    text: 'Atualizado com sucesso!',
                    icon: 'success'
                })
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Erro ao atualizar...',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });
            }
        });





        $("#profile-img-file-input").on('change', function() {
            $('.formUpload').submit();
        });

        $('.formUpload').ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {
                console.log('Enviando...')
            },
            success: function(responseText, statusText, xhr, $form) {
                Swal.fire({
                    title: 'OK!',
                    text: 'Imagem atualizada com sucesso!',
                    icon: 'success'
                })
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Erro ao atualizar imagem',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });
                console.log(xhr)
                console.log(status)
                console.log(error)
            }
        });








    });



    function searchUpdate(id) {
        if (id) {
            // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
            var url = _baseUrl + `api/v1/gerentes/${id}`;
            $.getJSON(url)
                .done(function(data, textStatus, jqXHR) {
                    if (data.foto) {
                        $("#fotoPerfil").attr('src', data.foto)
                    }
                    /*src="${gerente.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${gerente.nome.charAt(0)}';"*/
                    $("#viewNameUser").html(data.nome)
                    $("#facebook").val(data.facebook)
                    $("#website").val(data.website)
                    $("#instagram").val(data.instagram)

                    $("#nome").val(data.nome)
                    $("#sobrenome").val(data.sobrenome)
                    $("#cpf").val(data.cpf)
                    $("#cel").val(data.celular)
                    $("#email").val(data.email)
                    $("#tel").val(data.telefone)
                    $("#cep").val(data.cep)
                    $("#uf").val(data.uf)
                    $("#cidade").val(data.cidade)
                    $("#bairro").val(data.bairro)
                    $("#complemento").val(data.complemento)
                    $("#dizimo").val(data.data_dizimo)

                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro ao carregar os dados:", textStatus, errorThrown);
                    exibirMensagem('error', {
                        messages: {
                            error: 'Erro ao carregar os dados.'
                        }
                    });
                    $('.loadResult').hide();
                });
        }
    }
</script>


<?= $this->endSection() ?>