<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Gerenciamento de pastores</p>
</div>
<div class="row">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <?= form_open_multipart('api/v1/pastores/update/upload/' . $idSearch, 'class="formUpload"') ?>
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
                    <p class="text-muted mb-0">Pastor</p>
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
                <?= form_open('api/v1/pastores/update/links/' . $idSearch, 'class="formTexts"') ?>
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
                        <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                            <i class="far fa-envelope"></i> Transações do usuário
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <?= form_open('api/v1/pastores/' . $idSearch, 'class="formGeral"') ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="selectSupervisor" class="text-danger">Selecione um supervisor</label>
                                    <select name="selectSupervisor" id="selectSupervisor" class="form-select" required></select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="nome" class="form-label text-danger">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome..." required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="sobrenome" class="form-label text-danger">Sobre-nome</label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Sobrenome..." required>
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
                    <div class="tab-pane" id="privacy" role="tabpanel">

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
<script src="/assets/js/custom/functions.min.js"></script>
<script>
    $(document).ready(function() {

        // Formatação de inputs com Cleave.js
        var cleaveCpf = new Cleave('.cpf', {
            numericOnly: true,
            delimiters: ['.', '.', '-'],
            blocks: [3, 3, 3, 2],
            uppercase: true
        });

        var cleaveCep = new Cleave('.cep', {
            numericOnly: true,
            delimiters: ['-'],
            blocks: [5, 3],
            uppercase: true
        });

        var cleaveTelFixo = new Cleave('.telFixo', {
            numericOnly: true,
            delimiters: ['(', ') ', '-'],
            blocks: [0, 2, 4, 4]
        });

        var cleaveCelular = new Cleave('.celular', {
            numericOnly: true,
            delimiters: ['+', ' (', ') ', ' ', '-'],
            blocks: [0, 2, 2, 1, 4, 4]
        });

        searchUpdate(_idSearch)

        $(".enviaLinks").on('change', function() {
            $('.formTexts').submit();
        });

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
            var url = _baseUrl + `api/v1/pastores/${id}`;
            $.getJSON(url)
                .done(function(data, textStatus, jqXHR) {
                    if (data.foto) {
                        $("#fotoPerfil").attr('src', data.foto);
                    }

                    $("#viewNameUser").html(data.nome);
                    $("#facebook").val(data.facebook);
                    $("#website").val(data.website);
                    $("#instagram").val(data.instagram);
                    $("#nome").val(data.nome);
                    $("#sobrenome").val(data.sobrenome);
                    $("#cpf").val(data.cpf);
                    $("#cel").val(data.celular);
                    $("#email").val(data.email);
                    $("#tel").val(data.telefone);
                    $("#cep").val(data.cep);
                    $("#uf").val(data.uf);
                    $("#cidade").val(data.cidade);
                    $("#bairro").val(data.bairro);
                    $("#complemento").val(data.complemento);
                    $("#dizimo").val(data.data_dizimo);

                    //$("#gerente").val(data.gerente);
                    //$("#regiao").val(data.regiao);

                    populateSupervisorSelect(data.idSupervisor)
                    /*listGerentes(data.idGerente)*/

                }).fail(function(jqXHR, textStatus, errorThrown) {

                    $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');

                    console.error("Erro ao carregar os dados:", textStatus, errorThrown);

                    $('.loadResult').hide();

                    Swal.fire({
                        title: 'Os dados não foram enconrados',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false,
                    }).then(function(result) {
                        history.back();
                    });;


                });

            // Tratamento de erro para a imagem
            $('#fotoPerfil').on('error', function() {
                $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
            });
        }
    }


    function populateSupervisorSelect(idAtual) {
        $('#selectSupervisor').empty().removeAttr('required');

        $.getJSON(_baseUrl + "api/v1/supervisores/list", function(data) {
            
            data.forEach(function(supervisor) {
                if (idAtual === supervisor.id) {
                    var option = `<option selected value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
                } else {
                    var option = `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
                }

                $('#selectSupervisor').append(option);
            });

            //console.log('Id atual '+ idAtual)
            //console.log('IDs '+ supervisor.id)
            // Adiciona os atributos e inicializa o plugin Choices após adicionar todas as opções
            $('#selectSupervisor').attr('required', true).attr('data-choices', true);
            
            new Choices('#selectSupervisor');

        }).fail(function() {
            Swal.fire({
                title: 'Cadastre supervisores antes...',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            }).then(function(result) {
                history.back();
            });
        });
    }
</script>


<?= $this->endSection() ?>