function formatInputs() {
    $('.cpf').mask('000.000.000-00')
    $('.cep').mask('00000-000')
    $('.telFixo').mask('(00) 0000-0000')
    $('.celular').mask('+00 (00) 0 0000-0000')
}
function sends() {
    $(".enviaLinks").on('change', function() {
        $('.formTexts').submit();
    });

    $('.formTexts').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function(responseText, statusText, xhr, $form) {
            $(".alertAlterado").show(),
                setTimeout(() => {
                    $(".alertAlterado").fadeOut()
                }, 1200);
        },
        error: function(xhr, status, error) {
            console.log(xhr)
            console.log(status)
            console.log(error)
        }
    });
    $('.formGeral').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            options.type = 'PUT'
            Swal.fire({
                html: 'Enviando dados!',
                icon: 'info'
            })
        },
        success: function(responseText, statusText, xhr, $form) {
            Swal.fire({
                html: 'Atualizado com sucesso!',
                icon: 'success'
            })
            searchUpdate(_idSearch);
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Erro ao atualizar...',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
        }
    });
    $("#profile-img-file-input").on('change', function() {
        $('.formUpload').submit();
    });
    $('.formUpload').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            Swal.fire({
                html: 'Enviando imagem!',
                icon: 'info'
            })
        },
        success: function(responseText, statusText, xhr, $form) {
            Swal.fire({
                html: 'Imagem atualizada com sucesso!',
                icon: 'success'
            })
        },
        error: function(xhr, status, error) {
            Swal.fire({
                html: 'Erro ao atualizar imagem',
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
function searchUpdate(id) {
    if (id) {
        // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
        var url = _baseUrl + `api/v1/gerentes/${id}`;
        $.getJSON(url)
            .done(function(data, textStatus, jqXHR) {
                if (data.foto) {
                    $("#fotoPerfil").attr('src', data.foto);
                }
                $("#viewNameUser").html(data.nome);
                $("#facebook").val(data.facebook);
                $("#website").val(data.website);
                $("#instagram").val(data.instagram);
                $("#nome").val(data.nome);
                $("#sobrenome").val(data.sobrenome);
                $("#cpf").val(data.cpf);
                $("#cel").val(data.celular);
                $("#email").val(data.email);
                $("#tel").val(data.telefone);
                $("#cep").val(data.cep);
                $("#uf").val(data.uf);
                $("#cidade").val(data.cidade);
                $("#bairro").val(data.bairro);
                $("#complemento").val(data.complemento);
                $("#dizimo").val(data.data_dizimo);

                globalIdLogin = data.id_login;

                atualizarTabela();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');
                //console.error("Erro ao carregar os dados:", textStatus, errorThrown);
                Swal.fire({
                    title: 'Os dados não foram enconrados',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                }).then(function(result) {
                    history.back();
                });
                $('.loadResult').hide();
            });
        // Tratamento de erro para a imagem
        $('#fotoPerfil').on('error', function() {
            $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
        });
    }
}
$(document).ready(function() {
    searchUpdate(_idSearch)
    formatInputs()
    sends()
});