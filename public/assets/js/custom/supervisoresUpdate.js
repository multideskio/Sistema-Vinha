// Instâncias de Choices.js
let choicesRegioesInstance;
let choicesGerentesInstance;

$(document).ready(function () {
    initialize();
});

function initialize() {
    // Carrega os dados iniciais
    searchUpdate(_idSearch);
    // Configura as máscaras de input
    formatInput();
    // Configura os event handlers
    setupEventHandlers();
}

// Função para aplicar máscaras aos campos de input
function formatInput() {
    $('.cpf').mask('000.000.000-00');
    $('.cep').mask('00000-000');
    $('.telFixo').mask('(00) 0000-0000');
    $('.celular').mask('+00 (00) 0 0000-0000');
}

// Configura todos os event handlers
function setupEventHandlers() {
    configureFileUploads();
    configureAjaxForms();
}

// Configura os eventos de upload de arquivos
function configureFileUploads() {
    $(".enviaLinks").on('change', () => $('.formTexts').submit());
    $("#profile-img-file-input").on('change', () => $('.formUpload').submit());
}

// Configura os formulários AJAX
function configureAjaxForms() {
    setupAjaxForm('.formTexts', 'PUT', handleFormTextsSuccess);
    setupAjaxForm('.formGeral', 'PUT', handleFormGeralSuccess);
    setupAjaxForm('.formUpload', null, handleFormUploadSuccess);
}

// Configura um formulário AJAX com opções e callbacks
function setupAjaxForm(selector, method, successCallback) {
    $(selector).ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            if (method) options.type = method;
            if (selector != '.formTexts') {
                showLoadingMessage();
            }
        },
        success: successCallback,
        error: handleFormError
    });
}

// Exibe mensagem de carregamento
function showLoadingMessage() {
    Swal.fire({
        text: 'Enviando dados!',
        icon: 'info'
    });
}

// Sucesso ao enviar formTexts
function handleFormTextsSuccess(responseText, statusText, xhr, $form) {
    showTemporaryAlert(".alertAlterado", 1200);
}

// Sucesso ao enviar formGeral
function handleFormGeralSuccess(responseText, statusText, xhr, $form) {
    Swal.fire({
        text: 'Atualizado com sucesso!',
        icon: 'success'
    });

    searchUpdate(_idSearch);
}

// Sucesso ao enviar formUpload
function handleFormUploadSuccess(responseText, statusText, xhr, $form) {
    Swal.fire({
        text: 'Imagem atualizada com sucesso!',
        icon: 'success'
    });
}

// Tratamento de erro ao enviar qualquer formulário
function handleFormError(xhr, status, error) {
    Swal.fire({
        text: 'Erro ao processar a solicitação',
        icon: 'error'
    });
    console.error(xhr, status, error);
}

// Atualiza os dados com base no ID fornecido
function searchUpdate(id) {
    if (!id) {
        showErrorAndGoBack('Os dados não foram encontrados');
        return;
    }

    const url = `${_baseUrl}api/v1/supervisores/${id}`;
    $.getJSON(url)
        .done(handleSearchUpdateSuccess)
        .fail(handleSearchUpdateError);

    // Tratamento de erro para a imagem
    $('#fotoPerfil').on('error', function () {
        $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
    });
}

// Sucesso ao buscar dados de supervisores
function handleSearchUpdateSuccess(data) {
    updateFormFields(data);
    atualizarTabela();
    listRegioes(data.idRegiao);
    listGerentes(data.idGerente);
}

// Erro ao buscar dados de supervisores
function handleSearchUpdateError(jqXHR, textStatus, errorThrown) {
    $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');
    console.error("Erro ao carregar os dados:", textStatus, errorThrown);
    showErrorAndGoBack('Os dados não foram encontrados');
}

// Atualiza os campos do formulário com os dados retornados
function updateFormFields(data) {
    $("#fotoPerfil").attr('src', data.foto || 'https://placehold.co/50/00000/FFF?text=V');
    $("#viewNameUser").text(data.nome);
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
    $("#gerente").val(data.gerente);
    $("#regiao").val(data.regiao);

    globalIdLogin = data.id_login;
}

// Lista as regiões e inicializa Choices.js
function listRegioes(idAtual) {
    destroyChoicesInstance(choicesRegioesInstance);

    $('#selectRegiao').empty().removeAttr('required');
    $.getJSON(`${_baseUrl}api/v1/regioes`)
        .done(data => {
            populateSelectOptions('#selectRegiao', data.rows, idAtual);
            choicesRegioesInstance = initializeChoices('#selectRegiao');
        })
        .fail(() => showErrorAndGoBack('Cadastre regiões antes de cadastrar um supervisor...'));
}

// Lista os gerentes e inicializa Choices.js
function listGerentes(idAtual) {
    destroyChoicesInstance(choicesGerentesInstance);

    $('#selectGerentes').empty().removeAttr('required');
    $.getJSON(`${_baseUrl}api/v1/gerentes/list`)
        .done(data => {
            populateSelectOptions('#selectGerentes', data, idAtual);
            choicesGerentesInstance = initializeChoices('#selectGerentes');
        })
        .fail(() => showErrorAndGoBack('Cadastre gerentes antes de cadastrar um supervisor...'));
}

// Remove a instância de Choices.js, se existir
function destroyChoicesInstance(instance) {
    if (instance) {
        instance.destroy();
    }
}

function initializeChoices(selector) {
    if (typeof Choices !== 'undefined') {
        return new Choices(selector, {
            allowHTML: true
        });
    }
    return null;
}




// Popula as opções do select com base nos dados retornados
function populateSelectOptions(selector, data, selectedId) {
    const select = $(selector);
    data.forEach(item => {
        const isSelected = selectedId === item.id ? 'selected' : '';
        select.append(`<option ${isSelected} value="${item.id}">${item.id} - ${item.nome}</option>`);
    });
    select.attr('required', true).attr('data-choices', true);
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

// Exibe um alerta temporário
function showTemporaryAlert(selector, duration) {
    $(selector).show();
    setTimeout(() => {
        $(selector).fadeOut();
    }, duration);
}
