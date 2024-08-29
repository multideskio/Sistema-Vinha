<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>

<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">Gerar relatório</h1>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <?= form_open('api/v1/transacoes/relatorio', ['id' => 'formSend', 'autocomplete' => 'off']) ?>
                <div class="mb-3">
                    <label for="dateSearch" class="form-label">Defina uma data</label>
                    <div class="input-group">
                        <input id="dateSearch" name="dateSearch" type="text" class="form-control border-0 shadow">
                        <button class="input-group-text bg-primary border-primary text-white" id="btnSearchDash">
                            <i class="ri-calendar-2-line"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="tipoPagamento" class="form-label">Forma de pagamento</label>
                    <select class="form-select" name="tipoPagamento" id="tipoPagamento">
                        <option selected>Todos</option>
                        <option value="pix">Pix</option>
                        <option value="credito">Crédito</option>
                        <option value="debito">Débito</option>
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
                <button class="btn btn-info" type="submit">Gerar relátorio</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-body">
                Lista de relatórios gerados
                <div style="display: none" id="cardResult">
                    <div class="page-link" id="numResults"></div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>DATA</td>
                                <td>PARAMETROS</td>
                                <td>AÇÕES</td>
                            </tr>
                        </thead>
                        <tbody id="relatoriosLista">

                        </tbody>
                    </table>

                    <div id="pager" class="mt-2"></div>
                </div>

                <div class="noresult" style="display: none">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                    </div>
                </div>
                <div class="loadResult">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-2">Buscando registros</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script>
    $(document).ready(function() {
        flatpickr("#dateSearch", {
            locale: "pt",
            mode: "range", // Ativa o modo de seleção de intervalo
            dateFormat: "d/m/Y", // Define o formato da data
            maxDate: "today", // Permite apenas datas até hoje
            minDate: new Date().fp_incr(-365),
            defaultDate: [new Date().fp_incr(-30), "today"]
        });

        // Atualiza a tabela ao carregar a página
        atualizarTabela();

        // Configurações de busca
        configureSearch();

        // Inicialização do formulário AJAX
        initializeAjaxForm();
    });


    function atualizarTabela(search = '', page = 1) {
        $('.noresult').hide();
        $('#relatoriosLista').empty();
        $('#cardResult').hide();
        $('.loadResult').show();

        const url = _baseUrl + "api/v1/transacoes/relatorios/lista?" + $.param({
            search,
            page
        });

        $.getJSON(url)
            .done(function(data) {
                renderizarTabela(data);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Erro ao carregar os dados:", textStatus, errorThrown);
            });
    }

    function renderizarTabela(data) {
        $("#pager").html(data.pager);
        $("#numResults").html(data.num);

        if (data.rows.length === 0) {
            $('#cardResult').hide();
            $('.noresult').show();
        } else {
            $('#cardResult').show();
            $('.noresult').hide();
            $('#relatoriosLista').empty(); // Limpa a lista antes de adicionar novos dados

            data.rows.forEach(function(row) {
                // Parseia os parametros_busca para um objeto
                let parametrosBusca = JSON.parse(row.parametros_busca);

                // Função para formatar datas no formato dd/mm/yyyy hh:mm:ss
                function formatarData(data) {
                    if (!data) return 'N/A'; // Se não houver data, retorna N/A
                    let [dataPart, horaPart] = data.split(' '); // Separa data e hora
                    let [ano, mes, dia] = dataPart.split('-'); // Formata a data para dd/mm/yyyy
                    return `${dia}/${mes}/${ano} ${horaPart || ''}`; // Retorna data e hora formatadas
                }

                // Formata os parâmetros de busca de forma organizada
                let parametrosFormatados = `
                <ul>
                    <li><strong>Data Início:</strong> ${formatarData(parametrosBusca.data_inicio)}</li>
                    <li><strong>Data Fim:</strong> ${formatarData(parametrosBusca.data_fim)}</li>
                    <li><strong>Tipo Pagamento:</strong> ${parametrosBusca.tipo_pagamento || 'N/A'}</li>
                    <li><strong>Status:</strong> ${parametrosBusca.status || 'N/A'}</li>
                </ul>
            `;

                // Adiciona a linha na tabela
                $('#relatoriosLista').append(`<tr>
                <td>${row.id}</td>
                <td>${formatarData(row.created_at)}</td> <!-- Formata a data e hora de criação -->
                <td>${parametrosFormatados}</td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-info btn-sm" href="${row.url_download}" target="_blank">DOWNLOAD</a>
                    </div>
                </td>
            </tr>`);
            });
        }
        $('.loadResult').hide();
    }
    

    function configureSearch() {
        $("#inSearchBtn").click(function() {
            const search = $("#inSearch").val();
            atualizarTabela(search);
        });

        $("#inSearch").keypress(function(e) {
            if (e.which === 13) {
                const search = $("#inSearch").val();
                atualizarTabela(search);
            }
        });

        $("#pager").on("click", "a", function(e) {
            e.preventDefault();
            const href = $(this).attr("href");
            const urlParams = new URLSearchParams(href);
            const page = urlParams.get('page');
            const search = urlParams.get('search');
            if (!isNaN(page)) {
                atualizarTabela(search, page);
            }
        });
    }



    function initializeAjaxForm() {
        $('#formSend').ajaxForm({
            beforeSubmit: function() {
                Swal.fire({
                    title: 'Enviando dados!',
                    icon: 'info'
                });
            },
            success: function() {
                atualizarTabela();
                //$('#formCad')[0].reset();
                Swal.fire({
                    title: 'Relatório solicitado!',
                    icon: 'success',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON && xhr.responseJSON.messages ? xhr.responseJSON : {
                    messages: {
                        error: 'Erro desconhecido.'
                    }
                };
                exibirMensagem('error', errorMessage);
            }
        });
    }

    function exibirMensagem(type, error) {
        const messages = error.messages;
        let errorMessage = '';

        for (const key in messages) {
            if (messages.hasOwnProperty(key)) {
                errorMessage += `${messages[key]}\n`;
            }
        }

        Swal.fire({
            title: type === 'error' ? "Erro ao incluir registro" : "Mensagem",
            text: errorMessage,
            icon: type,
            confirmButtonClass: "btn btn-primary w-xs mt-2",
            buttonsStyling: false,
        });
    }
</script>
<?= $this->endSection() ?>