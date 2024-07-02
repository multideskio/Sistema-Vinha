$(document).ready(function () {
    formataCampos();
    atualizarTabela();
    populateSupervisorSelect();

    $("#inSearchBtn").click(function () {
        var search = $("#inSearch").val();
        atualizarTabela(search);
    });

    $("#inSearch").keypress(function (e) {
        if (e.which === 13) {
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
        if (!isNaN(page)) {
            atualizarTabela(search, page);
        }
    });

    $('#formCad').ajaxForm({
        beforeSubmit: function () { },
        success: function () {
            atualizarTabela();
            $('#formCad')[0].reset();
            Swal.fire({
                title: 'Cadastrado!',
                icon: 'success'
            });
        },
        error: function (xhr) {
            var errorMessage = xhr.responseJSON && xhr.responseJSON.messages ? xhr.responseJSON : { messages: { error: 'Erro desconhecido.' } };
            exibirMensagem('error', errorMessage);
        }
    });
});


function populateSupervisorSelect() {
    $('#selectSupervisor').empty().removeAttr('required');
    $.getJSON(_baseUrl + "api/v1/supervisores/list", function (data) {
        $.each(data, function (index, supervisor) {
            var option = `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
            $('#selectSupervisor').append(option);
        });
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

function initializeCleave(selector, options) {
    new Cleave(selector, options);
}

// Função para exibir mensagens
function exibirMensagem(type, error) {
    let messages = error.messages;
    let errorMessage = '';

    for (let key in messages) {
        if (messages.hasOwnProperty(key)) {
            errorMessage += `${messages[key]}\n`;
        }
    }

    Swal.fire({
        title: type === 'error' ? "Erro ao incluir registro" : "Mensagem",
        text: errorMessage,
        icon: type,
        confirmButtonClass: "btn btn-primary w-xs mt-2",
        buttonsStyling: false,
    });
}


function atualizarTabela(search = false, page = 1) {
    $('.noresult').hide();
	$('#perfilCards').empty();

    $('#cardResult').hide();
    $('.loadResult').show();

    // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
    var url = _baseUrl + "api/v1/igrejas?";
    if (search) {
        url += "search=" + search + "&";
    }
    if (page) {
        url += "page=" + page;
    }

    $.getJSON(url)
        .done(function (data, textStatus, jqXHR) {
            $("#pager").html(data.pager);
            $("#numResults").html(data.num);
            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
            } else {
                $('#cardResult').show();
                $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
            }
            $.each(data.rows, function (index, row) {
                var randomColor = Math.floor(Math.random() * 16777215).toString(16);
                $('#perfilCards').append(`
                    <div class="col-xl-3 col-md-4 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="mx-auto avatar-md mb-3">
                                    <img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.razao_social.charAt(0)}';" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <h5 class="card-title mb-1">#${row.id} - ${row.razao_social}</h5>
                                <hr>
                                <div class="text-start">
                                    <p class="text-muted mb-2"><strong>Nome Fantasia:</strong> ${row.fantasia}</p>
                                    <p class="text-muted mb-2"><strong>Gerente:</strong> ${row.nome_gerente} ${row.sobre_gerente}</p>
                                    <p class="text-muted mb-2 text-primary"><strong>Dia dízimo:</strong> ${row.data_dizimo}</p>
                                    <p class="text-muted mb-2"><strong>Supervisor:</strong> ${row.nome_supervisor} ${row.sobre_supervisor}</p>
                                    <p class="text-muted mb-2"><strong>Região:</strong> ${row.regiao}</p>
                                    <p class="text-muted mb-2"><strong>UF:</strong> ${row.uf}</p>
                                    <p class="text-muted mb-2"><strong>Cidade:</strong> ${row.cidade}</p>
                                    <p class="text-muted mb-2"><strong>CNPJ:</strong> ${row.cnpj}</p>
                                    <p class="text-muted mb-2"><strong>Email:</strong> ${row.email}</p>
                                    <p class="text-muted mb-2"><strong>Celular:</strong> ${row.celular}</p>
                                    <p class="text-muted mb-0"><strong>Telefone:</strong> ${row.telefone}</p>
                                </div>
                            </div>
                            <a href="${_baseUrl}admin/igreja/${row.id}" class="btn text-white bg-primary card-footer">
                                <i class="ri-pencil-line"></i> EDITAR
                            </a>
                        </div>
                    </div>
                `)
});

            $('.loadResult').hide();

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
        });

}


function formataCampos() {
    // Inicialização de formatação de inputs com Cleave.js
    initializeCleave('.cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        uppercase: true
    });

    initializeCleave('.cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3],
        uppercase: true
    });

    initializeCleave('.telFixo', {
        numericOnly: true,
        delimiters: ['(', ') ', '-'],
        blocks: [0, 2, 4, 4]
    });

    initializeCleave('.celular', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });

    /**formata CNPJ */
    var cleave = new Cleave('.cnpj', {
        numericOnly: true,
        blocks: [2, 3, 3, 4, 2],
        delimiters: ['.', '.', '/', '-'],
        uppercase: true
    });
}