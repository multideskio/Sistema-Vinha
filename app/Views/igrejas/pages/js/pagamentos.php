<script>
$(document).ready(function() {
    forms();
});



function mostrarDadosBoleto(data) {

    // Remove a classe d-none para exibir o conteúdo
    document.getElementById('boleto-info').classList.remove('d-none');


    // Preenche os dados no HTML
    $('#cliente-nome').text(data.response.Customer.Name);
    $('#cliente-cpf').text(data.response.Customer.Identity);

    var endereco =
        `${data.response.Customer.Address.Street}, ${data.response.Customer.Address.Number}, ${data.response.Customer.Address.City} - ${data.response.Customer.Address.State}, ${data.response.Customer.Address.ZipCode}`;
    $('#endereco').text(endereco);

    // Formatação da data no formato dd/mm/yyyy
    let dataVencimento = new Date(data.response.Payment.ExpirationDate);
    let dataFormatada = ('0' + dataVencimento.getDate()).slice(-2) + '/' +
        ('0' + (dataVencimento.getMonth() + 1)).slice(-2) + '/' +
        dataVencimento.getFullYear();
    $('#vencimento').text(dataFormatada);

    // Formatação do valor com separador de milhares e casas decimais (R$ 2.000,00)
    let valorFormatado = (data.response.Payment.Amount / 100).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
    $('#valorText').text(valorFormatado); // Atualiza o valor formatado

    // Preenche outros dados do boleto
    $('#numero-boleto').text(data.response.Payment.BoletoNumber);
    $('#codigo-barras').text(data.response.Payment.BarCodeNumber);
    $('#linha-digitavel').text(data.response.Payment.DigitableLine);

    // Define os links para visualizar e baixar o boleto
    $('#ver-boleto').attr('href', data.boletoUrl);
    $('#baixar-boleto').attr('href', data.boletoUrl);
}


function mostrarDadosPix(data) {
    atualizarStatusPix(data.Payment.PaymentId);
    $("#qrCodeGer").removeAttr('src');
    $("#qrCodeGer").attr("src", "data:image/png;base64," + data.Payment.QrCodeBase64Image)
    $("#copiaColaPix").val(data.Payment.QrCodeString);
    if (data.Payment.QrCodeString) {
        $("#copiaColaPix").show();
        $("#btnCopiaColaPix").show();
    }
    $("#modalPix").modal("show");
    $('#btnCopiaColaPix').click(function() {
        // Seleciona o texto do input
        $('#copiaColaPix').select();

        // Copia o texto selecionado para a área de transferência
        document.execCommand('copy');

        // Alerta para indicar que o texto foi copiado
        Swal.fire({
            title: 'Código copiado com sucesso!',
            type: 'info',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        });
    });
}

let requestCount = 0;
const maxRequests = 50;

function atualizarStatusPix(code) {
    if (requestCount >= maxRequests) {
        Swal.fire({
            title: 'Limite de requisições atingido',
            html: 'Limite de verificação atingido.<br>Se você ainda realizar o pagamento utilizando esse QrCode, vamos te enviar o comprovante pelo e-mail.',
            type: 'error',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        });
        return;
    }
    requestCount++;
    $.getJSON(_baseUrl + "/api/v1/cielo/payment-status/" + code,
        function(data, textStatus, jqXHR) {
            //console.log(data)
            if (data.status !== 2) {
                setTimeout(() => {
                    atualizarStatusPix(code);
                }, 10000);
            } else {
                if (data.status === 2) {
                    $("#areaStatusPixNo").hide();
                    Swal.fire({
                        title: 'Pagamento recebido com sucesso!',
                        type: 'success',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false,
                    }).then(function(result) {
                        location.reload();
                    });
                    //$('#formCad')[0].reset()
                }
            }
        }
    );
}

function validateExpiryDate(expiryDate) {
    // Expressão regular para validar MM / YYYY
    const regex = /^(0[1-9]|1[0-2]) \/\ 20\d{2}$/;

    if (!regex.test(expiryDate)) {
        Swal.fire({
            title: 'Erro!',
            text: 'Por favor, insira uma data válida no formato MM / YYYY.',
            type: 'error',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        });
        return false; // Data inválida
    }

    // Obter mês e ano da data de expiração
    const [inputMonth, inputYear] = expiryDate.split(' / ').map(Number);

    // Obter mês e ano atuais
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth() + 1; // Janeiro é 0
    const currentYear = currentDate.getFullYear();

    // Verificar se a data de expiração é no futuro
    if (inputYear < currentYear || (inputYear === currentYear && inputMonth <= currentMonth)) {
        Swal.fire({
            title: 'Erro!',
            text: 'Por favor, insira uma data futura.',
            type: 'error',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        });
        return false; // Data não é futura
    }

    return true; // Data válida e futura
}

function exibirMensagem(type, error) {
    // Extrai as mensagens de erro do objeto 'error'
    let messages = error.messages;

    // Inicializa uma string para armazenar as mensagens formatadas
    let errorMessage = '';

    // Itera sobre as mensagens de erro e as formata
    for (let key in messages) {
        errorMessage += `${messages[key]}\n`;
    }

    // Exibe a mensagem de erro formatada
    Swal.fire({
        title: "Erro ao incluir registro",
        html: `${errorMessage}`,
        type: type,
        confirmButtonClass: "btn btn-primary w-xs mt-2",
        buttonsStyling: false,
    });
}

function forms() {
    $('#formBoleto').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            // Executar ações antes de enviar o formulário (se necessário)
            Swal.fire({
                html: 'Gerando Boleto<br>Aguarde aparecer as informações na tela',
                type: 'info',
                //confirmButtonClass: 'btn btn-primary w-xs mt-2',
                //buttonsStyling: false,
            });
        },
        success: function(responseText, statusText, xhr, $form) {
            mostrarDadosBoleto(responseText);
            Swal.close(); // Fecha o alerta
        },
        error: function(xhr, status, error) {
            Swal.close();
            // Verifica se a resposta é um JSON
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                // Exibir mensagem de erro vinda do servidor
                exibirMensagem('error', xhr.responseJSON);
            } else {
                // Exibir mensagem de erro genérica
                exibirMensagem({
                    messages: {
                        error: 'Erro desconhecido.'
                    }
                });
            }
        }
    });

    $('#formPix').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            // Executar ações antes de enviar o formulário (se necessário)
            Swal.fire({
                html: 'Gerando PIX<br>Aguarde aparecer as informações na tela',
                type: 'info',
                //confirmButtonClass: 'btn btn-primary w-xs mt-2',
                //buttonsStyling: false,
            });
        },
        success: function(responseText, statusText, xhr, $form) {
            mostrarDadosPix(responseText);
            Swal.close(); // Fecha o alerta
        },
        error: function(xhr, status, error) {
            Swal.close();
            // Verifica se a resposta é um JSON
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                // Exibir mensagem de erro vinda do servidor
                exibirMensagem('error', xhr.responseJSON);
            } else {
                // Exibir mensagem de erro genérica
                exibirMensagem({
                    messages: {
                        error: 'Erro desconhecido.'
                    }
                });
            }
        }
    });

    $('.formCredit').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {

            const expiryDate = $('#card-expiry-input').val();
            console.log(expiryDate);

            if (!validateExpiryDate(expiryDate)) {
                return false; // Impede o envio do formulário se a validação falhar
            }

            // Executar ações antes de enviar o formulário (se necessário)
            Swal.fire({
                title: 'Realizando Operação Financeira...',
                type: 'info',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });

        },
        success: function(responseText, statusText, xhr, $form) {
            var returnCode = responseText.Payment.ReturnCode;
            var successCodes = ['4', '6', '00']; // Lista de códigos de sucesso

            if (successCodes.includes(returnCode)) {
                Swal.fire({
                    title: 'Pagamento aprovado!',
                    type: 'success',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });

                $('.formCredit')[0].reset();
            } else {
                Swal.fire({
                    title: "O pagamento não foi aprovado!",
                    text: `${responseText.Payment.ReturnMessage}`,
                    type: 'error',
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false,
                });
            }
        },
        error: function(xhr, status, error) {
            // Verifica se a resposta é um JSON
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                // Exibir mensagem de erro vinda do servidor
                exibirMensagem('error', xhr.responseJSON);
            } else {
                // Exibir mensagem de erro genérica
                exibirMensagem({
                    messages: {
                        error: 'Erro desconhecido.'
                    }
                });
            }
        }
    });
}
</script>