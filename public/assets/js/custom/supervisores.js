let choicesRegioesInstance; // Instância para Choices.js na lista de regiões
let choicesGerentesInstance; // Instância para Choices.js na lista de gerentes

$(document).ready(function () {
    // Inicialização de máscaras de entrada
    initializeMasks();

    // Atualiza a tabela ao carregar a página
    atualizarTabela();
    listRegioes();
    listGerentes();

    // Configurações de busca
    configureSearch();

    // Configuração de paginação
    configurePagination();

    // Inicialização do formulário AJAX
    initializeAjaxForm();
});

function initializeMasks() {
    $('.cpf').mask('000.000.000-00');
    $('.cep').mask('00000-000');
    $('.telFixo').mask('(00) 0000-0000');
    $('.celular').mask('+00 (00) 0 0000-0000');
}

function configureSearch() {
    const searchHandler = () => {
        const search = $("#inSearch").val();
        atualizarTabela(search);
    };

    $("#inSearchBtn").click(searchHandler);
    $("#inSearch").keypress(function (e) {
        if (e.which === 13) {
            searchHandler();
        }
    });
}

function configurePagination() {
    $("#pager").on("click", "a", function (e) {
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
    $('#formCad').ajaxForm({
        beforeSubmit: () => {
            Swal.fire({
                html: 'Enviando dados!',
                icon: 'info'
            });
        },
        success: (responseText, statusText, xhr, $form) => {
            atualizarTabela();
            $('#formCad')[0].reset();
            listRegioes(); // Repopula as regiões após resetar o formulário
            listGerentes(); // Repopula os gerentes após resetar o formulário
            Swal.fire({
                html: 'Cadastrado!',
                icon: 'success'
            });
        },
        error: (xhr) => {
            const errorMsg = xhr.responseJSON && xhr.responseJSON.messages
                ? xhr.responseJSON
                : { messages: { error: 'Erro desconhecido.' } };
            exibirMensagem('error', errorMsg);
        }
    });
}

function exibirMensagem(type, error) {
    let errorMessage = '';
    for (const key in error.messages) {
        if (error.messages.hasOwnProperty(key)) {
            errorMessage += `${error.messages[key]}\n`;
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

function initializeChoices(selector) {
    if (typeof Choices !== 'undefined') {
        return new Choices(selector, {
            allowHTML: true
        });
    }
    return null;
}

function listRegioes() {
    // Destroi a instância de Choices se já foi inicializada
    if (choicesRegioesInstance) {
        choicesRegioesInstance.destroy();
    }

    $('#selectRegiao').empty().removeAttr('required');

    $.getJSON(`${_baseUrl}api/v1/regioes`, {}, (data) => {

        data.rows.forEach(regiao => {
            $('#selectRegiao').append(`<option value="${regiao.id}">${regiao.id} - ${regiao.nome}</option>`);
        });

        $('#selectRegiao').attr('required', true).attr('data-choices', true);
        
        choicesRegioesInstance = initializeChoices('#selectRegiao'); // Recria a instância de Choices
    }).fail(() => {
        exibirMensagem('error', { messages: { error: 'Cadastre regiões antes de cadastrar um supervisor...' } });
        history.back();
    });
}

function listGerentes() {
    // Destroi a instância de Choices se já foi inicializada
    if (choicesGerentesInstance) {
        choicesGerentesInstance.destroy();
    }

    $('#selectGerentes').empty().removeAttr('required');

    $.getJSON(`${_baseUrl}api/v1/gerentes/list`, {}, (data) => {
        data.forEach(gerente => {
            $('#selectGerentes').append(`<option value="${gerente.id}">${gerente.id} - ${gerente.nome} ${gerente.sobrenome}</option>`);
        });

        $('#selectGerentes').attr('required', true).attr('data-choices', true);
        
        choicesGerentesInstance = initializeChoices('#selectGerentes'); // Recria a instância de Choices
    }).fail(() => {
        exibirMensagem('error', { messages: { error: 'Cadastre gerentes antes de cadastrar um supervisor...' } });
        history.back();
    });
}

function atualizarTabela(search = false, page = 1) {
    $('.noresult').hide();
    $('#perfilCards').empty();
    $('#cardResult').hide();
    $('.loadResult').show();

    let url = `${_baseUrl}api/v1/supervisores?`;
    if (search) url += `search=${search}&`;
    if (page) url += `page=${page}`;

    $.getJSON(url)
        .done((data) => {
            $("#numResults").html(data.num);
            $("#pager").html(data.pager);

            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show();
            } else {
                $('#cardResult').show();
                $('.noresult').hide();

                data.rows.forEach(row => {
                    const randomColor = Math.floor(Math.random() * 16777215).toString(16);
                    $('#perfilCards').append(`
                        <div class="col-xl-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mx-auto avatar-md mb-3">
                                        <img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.nome.charAt(0)}';" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <h5 class="card-title mb-1">#${row.id} - ${row.nome} ${row.sobrenome}</h5>
                                    <hr>
                                    <div class="text-start">
                                        <p class="text-muted mb-2"><strong>Gerente:</strong> ${row.gerente_nome} ${row.gerente_sobrenome}</p>
                                        <p class="text-muted mb-2"><strong>Região:</strong> ${row.regiao_nome}</p>
                                        <p class="text-muted mb-2"><strong>CPF:</strong> ${row.cpf}</p>
                                        <p class="text-muted mb-2"><strong>Email:</strong> ${row.email}</p>
                                        <p class="text-muted mb-2"><strong>Celular:</strong> ${row.celular}</p>
                                        <p class="text-muted mb-0"><strong>Telefone:</strong> ${row.telefone}</p>
                                    </div>
                                </div>
                                <a href="${_baseUrl}admin/supervisor/${row.id}" class="btn text-white bg-primary card-footer">
                                    <i class="ri-pencil-line"></i> EDITAR
                                </a>
                            </div>
                        </div>
                    `);
                });
            }

            $('.loadResult').hide();
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
        });
}
