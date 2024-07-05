function searchSupervisor() {
    var option = "<option selected value=''>Escolha um supervisor</option>";
    $('.selectSupervisor').empty().removeAttr('required');
    $.getJSON(_baseUrl + "api/v1/public/supervisor", function(data) {
        $.each(data, function(index, supervisor) {
            option += `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
        });
        $('.selectSupervisor').append(option);
        $('.selectSupervisor').attr('required', true).attr('data-choices', true);
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

function formataInputsPastor() {
    var cleaveCep = new Cleave('.cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3],
        uppercase: true
    });

    var cleaveCpf = new Cleave('.cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        uppercase: true
    });

    var cleaveCelular = new Cleave('.whatsapp', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });
}

function formataInputsIgreja(){
    var cleaveCpf = new Cleave('.cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        uppercase: true
    });
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
            }

            return valid; // Se 'valid' for false, o envio será interrompido
        },
        success: function(responseText, statusText, xhr, $form) {
            Swal.fire({
                text: 'Cadastrado com sucesso!',
                icon: 'success'
            });
        },
        error: function(xhr, status, error) {
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                exibirMensagem('error', xhr.responseJSON);
            } else {
                exibirMensagem('error', {
                    messages: {
                        error: 'Erro desconhecido.'
                    }
                });
            }
        }
    });
}


// Função para exibir mensagens
function exibirMensagem(type, error) {
    // Extrai as mensagens de erro do objeto 'error'
    let messages = error.messages;
    // Inicializa uma string para armazenar as mensagens formatadas
    let errorMessage = '';
    // Itera sobre as mensagens de erro e as formata
    for (let key in messages) {
        if (messages.hasOwnProperty(key)) {
            errorMessage += `${messages[key]}\n`;
        }
    }

    // Exibe a mensagem de erro formatada
    Swal.fire({
        title: type === 'error' ? "Erro ao incluir registro" : "Mensagem",
        text: errorMessage,
        icon: type
    });

}

$(document).ready(function() {
    searchSupervisor();
    formSend();

    $('#tipoCadastro').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == '1') {
            formataInputsPastor();
            $('#divPastor').show();
            $('#divIgreja').hide();
        } else if (selectedValue == '2') {
            formataInputsIgreja();
            $('#divPastor').hide();
            $('#divIgreja').show();
        } else {
            $('#divPastor').hide();
            $('#divIgreja').hide();
        }
    });
});