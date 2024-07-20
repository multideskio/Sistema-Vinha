$(document).ready(function () {
    // Configuração do plugin jQuery Form
    $('#formUpdate').ajaxForm({
        type: 'PUT',
        beforeSubmit: function (formData, jqForm, options) {
            // Executar ações antes de enviar o formulário (se necessário)
        },
        success: function (responseText, statusText, xhr, $form) {
            //$('#updateRegiao').modal('hide');
            atualizarTabela();
            // Limpar o formulário
            exibirMensagem('success', 'Atualizado!');
        },
        error: function (xhr, status, error) {
            // Verifica se a resposta é um JSON
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                // Exibir mensagem de erro vinda do servidor
                exibirMensagem('error', xhr.responseJSON.messages.nome);
            } else {
                // Exibir mensagem de erro genérica
                exibirMensagem('error', { messages: { error: 'Erro desconhecido.' } });
            }
        }
    });

    $('#formCad').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            // Executar ações antes de enviar o formulário (se necessário)
        },
        success: function (responseText, statusText, xhr, $form) {
            atualizarTabela();
            // Limpar o formulário
            $('#formCad')[0].reset();
            // Exibir mensagem de sucesso
            exibirMensagem('success', 'Cadastrado!');
        },
        error: function (xhr, status, error) {
            // Verifica se a resposta é um JSON
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                // Exibir mensagem de erro vinda do servidor
                exibirMensagem('error', xhr.responseJSON);
            } else {
                // Exibir mensagem de erro genérica
                exibirMensagem('error', { messages: { error: 'Erro desconhecido.' } });
            }
        }
    });

    $("#inSearchBtn").click(function (e) {
        e.preventDefault();
        var search = $("#inSearch").val();
        atualizarTabela(search);
    });

    $("#inSearch").keypress(function (e) {
        // Verifica se a tecla pressionada é a tecla Enter (código 13)
        if (e.which === 13) {
            e.preventDefault();
            var search = $("#inSearch").val();
            atualizarTabela(search);
        }
    });

    $("#pager").on("click", "a", function (e) {
        e.preventDefault();
        var href = $(this).attr("href");
        var urlParams = new URLSearchParams(href);
        var page = urlParams.get('page');
        var search = urlParams.get('search');

        

        // Verifica se o parâmetro "page" é um número
        if (!isNaN(page)) {
            // Chama a função atualizarTabela com os parâmetros corretos
            atualizarTabela(search, page);
        }
    });

    atualizarTabela();
});

function update(id, nome, desc) {
    //alert(id);
    $('#updateRegiao').modal('show');
    $('#idRegiao').html(id)
    $('#regiaoUpdate').val(nome)
    $('#descUpdate').val(desc)

    $('.regiaoUpdate').html(nome)
    $('#formUpdate').removeAttr("action").attr("action", `${_baseUrl}api/v1/regioes/${id}`)
}

// Função para atualizar a tabela de regiões
function atualizarTabela(search = false, page = 1) {
    $('#tabela-dados').empty(); // Limpa o conteúdo da tabela antes de atualizar

    $('#cardResult').hide();
    $('.loadResult').show();

    // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
    var url = _baseUrl + "api/v1/regioes?";
    if (search) {
        url += "search=" + search + "&";
    }
    if (page) {
        url += "page=" + page;
    }

    // Requisição AJAX para obter os dados das regiões
    $.getJSON(url)
        .done(function (data, textStatus, jqXHR) {
            $("#pager").html(data.pager);
            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
            } else {
                $('#cardResult').show();
                $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
            }
            // Itera sobre os dados recebidos e adiciona as linhas à tabela
            $.each(data.rows, function (index, regiao) {
                var newRow = `
                    <tr>
                        <td>#${regiao.id}</td>
                        <td>${regiao.nome}<br>${regiao.descricao}</td>
                        <td>
                        <div class="btn-group" role="group">
                            <a href="#" class="btn btn-dark btn-sm" onclick="update('${regiao.id}', '${regiao.nome}', '${regiao.descricao}')">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <!-- <a href="#" class="btn btn-danger btn-sm sa-warning" onclick="excluir('${regiao.id}', 'regioes')">
                                <i class="ri-delete-bin-6-line"></i>
                            </a> -->
                            </div>
                        </td>
                    </tr>
                `;
                $('#tabela-dados').append(newRow);
            });

            $('.loadResult').hide();
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
        });

}

function exibirMensagem(type, message) {
    Swal.fire({
        title: type.charAt(0).toUpperCase() + type.slice(1), // Capitalize the first letter
        text: message,
        icon: type,
        confirmButtonClass: 'btn btn-primary w-xs mt-2',
        buttonsStyling: false,
    });
}


function excluir(id, endPoint) {
    // Exibe uma mensagem de confirmação ao usuário antes de excluir o registro
    Swal.fire({
        title: "Tem certeza?",
        text: "Você não poderá reverter isso!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, exclua-o!",
        confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
        cancelButtonClass: 'btn btn-danger w-xs mt-2',
        buttonsStyling: false,
        showCloseButton: true
    }).then((result) => {
        // Verifica se o usuário confirmou a exclusão
        if (result.value) {
            // Envia uma requisição AJAX para excluir o registro
            $.ajax({
                type: "DELETE",
                url: `${_baseUrl}api/v1/${endPoint}/${id}`,
                dataType: "JSON",
            }).done(() => {
                // Exibe uma mensagem de sucesso após a exclusão e atualiza a tabela
                Swal.fire({
                    title: 'Excluído!',
                    text: 'O registro foi excluído com sucesso.',
                    icon: 'success',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                });

                /*setTimeout(() => {
                    location.reload();
                }, 1200);*/
                atualizarTabela()


            }).fail(() => {
                // Exibe uma mensagem de erro em caso de falha na requisição AJAX
                Swal.fire({
                    title: "Erro ao excluir",
                    text: "Ocorreu um erro ao tentar excluir o registro.",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false,
                });
            });
        }
    });
}