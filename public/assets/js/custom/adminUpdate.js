$(document).ready(function() {
    const userId = _idSearch; // Presumindo que _idSearch é definida globalmente.

    setupInputMasks();
    setupEventListeners(userId);
    loadUserData(userId);
});

function setupEventListeners(userId) {
    setupFormSubmission('.formGeral', 'PUT', () => {
        loadUserData(userId);
        showAlert('Atualizado com sucesso!', 'success');
    });

    setupFormSubmission('.formUpload', null, () => {
        loadUserData(userId);
        showAlert('Imagem atualizada com sucesso!', 'success');
    });

    $("#profile-img-file-input").on('change', function() {
        showAlert('Enviando imagem', 'info');
        $('.formUpload').submit();
    });

    $(".enviaLinks").on('change', function() {
        $('.formTexts').submit();
    });

    setupFormSubmission('.formTexts', 'PUT', () => {
        loadUserData(userId);
        $(".alertAlterado").show();
        setTimeout(() => {
            $(".alertAlterado").fadeOut();
        }, 1200);
    });
}

function setupFormSubmission(formSelector, method, onSuccess) {
    $(formSelector).ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            if (method) options.type = method;
        },
        success: function(responseText, statusText, xhr, $form) {
            if (onSuccess) onSuccess();
        },
        error: function(xhr, status, error) {
            showAlert('Erro ao atualizar...', 'error');
        }
    });
}

function loadUserData(id) {
    if (!id) return;

    const url = `${_baseUrl}api/v1/administradores/${id}`;
    $.getJSON(url)
        .done(function(data) {
            updateUserProfile(data);
        })
        .fail(function() {
            $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');
            showAlert('Os dados não foram encontrados', 'error', () => history.back());
        });

    $('#fotoPerfil').on('error', function() {
        $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
    });
}

function updateUserProfile(data) {
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
}

function showAlert(message, icon, callback) {
    Swal.fire({
        text: message,
        icon: icon
    }).then(callback);
}

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
