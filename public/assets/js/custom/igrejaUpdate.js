$(document).ready(function () {
    searchUpdate(_idSearch);
    inputFormat();
    initializeFormHandlers();
});

function inputFormat() {
    const maskConfigs = [
        { selector: '.cpf', mask: '000.000.000-00' },
        { selector: '.cep', mask: '00000-000' },
        { selector: '.telFixo', mask: '(00) 0000-0000' },
        { selector: '.celular', mask: '+00 (00) 0 0000-0000' },
        { selector: '.cnpj', mask: '00.000.000/0000-00' },
    ];

    maskConfigs.forEach(config => {
        $(config.selector).mask(config.mask);
    });
}

function initializeFormHandlers() {
    $(".enviaLinks").on('change', function () {
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
        beforeSubmitMessage: { text: 'Enviando dados!', icon: 'info' },
        successMessage: { text: 'Atualizado com sucesso!', icon: 'success' },
        errorMessage: { title: 'Erro ao atualizar...', icon: 'error' },
    });

    setupAjaxForm('.formUpload', {
        type: 'POST',
        beforeSubmitMessage: { text: 'Enviando imagem!', icon: 'info' },
        successMessage: { html: 'Imagem atualizada com sucesso!', icon: 'success' },
        errorMessage: { title: 'Erro ao atualizar imagem', icon: 'error' },
    });

    $("#profile-img-file-input").on('change', function () {
        $('.formUpload').submit();
    });
}

function setupAjaxForm(formSelector, options) {
    $(formSelector).ajaxForm({
        beforeSubmit: function (formData, jqForm, ajaxOptions) {
            if (options.type) ajaxOptions.type = options.type;
            if (options.beforeSubmitMessage) Swal.fire(options.beforeSubmitMessage);
        },
        success: function (responseText, statusText, xhr, $form) {
            if (options.successMessage) {
                if (typeof options.successMessage === 'string') {
                    alertMessage(options.successMessage, options.alertElement);
                } else {
                    Swal.fire(options.successMessage);
                }
            }
            searchUpdate(_idSearch);
        },
        error: function (xhr, status, error) {
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
        Swal.fire({ text: message, icon: 'success' });
    }
}

function searchUpdate(id) {
    if (!id) return;

    const url = `${_baseUrl}api/v1/igrejas/${id}`;

    $.getJSON(url)
        .done(updateFormFields)
        .fail(handleSearchError);
    
    $('#fotoPerfil').on('error', function () {
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
    atualizarTabela();
    populateSupervisorSelect(data.idSupervisor);
}

function handleSearchError(jqXHR, textStatus, errorThrown) {
    $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');

    console.error("Erro ao carregar os dados:", textStatus, errorThrown);
    $('.loadResult').hide();

    Swal.fire({
        title: 'Os dados não foram encontrados',
        icon: 'error',
        confirmButtonClass: 'btn btn-primary w-xs mt-2',
        buttonsStyling: false,
    }).then(() => history.back());
}

function populateSupervisorSelect(idAtual) {
    $('#selectSupervisor').empty().removeAttr('required');

    $.getJSON(`${_baseUrl}api/v1/supervisores/list`, function (data) {
        data.forEach(supervisor => {
            const option = `<option value="${supervisor.id}" ${supervisor.id === idAtual ? 'selected' : ''}>${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
            $('#selectSupervisor').append(option);
        });

        $('#selectSupervisor').attr('required', true).attr('data-choices', true);
        new Choices('#selectSupervisor');
    }).fail(() => {
        Swal.fire({
            title: 'Cadastre supervisores antes...',
            icon: 'error',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        }).then(() => history.back());
    });
}
