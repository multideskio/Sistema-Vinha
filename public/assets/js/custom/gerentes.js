$(document).ready(function () {
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
    $('#perfilCards').empty();
    $('#cardResult').hide();
    $('#noresult').hide();
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
                var newPerfilCard = `<div class="col-xl-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="mx-auto avatar-md mb-3">
                                <img src="${gerente.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${gerente.nome.charAt(0)}';" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h5 class="card-title mb-1">#${gerente.id} - ${gerente.nome} ${gerente.sobrenome}</h5>
                            <hr>
                            <div class="text-start">
                                <p class="text-muted mb-2"><strong>CPF:</strong> ${gerente.cpf}</p>
                                <p class="text-muted mb-2"><strong>Email:</strong> ${gerente.email}</p>
                                <p class="text-muted mb-2"><strong>Celular:</strong> ${gerente.celular}</p>
                                <p class="text-muted mb-0"><strong>Telefone:</strong> ${gerente.telefone}</p>
                            </div>
                        </div>
                        <a href="${_baseUrl}admin/gerente/${gerente.id}" class="btn btn-primary btn-sm text-white bg-primary card-footer">
                            <i class="ri-pencil-line"></i> EDITAR
                        </a>
                    </div>
                </div>`;
                $('#perfilCards').append(newPerfilCard);
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
