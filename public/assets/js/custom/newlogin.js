 /** Geral */
 function searchSupervisor() {
    
    
    var option = "<option selected value=''>Carregando dados...</option>";
    var listOption = $('.selectSupervisor');
    
    listOption.empty().removeAttr('required');
    listOption.append(option);
    
    $.getJSON(_baseUrl + "api/v1/public/supervisor", function(data) {
        listOption.empty().removeAttr('required');

        var option = "<option selected value=''>Escolha uma supervisor...</option>";

        $.each(data, function(index, supervisor) {
            option += `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
        });
        
        listOption.append(option);
        
        listOption.attr('required', true).attr('data-choices', true);
        
        new Choices('#selectSupervisorIgreja');
        new Choices('#selectSupervisor');
    
    }).fail(() => {
        Swal.fire({
            title: 'Ainda não tem supervisores cadastrados',
            icon: 'error'
        }).then((result) => {
            history.back();
        });
    });;
}

function formSend() {
    $('.formSend').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            // Verifica se todos os campos obrigatórios estão preenchidos
            let valid = true;
            jqForm.find(':input[required]').each(function() {
                if (!this.value) {
                    valid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!valid) {
                Swal.fire({
                    text: 'Por favor, preencha todos os campos obrigatórios.',
                    icon: 'error'
                });
                return false;
            }

            // Exibe mensagem de carregamento com spinner do Font Awesome
            Swal.fire({
                text: 'Cadastrando',
                icon: 'info'
            });

            return true; // Permite o envio do formulário
        },
        success: function(responseText, statusText, xhr, $form) {
            Swal.fire({
                title: 'Cadastrado com sucesso!',
                text: 'Confirme sua conta clicando no link que enviamos para o seu e-mail',
                icon: 'success'
            }).then(function(result) {
                window.location.href = '/';
            });;
        },
        error: function(xhr, status, error) {
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
    $('.auth-pass-inputgroup').each(function() {
        $(this).find('.password-addon').each(function() {
            $(this).on('click', function() {
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

    passwordInput.on('focus', function() {
        messageBox.show();
    });
    passwordInput.on('blur', function() {
        messageBox.hide();
    });
    passwordInput.on('keyup', function() {
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

/**Geral */
/**Pastor */
/** Utilizar p_ */
function formatarPastor() {
    const cep = new Cleave('#p_cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3]
    });

    const whatsapp = new Cleave('#p_whatsapp', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });

    const cpf = new Cleave('#p_cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2]
    });


}

/**Pastor */
/**Igreja */
/** Utilizar i_ */
function formataIgreja() {
    var cep = new Cleave('#i_cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3],
        uppercase: true
    });
    var whatsapp = new Cleave('#i_whatsapp', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });

    var cpf = new Cleave('#i_cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        uppercase: true
    });

    var cnpj = new Cleave('#i_cnpj', {
        numericOnly: true,
        blocks: [2, 3, 3, 4, 2],
        delimiters: ['.', '.', '/', '-'],
        uppercase: true
    });
}

function checkPasswordIgreja() {
    // Alternar visibilidade da senha
    $('#auth-pass-inputgroup-igreja').each(function() {
        $(this).find('.password-addon').each(function() {
            $(this).on('click', function() {
                const passwordInput = $(this).closest('#auth-pass-inputgroup-igreja').find('#password-input-igreja');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                } else {
                    passwordInput.attr('type', 'password');
                }
            });
        });
    });

    const passwordInput = $('#password-input-igreja');
    const messageBox = $('.password-contain');
    const letter = $('.pass-lower');
    const capital = $('.pass-upper');
    const number = $('.pass-number');
    const special = $('.pass-special'); // Adicionado para caracteres especiais
    const length = $('.pass-length');
    const btnSend = $('#btn-send-pastor');

    passwordInput.on('focus', function() {
        messageBox.show();
    });
    passwordInput.on('blur', function() {
        messageBox.hide();
    });
    passwordInput.on('keyup', function() {
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

/**Igreja */

$(document).ready(function() {
    searchSupervisor();
    formSend();

    $('#tipoCadastro').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == '1') {
            formatarPastor();
            checkPasswordPastor();
            $('#divPastor').show();
            $('#divIgreja').hide();
        } else if (selectedValue == '2') {
            checkPasswordIgreja();
            formataIgreja();
            $('#divPastor').hide();
            $('#divIgreja').show();
        } else {
            $('#divPastor').hide();
            $('#divIgreja').hide();
        }
    });
});