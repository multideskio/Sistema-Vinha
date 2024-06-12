<?= $this->extend('igrejas/template') ?>
<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Suas transações</p>
    <!-- Button trigger modal -->
</div>
<div class="col-12 mt-2">
    <div class="card">
        <div class="card-body">
            <?= $this->include('admin/pages/includes/search.php') ?>
            <div class="table-responsive">
                <!-- style="display: none" -->
                <div id="cardResult">
                    <div class="alert alert-info" role="alert">
                        <strong>A região é herdada do supervisor</strong>
                    </div>
                    <div class="page-link" id="numResults"></div>
                    <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Pessoa/Igreja</th>
                                <th>Descrição</th>
                                <th title="Data de emissão">Dt.Em</th>
                                <th title="Data de pagamento">Dt.Pg</th>
                                <th>Valor</th>
                                <th>Situação</th>
                                <th>Forma de pagamento</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-dados">
                        </tbody>
                    </table>
                    <div class="text-right">
                        <b>Valor:</b> <span id="valorPageView"></span> <br>
                        <b>Valor total:</b> <span id="valorTotalView"></span>
                    </div>
                    <div id="pager">
                    </div>
                </div>
                <!--  -->
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
<?= $this->endSection() ?>
<?= $this->section("js") ?>
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
        var href = $(this).attr("href");
        var urlParams = new URLSearchParams(href);
        var page = urlParams.get('page');
        var search = urlParams.get('search');

        console.log(page);

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
        var url = _baseUrl + "api/v1/transacoes/user?";

        if (search) {
            url += "search=" + search + "&";
        }

        if (page) {
            url += "page=" + page;
        }

        $.getJSON(url)
            .done(function(data, textStatus, jqXHR) {
                $("#valorPageView").html(data.currentPageTotal);
                $("#valorTotalView").html(data.allPagesTotal);
                $("#numResults").html(data.num);
                if (data.rows.length === 0) {
                    $('#cardResult').hide();
                    $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
                } else {
                    $('#cardResult').show();
                    $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
                }
                $.each(data.rows, function(index, row) {
                    var status;
                    if (row.status == 'Pago') {
                        status = `<span class="badge bg-success">${row.status}</span>`;
                    } else if (row.status == 'Cancelado') {
                        status = `<span class="badge bg-danger">${row.status}</span>`;
                    } else if (row.status == 'Reembolsado'){
                        status = `<span class="badge bg-dark">${row.status}</span>`;
                    } else {
                        status = `<span class="badge bg-warning">${row.status}</span>`;
                    }

                    var newRow = `
                    <tr>
                        <td>${row.id}</td>
                        <td>${row.nome}</td>
                        <td>${row.descricao_lg ? row.descricao_lg : row.desc}</td>
                        <td>${row.data_criado}</td>
                        <td>${row.data_pag ? row.data_pag : ''}</td>
                        <td>${row.valor}</td>
                        <td>${status}</td>
                        <td>${row.forma_pg}</td>
                    </tr>
                `;
                    $('#tabela-dados').append(newRow);
                });

                $("#pager").html(data.pager);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Erro ao carregar os dados:", textStatus, errorThrown);
            });
    }
</script>


<?= $this->endSection() ?>