let choicesInstance; // Variável global para armazenar a instância do Choices

$(document).ready(function () {
    initializeApp();
});

function initializeApp() {
    setupInputMasks();
    loadTableData();
    setupSupervisorSelect();
    setupEventHandlers();
    setupFormSubmission();
}

// Configura as máscaras de input nos campos
function setupInputMasks() {
    const maskConfigs = [
        { selector: '.cpf', mask: '000.000.000-00' },
        { selector: '.cep', mask: '00000-000' },
        { selector: '.telFixo', mask: '(00) 0000-0000' },
        { selector: '.celular', mask: '+00 (00) 0 0000-0000' },
        { selector: '.cnpj', mask: '00.000.000/0000-00' }
    ];

    maskConfigs.forEach(config => {
        $(config.selector).mask(config.mask);
    });
}

// Configura os event handlers para busca e paginação
function setupEventHandlers() {
    $("#inSearchBtn").on('click', handleSearch);
    $("#inSearch").on('keypress', handleSearchKeyPress);
    $("#pager").on("click", "a", handlePaginationClick);
}

// Configura o envio do formulário de cadastro
function setupFormSubmission() {
    $('#formCad').ajaxForm({
        beforeSubmit: showSubmittingMessage,
        success: handleFormSuccess,
        error: handleFormError
    });
}

// Função para tratar a busca
function handleSearch() {
    const searchQuery = $("#inSearch").val();
    loadTableData(searchQuery);
}

function handleSearchKeyPress(e) {
    if (e.which === 13) handleSearch();
}

// Função para tratar a paginação
function handlePaginationClick(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    const urlParams = new URLSearchParams(href);
    const page = urlParams.get('page');
    const search = urlParams.get('search');
    if (!isNaN(page)) {
        loadTableData(search, page);
    }
}

// Configura a seleção de supervisores
function setupSupervisorSelect() {
    destroyChoicesInstance();

    $('#selectSupervisor').empty().removeAttr('required');
    $.getJSON(_baseUrl + "api/v1/supervisores/list")
        .done(populateSupervisorSelectOptions)
        .fail(handleSupervisorSelectError);
}

// Popula o select de supervisores com as opções
function populateSupervisorSelectOptions(data) {
    data.forEach(supervisor => {
        $('#selectSupervisor').append(`<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`);
    });
    $('#selectSupervisor').attr('required', true).attr('data-choices', true);
    choicesInstance = initializeChoices('#selectSupervisor');
}

// Inicializa o Choices.js para um select
function initializeChoices(selector) {
    if (typeof Choices !== 'undefined') {
        return new Choices(selector, {
            allowHTML: true
        });
    }
    return null;
}

// Destroi a instância de Choices.js, se existir
function destroyChoicesInstance() {
    if (choicesInstance) {
        choicesInstance.destroy();
    }
}

// Exibe uma mensagem de erro ao falhar na obtenção de supervisores
function handleSupervisorSelectError() {
    showErrorAndGoBack('Cadastre supervisores antes de cadastrar um supervisor...');
}

// Carrega os dados da tabela com base na busca e paginação
function loadTableData(search = '', page = 1) {
    const $perfilCards = $('#perfilCards');
    const $cardResult = $('#cardResult');
    const $noResult = $('.noresult');
    const $loadResult = $('.loadResult');

    $noResult.hide();
    $perfilCards.empty();
    $cardResult.hide();
    $loadResult.show();

    const url = new URL(`${_baseUrl}api/v1/igrejas`);
    if (search) url.searchParams.append('search', search);
    url.searchParams.append('page', page);

    $.getJSON(url)
        .done(data => renderTableData(data, $perfilCards, $cardResult, $noResult, $loadResult))
        .fail(handleTableDataError);
}

// Renderiza os dados na tabela
function renderTableData(data, $perfilCards, $cardResult, $noResult, $loadResult) {
    $("#pager").html(data.pager);
    $("#numResults").html(data.num);

    if (data.rows.length === 0) {
        $cardResult.hide();
        $noResult.show();
    } else {
        $cardResult.show();
        $noResult.hide();

        data.rows.forEach(row => {
            const randomColor = Math.floor(Math.random() * 16777215).toString(16);
            $perfilCards.append(`
                <div class="col-xl-3 col-md-4 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="mx-auto avatar-md mb-3">
                                <img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.razao_social.charAt(0)}';" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h5 class="card-title mb-1">#${row.id} - ${row.razao_social}</h5>
                            <hr>
                            <div class="text-start">
                                <p class="text-muted mb-2"><strong>Nome Fantasia:</strong> ${row.fantasia}</p>
                                <p class="text-muted mb-2"><strong>Gerente:</strong> ${row.nome_gerente} ${row.sobre_gerente}</p>
                                <p class="text-muted mb-2 text-primary"><strong>Dia dízimo:</strong> ${row.data_dizimo}</p>
                                <p class="text-muted mb-2"><strong>Supervisor:</strong> ${row.nome_supervisor} ${row.sobre_supervisor}</p>
                                <p class="text-muted mb-2"><strong>Região:</strong> ${row.regiao}</p>
                                <p class="text-muted mb-2"><strong>UF:</strong> ${row.uf}</p>
                                <p class="text-muted mb-2"><strong>Cidade:</strong> ${row.cidade}</p>
                                <p class="text-muted mb-2"><strong>CNPJ:</strong> ${row.cnpj}</p>
                                <p class="text-muted mb-2"><strong>Email:</strong> ${row.email}</p>
                                <p class="text-muted mb-2"><strong>Celular:</strong> ${row.celular}</p>
                                <p class="text-muted mb-0"><strong>Telefone:</strong> ${row.telefone}</p>
                            </div>
                        </div>
                        <a href="${_baseUrl}admin/igreja/${row.id}" class="btn text-white bg-primary card-footer">
                            <i class="ri-pencil-line"></i> EDITAR
                        </a>
                    </div>
                </div>
            `);
        });
    }
    $loadResult.hide();
}

// Trata erro ao carregar os dados da tabela
function handleTableDataError(jqXHR, textStatus, errorThrown) {
    console.error("Erro ao carregar os dados:", textStatus, errorThrown);
}

// Exibe uma mensagem de carregamento antes de enviar o formulário
function showSubmittingMessage() {
    Swal.fire({
        title: 'Enviando dados!',
        icon: 'info'
    });
}

// Trata o sucesso no envio do formulário
function handleFormSuccess() {
    loadTableData();
    $('#formCad')[0].reset();
    Swal.fire({
        title: 'Cadastrado!',
        icon: 'success'
    });
    setupSupervisorSelect()
}

// Trata erro ao enviar o formulário
function handleFormError(xhr) {
    const errorMessage = xhr.responseJSON && xhr.responseJSON.messages 
        ? xhr.responseJSON 
        : { messages: { error: 'Erro desconhecido.' } };
    showErrorAlert('error', errorMessage);
}

// Exibe um alerta de erro
function showErrorAlert(type, error) {
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

// Exibe uma mensagem de erro e retorna à página anterior
function showErrorAndGoBack(message) {
    Swal.fire({
        text: message,
        icon: 'error'
    }).then(() => {
        history.back();
    });
}
