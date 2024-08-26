/** Geral */
let selectSupervisorIgreja;
let selectSupervisor;

function searchSupervisor() {
    if (selectSupervisorIgreja) {
        selectSupervisorIgreja.destroy();
    }

    if (selectSupervisor) {
        selectSupervisor.destroy();
    }

    var option = "<option selected value=''>Carregando dados...</option>";
    var listOption = $('.selectSupervisor');

    listOption.empty().removeAttr('required');
    listOption.append(option);

    $.getJSON(_baseUrl + "api/v1/public/supervisor", function (data) {
        listOption.empty().removeAttr('required');

        var option = "<option selected value=''>Escolha uma supervisor...</option>";

        $.each(data, function (index, supervisor) {
            option += `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
        });

        listOption.append(option);

        listOption.attr('required', true).attr('data-choices', true);

        selectSupervisorIgreja = initializeChoices('#selectSupervisorIgreja');
        selectSupervisor = initializeChoices('#selectSupervisor');

    }).fail(() => {
        Swal.fire({
            title: 'Ainda não tem supervisores cadastrados',
            icon: 'error'
        }).then((result) => {
            history.back();
        });
    });;
}


function initializeChoices(selector) {
    if (typeof Choices !== 'undefined') {
        //console.log(`Inicializando Choices.js no seletor: ${selector}`);
        return new Choices(selector, {
            allowHTML: true
        });
    } else {
        console.error(`Choices.js não foi carregado corretamente ou não está definido para o seletor: ${selector}`);
        return null;
    }
}

function formSend() {
    $('.formSend').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            // Verifica se todos os campos obrigatórios estão preenchidos
            let valid = true;
            jqForm.find(':input[required]').each(function () {
                if (!this.value) {
                    valid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!valid) {
                exibirMensagem('error', 'Por favor, preencha todos os campos obrigatórios.')
                return false;
            }

            // Exibe mensagem de carregamento com spinner do Font Awesome
            exibirMensagem('info', 'Enviando dados')

            return true; // Permite o envio do formulário
        },
        success: function (responseText, statusText, xhr, $form) {
            Swal.fire({
                title: 'Cadastrado com sucesso!',
                text: 'Confirme sua conta clicando no link que enviamos para o seu e-mail',
                icon: 'success'
            }).then(function (result) {
                window.location.href = '/';
            });;
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON) {
                if (xhr.responseJSON.messages && xhr.responseJSON.messages.error) {
                    exibirMensagem('error', xhr.responseJSON.messages.error);
                } else if (xhr.responseJSON.message) {
                    exibirMensagem('error', xhr.responseJSON.message);
                } else {
                    exibirMensagem('error', 'Erro desconhecido.');
                }
            } else {
                exibirMensagem('error', 'Erro desconhecido.');
            }
        }
    });
}

function exibirMensagem(tipo, message) {
    Swal.fire({
        text: message,
        icon: tipo
    });
}


function checkPasswordPastor() {
    // Alternar visibilidade da senha
    $('.auth-pass-inputgroup').each(function () {
        $(this).find('.password-addon').each(function () {
            $(this).on('click', function () {
                const passwordInput = $(this).closest('.auth-pass-inputgroup').find('#password-input-pastor');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                } else {
                    passwordInput.attr('type', 'password');
                }
            });
        });
    });

    const passwordInput = $('#password-input-pastor');
    const messageBox = $('.password-contain');
    const letter = $('.pass-lower');
    const capital = $('.pass-upper');
    const number = $('.pass-number');
    const special = $('.pass-special'); // Adicionado para caracteres especiais
    const length = $('.pass-length');
    const btnSend = $('#btn-send-pastor');

    passwordInput.on('focus', function () {
        messageBox.show();
    });
    passwordInput.on('blur', function () {
        messageBox.hide();
    });
    passwordInput.on('keyup', function () {
        // Validar letras minúsculas
        const lowerCaseLetters = /[a-z]/g;
        if (passwordInput.val().match(lowerCaseLetters)) {
            letter.removeClass('text-danger').addClass('text-success');
        } else {
            letter.removeClass('text-success').addClass('text-danger');
        }

        // Validar letras maiúsculas
        const upperCaseLetters = /[A-Z]/g;
        if (passwordInput.val().match(upperCaseLetters)) {
            capital.removeClass('text-danger').addClass('text-success');
        } else {
            capital.removeClass('text-success').addClass('text-danger');
        }

        // Validar números
        const numbers = /[0-9]/g;
        if (passwordInput.val().match(numbers)) {
            number.removeClass('text-danger').addClass('text-success');
        } else {
            number.removeClass('text-success').addClass('text-danger');
        }

        // Validar caracteres especiais
        const specialCharacters = /[!@#$%^&*(),.?":{}|<>]/g; // Adicionado para caracteres especiais
        if (passwordInput.val().match(specialCharacters)) {
            special.removeClass('text-danger').addClass('text-success');
        } else {
            special.removeClass('text-success').addClass('text-danger');
        }

        // Validar comprimento
        if (passwordInput.val().length >= 8) {
            length.removeClass('text-danger').addClass('text-success');
        } else {
            length.removeClass('text-success').addClass('text-danger');
        }

        // Exibir o botão enviar se todas as condições forem atendidas
        if (passwordInput.val().match(lowerCaseLetters) &&
            passwordInput.val().match(upperCaseLetters) &&
            passwordInput.val().match(numbers) &&
            passwordInput.val().match(specialCharacters) &&
            passwordInput.val().length >= 8) {
            btnSend.show();
        } else {
            btnSend.hide();
        }
    });
}

// Configura as máscaras de input nos campos
function setupInputMasks() {
    const maskConfigs = [
        { selector: '.cpf', mask: '000.000.000-00' },
        { selector: '.cep', mask: '00000-000' },
        { selector: '.telFixo', mask: '(00) 0000-0000' },
        { selector: '.whatsapp', mask: '+00 (00) 0 0000-0000' },
        { selector: '.cnpj', mask: '00.000.000/0000-00' }
    ];

    maskConfigs.forEach(config => {
        $(config.selector).mask(config.mask);
    });
}

/**Igreja */

$(document).ready(function () {
    setupInputMasks();

    searchSupervisor();
    formSend();

    $('#tipoCadastro').change(function () {
        var selectedValue = $(this).val();

        console.log(selectedValue);


        if (selectedValue == '1') {
            $('#divPastor').show();
            $('#divIgreja').hide();
        } else if (selectedValue == '2') {
            $('#divPastor').hide();
            $('#divIgreja').show();
        } else {
            $('#divPastor').hide();
            $('#divIgreja').hide();
        }
    });
});