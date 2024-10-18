    <?= $this->extend('igrejas/template') ?>
    <?= $this->section('page') ?>
    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h1 class="fw-bolder">TRANSAÇÕES</h1>
                </div>
            </div><!-- end card header -->
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <?= form_open('#', ['id' => 'formSend', 'autocomplete' => 'off']) ?>
                    <div class="mb-3">
                        <label for="dateSearch" class="form-label">Defina uma data</label>
                        <div class="input-group">
                            <input id="dateSearch" name="dateSearch" type="text" class="form-control border-0 shadow">
                            <button class="input-group-text bg-primary border-primary text-white" type="button">
                                <i class="ri-calendar-2-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tipoPagamento" class="form-label">Forma de pagamento</label>
                        <select class="form-select" name="tipoPagamento" id="tipoPagamento">
                            <option selected>Todos</option>
                            <option value="pix">Pix</option>
                            <option value="Crédito">Crédito</option>
                            <!--<option value="Debeto">Débito</option>-->
                            <option value="boleto">Boleto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="statusPagamento" class="form-label">Status do pagamento</label>
                        <select class="form-select" name="statusPagamento" id="statusPagamento">
                            <option selected>Todos</option>
                            <option value="Pago">Pago</option>
                            <option value="Aguardando">Aguardando</option>
                            <option value="Reembolsado">Reembolsado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="order" class="form-label">Ordenar por data:</label>
                        <select class="form-select" name="order" id="order">
                            <option value="ASC" selected>Crescente</option>
                            <option value="DESC">Decrescente</option>
                        </select>
                    </div>
                    <button class="btn btn-info" id="inSearchBtn" type="button">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="">
                            <div class="page-link" id="numResults"></div>
                            <table class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <!--<th>Pessoa/Igreja</th>-->
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
                                <span id="valorPageView"></span>
                                <br>
                                <span id="valorTotalView"></span>
                            </div>
                            <div id="pager">
                            </div>
                        </div>
                        <!--  -->
                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                                <p class="text-muted mb-0">Cadastre supervisores...</p>
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
                </div>
            </div>
        </div>

    </div>
    <?= $this->endSection() ?>
    <?= $this->section("js") ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    <script>
$(document).ready(function() {
    // Chama a função de atualização da tabela ao carregar a página
    atualizarTabela();

    // Configura o flatpickr para o campo de data
    flatpickr("#dateSearch", {
        locale: "pt", // Localização para português
        mode: "range", // Ativa o modo de intervalo de datas
        dateFormat: "d/m/Y", // Formato de exibição das datas
        maxDate: "today", // Limita a seleção até o dia atual
        minDate: new Date().fp_incr(-365), // Limita o intervalo a 1 ano atrás
        defaultDate: [new Date().fp_incr(-30), "today"] // Define intervalo padrão
    });

    // Configura o comportamento da pesquisa e paginação
    configureSearch();
});

function configureSearch() {
    // Evento de clique no botão de pesquisa
    $("#inSearchBtn").click(function() {
        const search = $("#inSearch").val();
        const date = $('#dateSearch').val();
        const tipo = $('#tipoPagamento').val();
        const status = $('#statusPagamento').val();
        const order = $('#order').val();
        atualizarTabela(search, 1, date, tipo, status, order);
    });

    // Evento de pressionar Enter na barra de pesquisa
    $("#inSearch").keypress(function(e) {
        if (e.which === 13) {
            const search = $("#inSearch").val();
            const date = $('#dateSearch').val();
            const tipo = $('#tipoPagamento').val();
            const status = $('#statusPagamento').val();
            const order = $('#order').val();

            atualizarTabela(search, 1, date, tipo, status, order);
        }
    });

    // Evento para a paginação ao clicar em um link de página
    $("#pager").on("click", "a", function(e) {
        e.preventDefault();
        const href = $(this).attr("href");
        const urlParams = new URLSearchParams(href);
        const page = urlParams.get('page');
        const search = urlParams.get('search');
        const date = $('#dateSearch').val();
        const tipo = $('#tipoPagamento').val();
        const status = $('#statusPagamento').val();
        const order = $('#order').val();


        if (!isNaN(page)) {
            atualizarTabela(search, page, date, tipo, status, order);
        }
    });
}

// Função para atualizar a tabela de dados com filtros e paginação
function atualizarTabela(search = false, page = 1, date = '', tipo = '', status = '', order = '') {
    $('.noresult').hide();
    $('#tabela-dados').empty(); // Limpa o conteúdo da tabela
    $('#cardResult').hide();
    $('.loadResult').show(); // Exibe o indicador de carregamento

    // Monta a URL com os parâmetros de busca, página, data, tipo e status
    var url = _baseUrl + "igreja/api/v1/transacoes?";

    if (search) {
        url += "search=" + encodeURIComponent(search) + "&";
    }

    if (page) {
        url += "page=" + page + "&";
    }

    if (date) {
        url += "date=" + encodeURIComponent(date) + "&";
    }

    if (tipo) {
        url += "tipo=" + encodeURIComponent(tipo) + "&";
    }

    if (status) {
        url += "status=" + encodeURIComponent(status) + "&";
    }

    if (order) {
        url += "order=" + encodeURIComponent(order);
    }

    // Faz a requisição AJAX
    $.getJSON(url)
        .done(function(data) {
            // Atualiza os contadores de página e total de registros
            $("#valorPageView").html("<b>Valor nessa página:</b> " + data.currentPageTotal);
            $("#valorTotalView").html("<b>Total total: </b>" + data.allPagesTotal);
            $("#numResults").html(data.num);

            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show(); // Exibe a mensagem de 'sem resultados'
            } else {
                $('#cardResult').show();
                $('.noresult').hide(); // Oculta a mensagem de 'sem resultados'
            }

            // Itera sobre os resultados e insere as linhas na tabela
            $.each(data.rows, function(index, row) {
                var status;
                switch (row.status) {
                    case 'Pago':
                        status = `<span class="badge bg-success">${row.status}</span>`;
                        break;
                    case 'Cancelado':
                        status = `<span class="badge bg-danger">${row.status}</span>`;
                        break;
                    case 'Reembolsado':
                        status = `<span class="badge bg-dark">${row.status}</span>`;
                        break;
                    default:
                        status = `<span class="badge bg-warning">${row.status}</span>`;
                        break;
                }

                var newRow = `
                        <tr>
                            <td>${row.id}</td>
                            <!-- <td>${row.nome}</td> -->
                            <td>${row.descricao_lg ? row.descricao_lg : row.desc}</td>
                            <td>${row.data_criado}</td>
                            <td>${row.data_pag ? row.data_pag : ''}</td>
                            <td>R$${row.valor}</td>
                            <td>${status}</td>
                            <td class="fw-bold">${row.forma_pg}</td>
                        </tr>
                    `;
                $('#tabela-dados').append(newRow);
            });

            // Atualiza a paginação
            $("#pager").html(data.pager);
            $('.loadResult').hide();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
            $('.loadResult').hide();
        });
}
    </script>
    <?= $this->endSection() ?>