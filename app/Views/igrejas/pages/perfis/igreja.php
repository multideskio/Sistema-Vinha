<?= $this->extend('igrejas/template') ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
<script>
const _idSearch = <?= $idSearch ?>;
</script>
<?= $this->endSection() ?>
<?= $this->section('page') ?>
<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">O perfil da sua igreja</h1>
</div>
<div class="row">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <?= form_open_multipart('api/v1/igrejas/update/upload/' . $idSearch, 'class="formUpload"') ?>
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <img src="https://placehold.co/50/00000/FFF?text=V" id="fotoPerfil"
                            class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
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
                <div class="alert alert-success alertAlterado bg-success text-white" role="alert"
                    style="display: none;">
                    <b>Alterado com sucesso</b>
                </div>
                <div class="mb-3 d-flex">
                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                        <span class="avatar-title rounded-circle fs-16 bg-primary">
                            <i class="ri-facebook-fill"></i>
                        </span>
                    </div>
                    <input type="url" class="form-control enviaLinks" name="linkFacebook" id="facebook"
                        placeholder="Link facebook" value="">
                </div>
                <div class="mb-3 d-flex">
                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                        <span class="avatar-title rounded-circle fs-16 bg-dark">
                            <i class="ri-global-fill"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control enviaLinks" id="website" name="linkWebsite"
                        placeholder="Link site" value="">
                </div>
                <div class="mb-3 d-flex">
                    <div class="avatar-xs d-block flex-shrink-0 me-3">
                        <span class="avatar-title rounded-circle fs-16 bg-danger">
                            <i class="ri-instagram-fill"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control enviaLinks" id="instagram" name="linkInstagram"
                        placeholder="Link instagram" value="">
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
                    <!--
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#preferencias" role="tab">
                            <i class="far fa-envelope"></i> Preferências
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#preferencias" role="tab">
                            <i class="far fa-envelope"></i> Logs
                        </a>
                    </li> -->
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <?= form_open('api/v1/igrejas/' . $idSearch, 'class="formGeral" autocomplete="off"') ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cnpj" class="form-label text-danger">CNPJ</label>
                                    <input type="text" class="form-control cnpj" id="cnpj" name="cnpj"
                                        placeholder="CNPJ..." required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="razaosocial" class="form-label text-danger">Razão social</label>
                                    <input type="text" class="form-control" id="razaosocial" name="razaosocial"
                                        placeholder="Razão social..." required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="fantasia" class="form-label text-danger">Nome fantasia</label>
                                    <input type="text" class="form-control" id="fantasia" name="fantasia"
                                        placeholder="Nome fantasia..." required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="nome" class="form-label text-danger">Nome tesoureiro</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome..."
                                        required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="sobrenome" class="form-label text-danger">Sobre-nome tesoureiro</label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome"
                                        placeholder="Sobrenome..." required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cpf" class="form-label">CPF tesoureiro</label>
                                    <input type="text" class="form-control cpf" id="cpf" name="cpf"
                                        placeholder="000.000.000-00">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cel" class="form-label text-danger">Celular</label>
                                    <input type="text" class="form-control celular" id="cel" name="cel"
                                        placeholder="(00) 0000-0000" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="tel" class="form-label">Telefone 2</label>
                                    <input type="text" class="form-control telFixo" id="tel" name="tel"
                                        placeholder="(00) 0000-0000" autocomplete="on">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label text-danger">Email</label><br>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="exemplo@email.com" disabled autocomplete="off">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="pais" class="form-label">País</label>
                                    <input type="text" class="form-control" name="pais" id="pais"
                                        placeholder="Ex: Brasil" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control cep" id="cep" name="cep"
                                        placeholder="00000-000">
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
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                        placeholder="Cidade...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                        placeholder="Bairro...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="number" class="form-control" name="numero" id="numero"
                                        placeholder="200" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="rua" class="form-label">Rua</label>
                                    <input type="text" class="form-control" name="rua" id="rua" placeholder="Sua rua"
                                        required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" name="complemento" id="complemento"
                                        placeholder="complemento...">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="dizimo" class="form-label text-danger">Dia do dízimo</label>
                                    <input type="number" max="31" class="form-control" id="dizimo" name="dia"
                                        placeholder="Dia dizimo" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="fundacao" class="form-label">Fundação</label>
                                    <input type="date" max="31" class="form-control" id="fundacao" name="fundacao"
                                        placeholder="Fundação" required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-dark">Alterar</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        </form>
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


<?= $this->endSection() ?>
<?= $this->section("js") ?>
<!-- intl-tel-input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<!-- Utils script (para validação de números) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="/assets/js/pages/profile-setting.init.js"></script>

<script>
$(document).ready(function() {
    searchUpdate(1)
    initializeFormHandlers();
    inputFormat();
});

function inputFormat() {
    const maskConfigs = [{
            selector: '.cpf',
            mask: '000.000.000-00'
        },
        {
            selector: '.cep',
            mask: '00000-000'
        },
        {
            selector: '.telFixo',
            mask: '(00) 0000-0000'
        },
        {
            selector: '.celular',
            mask: '+00 (00) 0 0000-0000'
        },
        {
            selector: '.cnpj',
            mask: '00.000.000/0000-00'
        },
    ];

    maskConfigs.forEach(config => {
        $(config.selector).mask(config.mask);
    });
}

/**Atualiza dados */
function initializeFormHandlers() {
    $(".enviaLinks").on('change', function() {
        $('.formTexts').submit();
    });

    setupAjaxForm('.formTexts', {
        type: 'PUT',
        successMessage: 'Dados atualizados com sucesso!',
        errorMessage: 'Erro ao atualizar dados.',
        alertElement: $(".alertAlterado"),
    });

    setupAjaxForm('.formGeral', {
        type: 'PUT',
        beforeSubmitMessage: {
            text: 'Enviando dados!',
            type: 'info'
        },
        successMessage: {
            text: 'Atualizado com sucesso!',
            type: 'success'
        },
        errorMessage: {
            title: 'Erro ao atualizar...',
            type: 'error'
        },
    });

    setupAjaxForm('.formUpload', {
        type: 'POST',
        beforeSubmitMessage: {
            text: 'Enviando imagem!',
            type: 'info'
        },
        successMessage: {
            html: 'Imagem atualizada com sucesso!',
            type: 'success'
        },
        errorMessage: {
            title: 'Erro ao atualizar imagem',
            type: 'error'
        },
    });

    $("#profile-img-file-input").on('change', function() {
        $('.formUpload').submit();
    });
}

function setupAjaxForm(formSelector, options) {
    $(formSelector).ajaxForm({
        beforeSubmit: function(formData, jqForm, ajaxOptions) {
            if (options.type) ajaxOptions.type = options.type;
            if (options.beforeSubmitMessage) Swal.fire(options.beforeSubmitMessage);
        },
        success: function(responseText, statusText, xhr, $form) {
            if (options.successMessage) {
                if (typeof options.successMessage === 'string') {
                    alertMessage(options.successMessage, options.alertElement);
                } else {
                    Swal.fire(options.successMessage);
                }
            }
            searchUpdate(_idSearch);
        },
        error: function(xhr, status, error) {
            Swal.fire(options.errorMessage);
            console.error(`Erro: ${status}`, error, xhr);
        }
    });
}

function alertMessage(message, alertElement) {
    if (alertElement) {
        alertElement.show();
        setTimeout(() => alertElement.fadeOut(), 1200);
    } else {
        Swal.fire({
            html: message,
            type: 'success'
        });
    }
}

/**Busca dados do usuário logado*/
function searchUpdate(id) {
    if (!id) return;
    const url = `${_baseUrl}api/v1/igrejas/${id}`;
    $.getJSON(url)
        .done(updateFormFields)
        .fail(handleSearchError);

    $('#fotoPerfil').on('error', function() {
        $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
    });
}

function updateFormFields(data) {
    if (data.foto) $("#fotoPerfil").attr('src', data.foto);

    const fields = {
        "#cnpj": data.cnpj,
        "#razaosocial": data.razaoSocial,
        "#fantasia": data.nomeFantazia,
        "#nome": data.nomeTesoureiro,
        "#sobrenome": data.sobrenomeTesoureiro,
        "#cpf": data.cpfTesoureiro,
        "#facebook": data.facebook,
        "#website": data.website,
        "#instagram": data.instagram,
        "#fundacao": data.fundacao,
        "#numero": data.numero,
        "#rua": data.rua,
        "#pais": data.pais,
        "#cel": data.celular,
        "#email": data.email,
        "#tel": data.telefone,
        "#cep": data.cep,
        "#uf": data.uf,
        "#cidade": data.cidade,
        "#bairro": data.bairro,
        "#complemento": data.complemento,
        "#dizimo": data.data_dizimo,
    };

    for (const [selector, value] of Object.entries(fields)) {
        $(selector).val(value);
    }
    $("#viewNameUser").html(data.razaoSocial),
        globalIdLogin = data.id_login;
}

function handleSearchError(jqXHR, textStatus, errorThrown) {
    $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');

    console.error("Erro ao carregar os dados:", textStatus, errorThrown);
    $('.loadResult').hide();

    Swal.fire({
        title: 'Os dados não foram encontrados',
        type: 'error',
        confirmButtonClass: 'btn btn-primary w-xs mt-2',
        buttonsStyling: false,
    }).then(() => history.back());
}
</script>


<?= $this->endSection() ?>