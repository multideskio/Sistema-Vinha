/** js */
let currentStepPastor = 0;
let currentStepIgreja = 0;

// Selecionar as etapas dos formulários
const stepsPastor = document.querySelectorAll('.pastor-step');
const stepsIgreja = document.querySelectorAll('.igreja-step');

// Função para atualizar etapas do formulário Pastor
function updateStepPastor() {
    stepsPastor.forEach((step, index) => {
        step.classList.toggle('active', index === currentStepPastor);
    });
}

// Função para validar as etapas do formulário Pastor
function validateStepPastor() {
    const currentFields = stepsPastor[currentStepPastor].querySelectorAll('input, select');
    let isValid = true;

    currentFields.forEach(field => {
        if (!field.checkValidity()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Próxima etapa do formulário Pastor
function nextStepPastor() {
    if (validateStepPastor()) {
        if (currentStepPastor < stepsPastor.length - 1) {
            currentStepPastor++;
            updateStepPastor();
        }
    }
}

// Voltar para etapa anterior do formulário Pastor
function prevStepPastor() {
    if (currentStepPastor > 0) {
        currentStepPastor--;
        updateStepPastor();
    }
}

// Função para atualizar etapas do formulário Igreja
function updateStepIgreja() {
    stepsIgreja.forEach((step, index) => {
        step.classList.toggle('active', index === currentStepIgreja);
    });
}

// Função para validar as etapas do formulário Igreja
function validateStepIgreja() {
    const currentFields = stepsIgreja[currentStepIgreja].querySelectorAll('input, select');
    let isValid = true;

    currentFields.forEach(field => {
        if (!field.checkValidity()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Próxima etapa do formulário Igreja
function nextStepIgreja() {
    if (validateStepIgreja()) {
        if (currentStepIgreja < stepsIgreja.length - 1) {
            currentStepIgreja++;
            updateStepIgreja();
        }
    }
}

// Voltar para etapa anterior do formulário Igreja
function prevStepIgreja() {
    if (currentStepIgreja > 0) {
        currentStepIgreja--;
        updateStepIgreja();
    }
}


$(document).ready(function () {
    const fullPhoneInput1 = $("#full_phone1");
    const fullPhoneInput2 = $("#full_phone");

    // Seleciona todos os inputs com a classe .phone
    $(".phone").each(function () {
        const input = $(this); // Referência ao input atual

        // Inicializa o intl-tel-input para cada campo de telefone
        const iti = window.intlTelInput(input[0], {
            initialCountry: "auto", // Detecta o país automaticamente
            geoIpLookup: function (callback) {
                fetch('https://ipinfo.io/json', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => callback(data.country))
                    .catch(() => callback('BR')); // Default para 'BR' caso falhe
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        // Adiciona o event listener de 'change' para cada campo de telefone
        input.on('change', function (event) {
            // Captura o número completo com DDI no formato E.164
            const fullPhoneNumber = iti.getNumber(intlTelInputUtils.numberFormat.E164);

            if (iti.isValidNumber()) {
                // Insere o número completo nos campos ocultos
                fullPhoneInput1.val(fullPhoneNumber);
                fullPhoneInput2.val(fullPhoneNumber);
            } else {
                // Se o número for inválido, previne o envio e alerta o usuário
                event.preventDefault();
                exibirMensagem("error", "Por favor, insira um número de telefone válido.");
            }
        });
    });
});



// Remover a classe de erro ao começar a digitar no campo
$(".multi-step-form-2").on('input', (event) => {
    const field = event.target;
    if (field.checkValidity()) {
        $(field).removeClass('is-invalid');
    }
});


function exibirMensagem(tipo, message) {
    Swal.fire({
        text: message,
        icon: tipo
    });
}




$(document).ready(function () {

    // Alternar visibilidade da senha
    $('.password-addon').on('click', function () {
        // Seleciona o input de senha relacionado ao botão clicado
        const passwordInput = $(this).closest('.auth-pass-inputgroup').find('.password-input');

        // Alternar entre o tipo "password" e "text"
        const currentType = passwordInput.attr('type');
        passwordInput.attr('type', currentType === 'password' ? 'text' : 'password');
    });

    // Para cada input de senha
    $('.password-input').each(function () {
        const passwordInput = $(this); // O campo de senha atual
        const messageBox = passwordInput.closest('.auth-pass-inputgroup').next('.password-contain'); // Caixa de mensagens relacionada ao input atual
        const letter = messageBox.find('.pass-lower');
        const capital = messageBox.find('.pass-upper');
        const number = messageBox.find('.pass-number');
        const special = messageBox.find('.pass-special'); // Validação de caracteres especiais
        const length = messageBox.find('.pass-length');
        const btnSend = passwordInput.closest('form').find('.btn-send'); // Botão de envio associado ao formulário do campo de senha atual

        // Mostrar a mensagem de validação ao focar no campo
        passwordInput.on('focus', function () {
            messageBox.show();
        });

        // Esconder a mensagem de validação ao perder o foco
        passwordInput.on('blur', function () {
            messageBox.hide();
        });

        // Validar a senha enquanto o usuário digita
        passwordInput.on('keyup', function () {
            const password = passwordInput.val(); // Pega o valor do input de senha atual

            // Validar letras minúsculas
            const lowerCaseLetters = /[a-z]/g;
            if (password.match(lowerCaseLetters)) {
                letter.removeClass('text-danger').addClass('text-success');
            } else {
                letter.removeClass('text-success').addClass('text-danger');
            }

            // Validar letras maiúsculas
            const upperCaseLetters = /[A-Z]/g;
            if (password.match(upperCaseLetters)) {
                capital.removeClass('text-danger').addClass('text-success');
            } else {
                capital.removeClass('text-success').addClass('text-danger');
            }

            // Validar números
            const numbers = /[0-9]/g;
            if (password.match(numbers)) {
                number.removeClass('text-danger').addClass('text-success');
            } else {
                number.removeClass('text-success').addClass('text-danger');
            }

            // Validar caracteres especiais
            const specialCharacters = /[!@#$%^&*(),.?":{}|<>]/g;
            if (password.match(specialCharacters)) {
                special.removeClass('text-danger').addClass('text-success');
            } else {
                special.removeClass('text-success').addClass('text-danger');
            }

            // Validar comprimento
            if (password.length >= 8) {
                length.removeClass('text-danger').addClass('text-success');
            } else {
                length.removeClass('text-success').addClass('text-danger');
            }

            // Exibir o botão enviar se todas as condições forem atendidas
            if (password.match(lowerCaseLetters) &&
                password.match(upperCaseLetters) &&
                password.match(numbers) &&
                password.match(specialCharacters) &&
                password.length >= 8) {
                btnSend.show();
            } else {
                btnSend.hide();
            }
        });
    });
});




/**jquery */

$(document).ready(function () {
    searchEmail();
    initFunctions();
    searchSupervisor();
    formSend();
    setupInputMasks();
});

function initFunctions() {
    $(".btnMostraIgreja").click(function () {
        $(".igrejaCad").show();
        $(".pastorCad").hide();
    });
    $(".btnMostraPastor").click(function () {
        $(".pastorCad").show();
        $(".igrejaCad").hide();
    });
}


function searchEmail() {
    $(".searchEmail").on("change", function () {
        var inputField = $(this); // Salva a referência ao campo de e-mail atual
        var email = inputField.val(); // Pega o valor do input específico que foi modificado

        if (email) {
            $.getJSON(`${_baseUrl}api/v1/public/search?email=${email}`,
                null,
                function (data, textStatus, jqXHR) {
                    console.log(data);
                    if (data.is === 'not') {
                        inputField.val(""); // Usa a referência ao input correto
                        exibirMensagem("error",
                            "O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha."
                        );
                    }
                }
            );
        } else {
            console.log(email + ' Algum problema?');
        }
    });
}




function initializeChoices(selector) {
    if (typeof Choices !== 'undefined') {
        console.log(`Inicializando Choices.js no seletor: ${selector}`);
        return new Choices(selector, {
            allowHTML: true
        });
    } else {
        console.error(`Choices.js não foi carregado corretamente ou não está definido para o seletor: ${selector}`);
        return null;
    }
}

/** SELECTS */
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
        var option = "<option selected value=''>Escolha um supervisor...</option>";
        $.each(data, function (index, supervisor) {
            option += `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
        });
        listOption.append(option);
        listOption.attr('required', true).attr('data-choices', true);
        selectSupervisorIgreja = initializeChoices('#selectSupervisorIgreja');
        //selectSupervisor = initializeChoices('#selectSupervisor');
    }).fail(() => {
        Swal.fire({
            title: 'Ainda não tem supervisores cadastrados',
            icon: 'error'
        }).then((result) => {
            history.back();
        });
    });
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

function setupInputMasks() {
    const maskConfigs = [
        { selector: '.cpf', mask: '000.000.000-00' },
        { selector: '.cep', mask: '00000-000' },
        { selector: '.telFixo', mask: '(00) 0000-0000' },
        { selector: '.phone', mask: '(00) 0 0000-0000' },
        { selector: '.cnpj', mask: '00.000.000/0000-00' }
    ];

    maskConfigs.forEach(config => {
        $(config.selector).mask(config.mask);
    });
}