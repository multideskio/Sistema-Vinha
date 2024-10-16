/** js */
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector("#phone");
    const fullPhoneInput = document.querySelector("#full_phone");

    // Inicializa o intl-tel-input
    const iti = window.intlTelInput(input, {
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

    // Antes de submeter o formulário, captura o número completo com DDI
    document.querySelector('#multi-step-form').addEventListener('submit', function (event) {
        // Captura o número completo com DDI no formato E.164
        const fullPhoneNumber = iti.getNumber(intlTelInputUtils.numberFormat.E164);

        if (iti.isValidNumber()) {
            // Insere o número completo no campo oculto
            fullPhoneInput.value = fullPhoneNumber;
        } else {
            // Se o número for inválido, previne o envio e alerta o usuário
            event.preventDefault();
            exibirMensagem("error", "Por favor, insira um número de telefone válido.")
        }
    });
});


//steps
let currentStep = 0;
const steps = document.querySelectorAll('.step');

const form = document.getElementById('multi-step-form');

function updateStep() {
    steps.forEach((step, index) => {
        step.classList.toggle('active', index === currentStep);
    });
}

function validateStep() {
    // Valida os campos da etapa atual
    const currentFields = steps[currentStep].querySelectorAll('input, select');
    let isValid = true;

    currentFields.forEach(field => {
        if (!field.checkValidity()) {
            field.classList.add('is-invalid'); // Adiciona classe de erro
            isValid = false;
        } else {
            field.classList.remove('is-invalid'); // Remove erro se válido
        }
    });

    return isValid;
}

function nextStep() {
    if (validateStep()) {
        if (currentStep < steps.length - 1) {
            currentStep++;
            updateStep();
        }
    }
}

function prevStep() {
    if (currentStep > 0) {
        currentStep--;
        updateStep();
    }
}

// Remover a classe de erro ao começar a digitar no campo
form.addEventListener('input', (event) => {
    const field = event.target;
    if (field.checkValidity()) {
        field.classList.remove('is-invalid');
    }
});

function exibirMensagem(tipo, message) {
    Swal.fire({
        text: message,
        icon: tipo
    });
}




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


//Verifica se o email já está no banco de dados
function searchEmail() {
    $("#useremailIgreja").on("change", function () {
        var email = $("#useremailIgreja").val();
        if (email) {
            $.getJSON(`${_baseUrl}api/v1/public/search?email=${email}`,
                null,
                function (data, textStatus, jqXHR) {
                    console.log(data);
                    if (data.is === 'not') {
                        $("#useremailIgreja").val("");
                        exibirMensagem("error",
                            "O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha."
                        )
                    }
                }
            );
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