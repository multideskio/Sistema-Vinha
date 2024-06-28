



function formata() {
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
}

function search() {
    // Clique no botão de pesquisa
    $("#inSearchBtn").on('click', function (e) {
        var search = $("#inSearch").val();
        atualizarTabela(search);
    });

    // Pressiona Enter no campo de pesquisa
    $("#inSearch").on('keypress', function (e) {
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
}

function cadastro(){
    $('#form').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            // Ações antes de enviar o formulário, se necessário
        },
        success: function (responseText, statusText, xhr, $form) {
            atualizarTabela();
            $('#form')[0].reset();
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
}

// Função para atualizar a tabela
function atualizarTabela(search = false, page = 1) {
    $('#tabela-dados').empty(); // Limpa o conteúdo da tabela antes de atualizar

    $('#cardResult').hide();
    $('.loadResult').show();

    // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
    var url = _baseUrl + "api/v1/administradores?";
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
                            <a href="${_baseUrl}admin/admin/${gerente.id}" class="btn btn-primary btn-sm sa-dark">
                                <!-- <i class="ri-pencil-line"></i> --> <b>EDITAR</b>
                            </a>
                            <!-- <a href="#" onclick="recursoindisponivel()" class="btn btn-danger btn-sm sa-warning">
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
            exibirMensagem('error', { messages: { error: 'Erro ao carregar os dados.' } });
            $('.loadResult').hide();
        });
}


$(document).ready(function () {
    formata();
    atualizarTabela();
    search();
});