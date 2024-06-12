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
                    title: 'Gerando PIX...',
                    type: 'info',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });
            },
            success: function(responseText, statusText, xhr, $form) {
                mostrarDadosPix(responseText);
                // Limpar o formulário
                //$('#formCad')[0].reset();
                // Exibir mensagem de sucesso
                //exibirMensagem('success', 'Sucesso: ' + responseText);
                Swal.fire({
                    title: 'Pix Gerado!',
                    type: 'success',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });
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
    $(document).ready(function() {
        $('.formCredit').ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {
                // Executar ações antes de enviar o formulário (se necessário)
                Swal.fire({
                    title: 'Realizando Operação Financeira...',
                    type: 'info',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });
            },
            success: function(responseText, statusText, xhr, $form) {
                // Exibir mensagem de sucesso
                if (responseText.Payment.ReturnMessage === 'Operation Successful') {
                    Swal.fire({
                        title: 'Pagamento aprovado!',
                        type: 'success',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false,
                    });

                    $('.formCredit')[0].reset();
                } else if (responseText.Payment.ReturnMessage === 'Transacao autorizada') {
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
                //exibirMensagem('success', 'Sucesso: ' + responseText);
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
            text: `${errorMessage}`,
            type: type,
            confirmButtonClass: "btn btn-primary w-xs mt-2",
            buttonsStyling: false,
        });
    }
</script>