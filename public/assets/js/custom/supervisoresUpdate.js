function formatImput() {
    // Formatação de inputs com Cleave.js
    $('.cpf').mask('000.000.000-00')
    $('.cep').mask('00000-000')
    $('.telFixo').mask('(00) 0000-0000')
    $('.celular').mask('+00 (00) 0 0000-0000')
}
function sends() {
    $(".enviaLinks").on('change', () => {
        $('.formTexts').submit()
    });
    $("#profile-img-file-input").on('change', () => {
        $('.formUpload').submit()
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
                text: 'Enviando dados!',
                icon: 'info'
            })
        },
        success: function(responseText, statusText, xhr, $form) {
            Swal.fire({
                text: 'Atualizado com sucesso!',
                icon: 'success'
            })
        },
        error: function(xhr, status, error) {
            Swal.fire({
                text: 'Erro ao atualizar...',
                icon: 'error'
            });
        }
    });

    $('.formUpload').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            console.log('Enviando...')
            Swal.fire({
                text: 'Enviando imagem!',
                icon: 'info'
            })
        },
        success: function(responseText, statusText, xhr, $form) {
            Swal.fire({
                text: 'Imagem atualizada com sucesso!',
                icon: 'success'
            })
        },
        error: function(xhr, status, error) {
            Swal.fire({
                text: 'Erro ao atualizar imagem',
                icon: 'error'
            });
        }
    });
}
function searchUpdate(id) {
    if (id) {
        // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
        var url = `${_baseUrl}api/v1/supervisores/${id}`;
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

                $("#gerente").val(data.gerente);
                $("#regiao").val(data.regiao);

                globalIdLogin = data.id_login;
                atualizarTabela();

                listRegioes(data.idRegiao)
                listGerentes(data.idGerente)

            }).fail(function(jqXHR, textStatus, errorThrown) {
                $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');
                console.error("Erro ao carregar os dados:", textStatus, errorThrown);
                $('.loadResult').hide();
                Swal.fire({
                    text: 'Os dados não foram enconrados',
                    icon: 'error'
                }).then(function(result) {
                    history.back();
                });
            });
        // Tratamento de erro para a imagem
        $('#fotoPerfil').on('error', function() {
            $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
        });
    } else {
        Swal.fire({
            text: 'Os dados não foram encontrados',
            icon: 'error'
        }).then(function(result) {
            history.back();
        });
    }
}
function listRegioes(idAtual) {
    $('#selectRegiao').empty().removeAttr('required');
    $.getJSON(`${_baseUrl}api/v1/regioes`, {}, (data) => {
        data.rows.forEach(regiao => {
            if (idAtual === regiao.id) {
                $('#selectRegiao').append(`<option selected value="${regiao.id}">${regiao.id} - ${regiao.nome}</option>`);
            } else {
                $('#selectRegiao').append(`<option value="${regiao.id}">${regiao.id} - ${regiao.nome}</option>`);
            }
        });
        // Adiciona os atributos e inicializa o plugin Choices após adicionar todas as opções
        $('#selectRegiao').attr('required', true).attr('data-choices', true);
        new Choices('#selectRegiao');
    }).fail(() => {
        Swal.fire({
            text: 'Cadastre regiões antes de cadastrar um supervisor...',
            icon: 'error'
        }).then((result) => {
            history.back();
        });
    });
}
function listGerentes(idAtual) {
    $('#selectGerentes').empty().removeAttr('required');

    $.getJSON(`${_baseUrl}api/v1/gerentes/list`, {}, (data) => {
        data.forEach(gerente => {
            if (idAtual === gerente.id) {
                $('#selectGerentes').append(`<option selected value="${gerente.id}">${gerente.id} - ${gerente.nome} ${gerente.sobrenome}</option>`);
            } else {
                $('#selectGerentes').append(`<option value="${gerente.id}">${gerente.id} - ${gerente.nome} ${gerente.sobrenome}</option>`);
            }
        });
        // Adiciona os atributos e inicializa o plugin Choices após adicionar todas as opções
        $('#selectGerentes').attr('required', true).attr('data-choices', true);
        new Choices('#selectGerentes');
    }).fail(() => {
        Swal.fire({
            text: 'Cadastre gerentes antes de cadastrar um supervisor...',
            icon: 'error'
        }).then((result) => {
            history.back();
        });
    });
}
$(document).ready(function() {
    searchUpdate(_idSearch)
    formatImput()
    sends();
});