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
    <p class="text-muted float-start">Gerenciamento de supervisores</p>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#cadastrarSupervisor">
        <i class="ri-user-settings-line"></i> Cadastrar supervisor
    </button>
</div>


<div class="col-12 mt-2">
    <div class="card">
        <div class="card-body">
            <?= $this->include('admin/pages/includes/search.php') ?>
            <div class="table-responsive">
                <div style="display: none" id="cardResult">
                    <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>id</th>
                                <th width="90%">Nome completo</th>
                                <th>Gerente</th>
                                <th>Região</th>
                                <th>CPF</th>
                                <th>E-mail</th>
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
                        <p class="text-muted mb-0">Cadastre supervisores...</p>
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
<?= $this->include('admin/pages/supervisores/forms.php') ?>
<?= $this->endSection('page') ?>
<?= $this->section('js') ?>

<script>
    $(document).ready(function() {
        $('#cardResult').show();
        $('.loadResult').hide();

        listRegioes()
        listGerentes()
        atualizarTabela()
    });
</script>
<script>
    function listRegioes() {
        $('#selectRegiao').empty();
        $('#selectRegiao').removeAttr('required');
        var data = {};
        $.getJSON(_baseUrl + "api/v1/regioes", data, function(data, textStatus, jqXHR) {
            // Itera sobre os dados e adiciona as opções ao select
            $.each(data.rows, function(index, regiao) {
                var option = `<option value="${regiao.id}">${regiao.id} - ${regiao.nome}</option>`;
                $('#selectRegiao').append(option);
            });
            $('#selectGerentes').attr('required');
            $('#selectGerentes').attr('data-choices', true);
            // Inicializa o Choices.js no elemento <select>
            new Choices('#selectRegiao');
        });
    }

    function listGerentes() {
        $('#selectGerentes').empty();
        $('#selectGerentes').removeAttr('required');
        var data = {};
        $.getJSON(_baseUrl + "api/v1/gerentes/list", data, function(data, textStatus, jqXHR) {
            // Itera sobre os dados e adiciona as opções ao select
            $.each(data, function(index, gerente) {
                var option = `<option value="${gerente.id}">${gerente.id} - ${gerente.nome} ${gerente.sobrenome}</option>`;
                $('#selectGerentes').append(option);
            });
            // Adiciona os atributos 'required' e 'data-choices' ao elemento <select>
            $('#selectGerentes').attr('required');
            $('#selectGerentes').attr('data-choices', true);
            // Inicializa o Choices.js no elemento <select>
            new Choices('#selectGerentes');
        });
    }
</script>

<script>
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
        var url = _baseUrl + "api/v1/supervisores?";
        if (search) {
            url += "search=" + search + "&";
        }
        if (page) {
            url += "page=" + page;
        }

        // Requisição AJAX para obter os dados das regiões
        $.getJSON(url)
            .done(function(data, textStatus, jqXHR) {
                $("#pager").html(data.pager);
                if (data.rows.length === 0) {
                    $('#cardResult').hide();
                    $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
                } else {
                    $('#cardResult').show();
                    $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
                }

                // Itera sobre os dados recebidos e adiciona as linhas à tabela
                // Itera sobre os dados recebidos e adiciona as linhas à tabela
                $.each(data.rows, function(index, row) {
                    var randomColor = Math.floor(Math.random() * 16777215).toString(16);
                    var newRow = `
        <tr>
            <td>
                <div class="image-container" style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%;">
                    <img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.nome.charAt(0)}';" style="width: 100%; height: 100%; object-fit: cover;" class="rounded-circle">
                </div>
            </td>
            <td class="align-middle">#${row.id}</td>
            <td class="align-middle">${row.nome ? row.nome : ''} ${row.sobrenome ? row.sobrenome : ''}</td>
            <td class="align-middle">${row.gerente_nome ? row.gerente_nome : ''} ${row.gerente_sobrenome ? row.gerente_sobrenome : ''}</td>
            <td class="align-middle">${row.regiao_nome ? row.regiao_nome : ''}</td>
            <td class="align-middle">
                ${row.cpf ? row.cpf : ''}
            </td>
            <td class="align-middle">
                <a href="mailto:${row.email ? row.email : ''}"><b>${row.email ? row.email : ''}</b></a>
            </td>
            <td class="align-middle">
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel Celular" href="tel:${row.celular ? row.celular : ''}"><span class="badge bg-dark rounded-pill">${row.celular ? row.celular : ''}</span></a>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel fixo" href="tel:${row.telefone ? row.telefone : ''}"><span class="badge bg-success rounded-pill">${row.telefone ? row.telefone : ''}</span></a>
            </td>
            <td class="align-middle">
                <div class="btn-group" role="group">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Atualizar cadastro" class="btn btn-dark btn-sm">
                        <i class="ri-pencil-line"></i>
                    </a>
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir cadastro" onclick="excluir('${row.id}', 'gerentes')" class="btn btn-danger btn-sm sa-warning">
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
</script>


<?= $this->endSection('js') ?>