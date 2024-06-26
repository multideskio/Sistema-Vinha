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
        },
        success: function (responseText, statusText, xhr, $form) {
            dataConfig();
            Swal.fire({
                title: 'OK!',
                text: 'Imagem atualizada com sucesso!',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: 'Erro ao atualizar imagem',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
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
        },
        success: function (responseText, statusText, xhr, $form) {
            dataConfig();
            Swal.fire({
                title: 'OK!',
                text: 'Atualizado com sucesso!',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: 'Erro ao atualizar...',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
        }
    });
}

function dataConfig() {
    $.getJSON(`${_baseUrl}/api/v1/administracao/${idEmp}`)
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
            setCheckboxState("#ativarSMTP", data.ativar_smtp == 1);

            //WhatsApp
            $("#urlAPI").val(data.url_api)
            $("#instanceAPI").val(data.instance_api)
            $("#keyAPI").val(data.key_api)
            setCheckboxState("#ativawa", data.ativar_wa == 1);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
            $('.loadResult').hide();
            Swal.fire({
                title: 'Os dados não foram enconrados',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
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
    // Formatação de inputs com Cleave.js
    var cleave = new Cleave('#cnpj', {
        numericOnly: true,
        blocks: [2, 3, 3, 4, 2],
        delimiters: ['.', '.', '/', '-'],
        uppercase: true
    });

    var cleaveCep = new Cleave('#cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3],
        uppercase: true
    });

    var cleaveTelFixo = new Cleave('#fixo', {
        numericOnly: true,
        delimiters: ['(', ') ', '-'],
        blocks: [0, 2, 4, 4]
    });

    var cleaveCelular = new Cleave('#celular', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });
}

function envioDeTeste() {
    $("#testarEmail").on('click', function () {
        var email = $('#email').val();
        Swal.fire({
            title: `Estamos enviando um e-mail de teste para ${email}`,
            icon: 'info',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
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
                    title: 'OK!',
                    text: 'O E-mail foi enviado!',
                    icon: 'success'
                })
            },
            error: function (error) {
                Swal.fire({
                    title: `O e-email não foi enviado.`,
                    text: `${error.responseJSON.messages.error}`,
                    icon: 'error',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false
                })
                //console.error("Erro ao enviar dados:", error.responseJSON.messages.error);
            }
        });
    })
}