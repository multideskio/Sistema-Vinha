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
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#cadastrarGerente">
        <i class="ri-user-settings-line"></i> Cadastrar Gerente
    </button>
</div>

<div class="col-12 mt-2">
    <div class="card">
        <div class="card-body">
            <?= $this->include('admin/pages/includes/search.php') ?>
            <div class="table-responsive">
                <div style="display: none" id="cardResult">
                    <div class="page-link" id="numResults"></div>
                    <table class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>id</th>
                                <th width="100%">Nome completo</th>
                                <th>CPF</th>
                                <th>Email</th>
                                <th>Telefones</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-dados">
                        </tbody>
                    </table>
                    <div id="pager">

                    </div>
                </div>
                <div class="noresult" style="display: none">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                        <p class="text-muted mb-0">Cadastre gerentes...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loadResult">
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mt-2">Carregando registros</h5>
        </div>
    </div>
</div>



<?= $this->include('admin/pages/gerentes/forms.php') ?>
<?= $this->endSection('page') ?>
<?= $this->section('js') ?>

<script>
    $(document).ready(function() {
        atualizarTabela();
    });

    $("#inSearchBtn").click(function(e) {
        var search = $("#inSearch").val();
        atualizarTabela(search);
    });

    $("#inSearch").keypress(function(e) {
        // Verifica se a tecla pressionada é a tecla Enter (código 13)
        if (e.which === 13) {
            var search = $("#inSearch").val();
            atualizarTabela(search);
        }
    });

    $("#pager").on("click", "a", function(e) {
        e.preventDefault();
        var page = $(this).attr("href").split("=")[1];
        var searchParams = new URLSearchParams(window.location.search);
        var search = searchParams.get('search');

        // Verifica se o parâmetro "page" é um número
        if (!isNaN(page)) {
            // Chama a função atualizarTabela com os parâmetros corretos
            atualizarTabela(search, page);
        }
    });

    function atualizarTabela(search = false, page = 1) {

        $('#tabela-dados').empty(); // Limpa o conteúdo da tabela antes de atualizar

        $('#cardResult').hide();
        $('.loadResult').show();

        // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
        var url = _baseUrl + "api/v1/gerentes?";
        if (search) {
            url += "search=" + search + "&";
        }
        if (page) {
            url += "page=" + page;
        }

        // Requisição AJAX para obter os dados das regiões
        $.getJSON(url)
            .done(function(data, textStatus, jqXHR) {
                $("#numResults").html(data.num);
                $("#pager").html(data.pager);


                if (data.rows.length === 0) {
                    $('#cardResult').hide();
                    $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
                } else {
                    $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
                    $('#cardResult').show();
                }

                // Itera sobre os dados recebidos e adiciona as linhas à tabela
                $.each(data.rows, function(index, gerente) {
                    var randomColor = Math.floor(Math.random() * 16777215).toString(16);
                    var newRow = `
                <tr>
                <td>
                    <div class="image-container" style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%;">
                        <img src="${gerente.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${gerente.nome.charAt(0)}';" style="width: 100%; height: 100%; object-fit: cover;" class="rounded-circle">
                    </div>
                </td>
                    <td class="align-middle">#${gerente.id}</td>
                    <td class="align-middle">${gerente.nome} ${gerente.sobrenome}</td>
                    <td class="align-middle">${gerente.cpf}</td>
                    <td class="align-middle">
                        <a href="mailto:${gerente.email}"><b>${gerente.email}</b></a>
                    </td>
                    <td class="align-middle">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel Celular" href="tel:${gerente.celular}"><span class="badge bg-dark rounded-pill">${gerente.celular}</span></a>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel fixo" href="tel:${gerente.telefone}"><span class="badge bg-success rounded-pill">${gerente.telefone}</span></a>
                    </td>
                    <td class="align-middle">
                        <div class="btn-group" role="group">
                            <a href="javascritp:;" onclick="recursoindisponivel()" data-bs-toggle="tooltip" data-bs-placement="top" title="Atualizar cadastro" class="btn btn-dark btn-sm sa-dark">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir cadastro" onclick="excluir('${gerente.id}', 'gerentes')" class="btn btn-danger btn-sm sa-warning">
                                <i class="ri-delete-bin-6-line"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
                    $('#tabela-dados').append(newRow);
                });

                $('.loadResult').hide();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Erro ao carregar os dados:", textStatus, errorThrown);
            });
    }


    function escapeString(str) {
        return str.replace(/'/g, "\\'");
    }



    function update(id, nome, sobrenome, cpf, email, cep, uf, cidade, bairro, endereco, dia, tel, cel) {
        //alert(id);
        $('#updateGerente').modal('show');
        $('#nomeUpdate').val(nome)
        $('#sobrenomeUpdate').val(sobrenome)
        $('#cpfUpdate').val(cpf)
        $('#emailUpdate').val(email)
        $('#cepUpdate').val(cep)
        $('#ufUpdate').val(uf)
        $('#cidadeUpdate').val(cidade)
        $('#bairroUpdate').val(bairro)
        $('#enderecoUpdate').val(endereco)
        $('#diaUpdate').val(dia)
        $('#telUpdate').val(tel)
        $('#celUpdate').val(cel)
        $('#formUpdate').removeAttr("action").attr("action", `${_baseUrl}api/v1/gerentes/${id}`)
    }

    $(document).ready(function() {
        $('#formUpdate').ajaxForm({
            type: 'PUT',
            beforeSubmit: function(formData, jqForm, options) {
                // Executar ações antes de enviar o formulário (se necessário)
            },
            success: function(responseText, statusText, xhr, $form) {
                $('#updateGerente').modal('hide');
                atualizarTabela();
                Swal.fire({
                    title: 'Atualizado!',
                    type: 'success',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });

            },
            error: function(xhr, status, error) {
                // Verifica se a resposta é um JSON
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    // Exibir mensagem de erro vinda do servidor
                    exibirMensagem('error', xhr.responseJSON);
                } else {
                    // Exibir mensagem de erro genérica
                    exibirMensagem({
                        messages: {
                            error: 'Erro desconhecido.'
                        }
                    });
                }
            }
        });
    });
</script>

<!-- REPETE -->
<?= $this->endSection('js') ?>