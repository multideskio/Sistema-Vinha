$(document).ready(function () {
    dataConfig();
    updateImage();
    updateSocial();
    updateGeral();
    formataCampos();
    envioDeTeste();
})

function updateSocial() {
    $(".enviaLinks").on('change', function () {
        $('.formTexts').submit();
    });

    $('.formTexts').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function (responseText, statusText, xhr, $form) {
            dataConfig();
            $(".alertAlterado").show(),
                setTimeout(() => {
                    $(".alertAlterado").fadeOut()
                }, 1200);
        },
        error: function (xhr, status, error) {
            console.log(xhr)
            console.log(status)
            console.log(error)
        }
    });
}

function updateImage() {

    $("#profile-img-file-input").on('change', function () {
        $('.formUpload').submit();
    });

    $('.formUpload').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            console.log('Enviando...')
            Swal.fire({
                text: 'Enviando imagem!',
                icon: 'info'
            })
        },
        success: function (responseText, statusText, xhr, $form) {
            dataConfig();
            Swal.fire({
                text: 'Imagem atualizada com sucesso!',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            Swal.fire({
                text: 'Erro ao atualizar imagem',
                icon: 'error'
            });
            console.log(xhr)
            console.log(status)
            console.log(error)
        }
    });
}

function updateGeral() {
    $('.formGeral').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            options.type = 'PUT'
            Swal.fire({
                text: 'Enviando dados!',
                icon: 'info'
            })
        },
        success: function (responseText, statusText, xhr, $form) {
            dataConfig();
            Swal.fire({
                text: 'Excutado com sucesso!',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            Swal.fire({
                text: 'Ocorreu um erro...',
                icon: 'error'
            });
        }
    });
}

function dataConfig() {
    $.getJSON(`${_baseUrl}api/v1/administracao/${idEmp}`)
        .done(function (data) {
            const setCheckboxState = (selector, state) => {
                $(selector).prop('checked', state);
            };

            if (data.logo) {
                $("#fotoPerfil").attr('src', data.logo);
            }

            $("#viewNameUser").html(data.empresa)

            //Redes sociais
            $("#facebook").val(data.facebook);
            $("#website").val(data.site);
            $("#instagram").val(data.instagram);

            //Form 1
            $("#cnpj").val(data.cnpj)
            $("#razaosocial").val(data.empresa)
            $("#email").val(data.email)
            $("#fixo").val(data.telefone)
            $("#celular").val(data.celular)
            $("#cep").val(data.cep)
            $("#uf").val(data.uf)
            $("#cidade").val(data.cidade)
            $("#bairro").val(data.bairro)
            $("#complemento").val(data.complemento)

            //SMTP
            $("#emailRemetente").val(data.email_remetente)
            $("#nomeRemetente").val(data.nome_remetente)
            $("#smtpHOST").val(data.smtp_host)
            $("#smtpLOGIN").val(data.smtp_user)
            $("#smtpPASS").val(data.smtp_pass)
            $("#smtpPORT").val(data.smtp_port)
            
            let smtpCRYPT = data.smtp_crypt;

            $("#smtpCRYPT option").each(function () {
                if ($(this).val() === smtpCRYPT) {
                    $(this).prop("selected", true);
                }
            });

            setCheckboxState("#ativarSMTP", data.ativar_smtp == 1);

            //WhatsApp
            $("#urlAPI").val(data.url_api)
            $("#instanceAPI").val(data.instance_api)
            $("#keyAPI").val(data.key_api)
            
            setCheckboxState("#ativawa", data.ativar_wa == 1);

            //s3
            //$("#s3Regiao").val(data.s3_region)
            $("#s3Bucket").val(data.s3_bucket_name)
            $("#s3Id").val(data.s3_access_key_id)
            $("#s3Key").val(data.s3_secret_access_key)
            $("#s3Cdn").val(data.s3_cdn)

            // Supondo que você recebeu a região retornada em uma variável chamada data.s3_region
            let regiaoSelecionada = data.s3_region;

            // Iterar sobre as opções do select e marcar a opção correspondente como selecionada
            $("#s3Region option").each(function () {
                if ($(this).val() === regiaoSelecionada) {
                    $(this).prop("selected", true);
                }
            });

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
            $('.loadResult').hide();
            Swal.fire({
                title: 'Os dados não foram encontrados',
                icon: 'error'
            }).then(function (result) {
                history.back();
            });
        });

    // Tratamento de erro para a imagem
    $('#fotoPerfil').on('error', function () {
        $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=M');
    });
}

function formataCampos() {
    const maskConfigs = [
        { selector: '.cpf', mask: '000.000.000-00' },
        { selector: '.cep', mask: '00000-000' },
        { selector: '.telFixo', mask: '(00) 0000-0000' },
        { selector: '.celular', mask: '+00 (00) 0 0000-0000' },
        { selector: '#numberSend', mask: '+00 (00) 0 0000-0000' },
        { selector: '.cnpj', mask: '00.000.000/0000-00' }
    ];

    maskConfigs.forEach(config => {
        $(config.selector).mask(config.mask);
    });
}


function envioDeTeste() {
    $("#testarS3").on('click', function () {
        Swal.fire({
            text: `Realizando teste`,
            icon: 'info'
        });

        $.ajax({
            url: `${_baseUrl}api/v1/administracao/teste/s3`,
            method: 'GET',
            dataType: 'json'
        }).done(function (data, textStatus, jqXHR) {
            let iconType = data.status === 'success' ? 'success' : 'error';
            Swal.fire({
                title: `Teste S3.`,
                text: data.message,
                icon: iconType,
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false
            });
        }).fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire({
                text: 'Ocorreu um erro ao processar sua solicitação.',
                icon: 'error'
            });
        }).always(function () {
            // Executa após a requisição (seja sucesso ou falha)
        });
    });

    $("#testarEmail").on('click', function () {
        let email = $('#emailUser').val();
        console.log(email);
        Swal.fire({
            title: `Estamos enviando um e-mail de teste para ${email}`,
            icon: 'info'
        })

        $.ajax({
            type: "POST",
            url: `${_baseUrl}api/v1/email/teste`,
            data: {
                'email': email
            },
            dataType: "json",
            success: function (response) {
                Swal.fire({
                    text: 'O E-mail foi enviado!',
                    icon: 'success'
                })
            },
            error: function (error) {
                Swal.fire({
                    title: `O e-email não foi enviado.`,
                    text: `${error.responseJSON.messages.error}`,
                    icon: 'error'
                })
            }
        });
    })
}