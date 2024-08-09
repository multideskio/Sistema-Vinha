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

function cadastro() {
    console.log('Function create init')
    $('#form0').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            // Ações antes de enviar o formulário, se necessário
        },
        success: function (responseText, statusText, xhr, $form) {
            atualizarTabela();
            $('#form')[0].reset();
            Swal.fire({
                text: 'Cadastrado!',
                icon: 'success'
            });
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                //exibirMensagem('error', xhr.responseJSON);
                Swal.fire({
                    title: 'Ocorreu um erro',
                    text: xhr.responseJSON.messages.error,
                    icon: 'error'
                });
            } else {
                //exibirMensagem('error', { messages: { error: 'Erro desconhecido.' } });
                Swal.fire({
                    title: 'Ocorreu um erro',
                    text: 'Erro desconhecido...',
                    icon: 'error'
                });
            }
        }
    });
}

// Função para atualizar a tabela
function atualizarTabela(search = false, page = 1) {
    $('.noresult').hide();
	$('#perfilCards').empty();

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

            data.rows.forEach(row => {
                var randomColor = Math.floor(Math.random() * 16777215).toString(16);
                $('#perfilCards').append(`<div class="col-xl-3">
                    <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                    <div class="mx-auto avatar-md mb-3">
                    <img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.nome.charAt(0)}';" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h5 class="card-title mb-1">#${row.id} - ${row.nome} ${row.sobrenome}</h5>
                    <hr>
                    <div class="text-start">
                    <p class="text-muted mb-2"><strong>CPF:</strong> ${row.cpf}</p>
                    <p class="text-muted mb-2"><strong>Email:</strong> ${row.email}</p>
                    <p class="text-muted mb-2"><strong>Celular:</strong> ${row.celular}</p>
                    <p class="text-muted mb-0"><strong>Telefone:</strong> ${row.telefone}</p>
                    </div>
                    </div>
                    <a href="${_baseUrl}admin/admin/${row.id}" class="btn text-white bg-primary card-footer">
                    <i class="ri-pencil-line"></i> EDITAR
                    </a>
                    </div>
                    </div>`);
            })

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
    cadastro();
});