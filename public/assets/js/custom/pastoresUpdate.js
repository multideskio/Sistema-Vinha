$(document).ready(function () {
    searchUpdate(_idSearch)

    $(".enviaLinks").on('change', function () {
        $('.formTexts').submit();
    });

    $('.formTexts').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function (responseText, statusText, xhr, $form) {
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

    $('.formGeral').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function (responseText, statusText, xhr, $form) {
            searchUpdate(_idSearch)
            Swal.fire({
                text: 'Atualizado com sucesso!',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            Swal.fire({
                text: 'Erro ao atualizar...',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
        }
    });

    $("#profile-img-file-input").on('change', function () {
        $('.formUpload').submit();
    });

    $('.formUpload').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            console.log('Enviando...')
        },
        success: function (responseText, statusText, xhr, $form) {
            Swal.fire({
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
});

function searchUpdate(id) {
    if (id) {
        // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
        var url = _baseUrl + `api/v1/pastores/${id}`;
        $.getJSON(url)
            .done(function (data, textStatus, jqXHR) {
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

                //$("#gerente").val(data.gerente);
                //$("#regiao").val(data.regiao);

                populateSupervisorSelect(data.idSupervisor)
                /*listGerentes(data.idGerente)*/

            }).fail(function (jqXHR, textStatus, errorThrown) {

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
                });;


            });

        // Tratamento de erro para a imagem
        $('#fotoPerfil').on('error', function () {
            $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
        });
    }
}


function populateSupervisorSelect(idAtual) {
    $('#selectSupervisor').empty().removeAttr('required');

    $.getJSON(_baseUrl + "api/v1/supervisores/list", function (data) {

        data.forEach(function (supervisor) {
            if (idAtual === supervisor.id) {
                var option = `<option selected value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
            } else {
                var option = `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
            }

            $('#selectSupervisor').append(option);
        });

        //console.log('Id atual '+ idAtual)
        //console.log('IDs '+ supervisor.id)
        // Adiciona os atributos e inicializa o plugin Choices após adicionar todas as opções
        $('#selectSupervisor').attr('required', true).attr('data-choices', true);

        new Choices('#selectSupervisor');

    }).fail(function () {
        Swal.fire({
            title: 'Cadastre supervisores antes...',
            icon: 'error',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        }).then(function (result) {
            history.back();
        });
    });
}

var teste = "0005";