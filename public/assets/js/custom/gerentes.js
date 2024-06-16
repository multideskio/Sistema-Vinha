$(document).ready(function () {
    // Inicialização de plugins FilePond
    FilePond.registerPlugin(
        // Codifica o arquivo como dados base64
        FilePondPluginFileEncode,
        // Valida o tamanho do arquivo
        FilePondPluginFileValidateSize,
        // Corrige a orientação da imagem no mobile
        FilePondPluginImageExifOrientation,
        // Pré-visualiza imagens carregadas
        FilePondPluginImagePreview
    );

    FilePond.create(
        document.querySelector('.filepond-input-circle'), {
        labelIdle: 'Clique para carregar a imagem',
        imagePreviewHeight: 170,
        imageCropAspectRatio: '1:1',
        imageResizeTargetWidth: 200,
        imageResizeTargetHeight: 200,
        stylePanelLayout: 'compact circle',
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
    }
    );

    // Formatação de inputs com Cleave.js
    var cleaveCpf = new Cleave('.cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        uppercase: true
    });

    var cleaveCep = new Cleave('.cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3],
        uppercase: true
    });

    var cleaveTelFixo = new Cleave('.telFixo', {
        numericOnly: true,
        delimiters: ['(', ') ', '-'],
        blocks: [0, 2, 4, 4]
    });

    var cleaveCelular = new Cleave('.celular', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });

    // Atualiza a tabela ao carregar a página
    atualizarTabela();

    // Clique no botão de pesquisa
    $("#inSearchBtn").click(function (e) {
        var search = $("#inSearch").val();
        atualizarTabela(search);
    });

    // Pressiona Enter no campo de pesquisa
    $("#inSearch").keypress(function (e) {
        if (e.which === 13) {
            var search = $("#inSearch").val();
            atualizarTabela(search);
        }
    });

    // Paginação
    $("#pager").on("click", "a", function (e) {
        e.preventDefault();
        var href = $(this).attr("href");
        var urlParams = new URLSearchParams(href);
        var page = urlParams.get('page');
        var search = urlParams.get('search');

        console.log(page);

        // Verifica se o parâmetro "page" é um número
        if (!isNaN(page)) {
            atualizarTabela(search, page);
        }
    });

    // Inicialização do formulário AJAX
    $('#formCad').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            // Ações antes de enviar o formulário, se necessário
        },
        success: function (responseText, statusText, xhr, $form) {
            atualizarTabela();
            $('#formCad')[0].reset();
            Swal.fire({
                title: 'Cadastrado!',
                icon: 'success',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                exibirMensagem('error', xhr.responseJSON);
            } else {
                exibirMensagem('error', { messages: { error: 'Erro desconhecido.' } });
            }
        }
    });
});

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
        icon: type,
        confirmButtonClass: "btn btn-primary w-xs mt-2",
        buttonsStyling: false,
    });
}

// Função para atualizar a tabela
function atualizarTabela(search = false, page = 1) {
    $('#tabela-dados').empty(); // Limpa o conteúdo da tabela antes de atualizar

    $('#cardResult').hide();
    $('.loadResult').show();

    // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
    var url = _baseUrl + "api/v1/gerentes?";
    if (search) {
        url += "search=" + encodeURIComponent(search) + "&";
    }
    if (page) {
        url += "page=" + page;
    }

    // Requisição AJAX para obter os dados
    $.getJSON(url)
        .done(function (data, textStatus, jqXHR) {
            $("#numResults").html(data.num);
            $("#pager").html(data.pager);

            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
            } else {
                $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
                $('#cardResult').show();
            }

            // Itera sobre os dados recebidos e adiciona as linhas à tabela
            $.each(data.rows, function (index, gerente) {
                var randomColor = Math.floor(Math.random() * 16777215).toString(16);
                var newRow = `
                <tr>
                    <td>
                        <div class="image-container" style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%;">
                            <img src="${gerente.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${gerente.nome.charAt(0)}';" style="width: 100%; height: 100%; object-fit: cover;" class="rounded-circle">
                        </div>
                    </td>
                    <td class="align-middle">#${gerente.id}</td>
                    <td class="align-middle">${gerente.nome} ${gerente.sobrenome}</td>
                    <td class="align-middle">${gerente.cpf}</td>
                    <td class="align-middle">
                        <a href="mailto:${gerente.email}"><b>${gerente.email}</b></a>
                    </td>
                    <td class="align-middle">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel Celular" href="tel:${gerente.celular}"><span class="badge bg-dark rounded-pill">${gerente.celular}</span></a>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel fixo" href="tel:${gerente.telefone}"><span class="badge bg-success rounded-pill">${gerente.telefone}</span></a>
                    </td>
                    <td class="align-middle">
                        <div class="btn-group" role="group">
                            <a href="${_baseUrl}admin/gerente/${gerente.id}" class="btn btn-dark btn-sm sa-dark">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <a href="#" onclick="recursoindisponivel()" class="btn btn-danger btn-sm sa-warning">
                                <i class="ri-delete-bin-6-line"></i>
                            </a>
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
            exibirMensagem('error', { messages: { error: 'Erro ao carregar os dados.' } });
            $('.loadResult').hide();
        });
}

function escapeString(str) {
    return str.replace(/'/g, "\\'");
}




/*function update(id, nome, sobrenome, cpf, email, cep, uf, cidade, bairro, endereco, dia, tel, cel) {
    //alert(id);
    $('#updateGerente').modal('show');
    $('#nomeUpdate').val(nome)
    $('#sobrenomeUpdate').val(sobrenome)
    $('#cpfUpdate').val(cpf)
    $('#emailUpdate').val(email)
    $('#cepUpdate').val(cep)
    $('#ufUpdate').val(uf)
    $('#cidadeUpdate').val(cidade)
    $('#bairroUpdate').val(bairro)
    $('#enderecoUpdate').val(endereco)
    $('#diaUpdate').val(dia)
    $('#telUpdate').val(tel)
    $('#celUpdate').val(cel)
    $('#formUpdate').removeAttr("action").attr("action", `${_baseUrl}api/v1/gerentes/${id}`)
}
$(document).ready(function() {
    $('#formUpdate').ajaxForm({
        type: 'PUT',
        beforeSubmit: function(formData, jqForm, options) {
            // Executar ações antes de enviar o formulário (se necessário)
        },
        success: function(responseText, statusText, xhr, $form) {
            $('#updateGerente').modal('hide');
            atualizarTabela();
            Swal.fire({
                title: 'Atualizado!',
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
});*/


