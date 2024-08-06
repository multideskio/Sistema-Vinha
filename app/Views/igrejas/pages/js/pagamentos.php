<script>
    function mostrarDadosPix(data) {
        //console.log(data);

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

    function atualizarStatusPix(code) {
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
</script>

<script>
    $(document).ready(function() {
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

                // Limpar o formulário
                //$('#formCad')[0].reset();
                // Exibir mensagem de sucesso
                //exibirMensagem('success', 'Sucesso: ' + responseText);
                /*Swal.fire({
                    title: 'Pix Gerado!',
                    type: 'success',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });*/
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
    });
</script>


<script>
    $(document).ready(function() {
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

        $('.formCredit').ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {

                const expiryDate = $('#card-expiry-input').val();

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
    });
</script>

<script>
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
</script>