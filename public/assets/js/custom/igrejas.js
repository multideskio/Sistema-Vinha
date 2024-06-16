$(document).ready(function () {
    // Inicialização de plugins FilePond
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview
    );

    FilePond.create(document.querySelector('.filepond-input-circle'), {
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
    });

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
                icon: 'success',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
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
    }).fail(function() {
        Swal.fire({
            title: 'Cadastre supervisores antes...',
            icon: 'error',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        }).then(function(result) {
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
    $('#tabela-dados').empty(); // Limpa o conteúdo da tabela antes de atualizar

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
                var newRow = `
    <tr>
        <td>
            <div class="image-container" style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%;">
                <img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.razao_social.charAt(0)}';" style="width: 100%; height: 100%; object-fit: cover;" class="rounded-circle">
            </div>
        </td>
        <td class="align-middle">#${row.id}</td>
        <td class="align-middle">${row.razao_social ? row.razao_social : ''}</td>
        <td class="align-middle">${row.fantasia ? row.fantasia : ''}</td>
        <td class="align-middle">${row.regiao ? row.regiao : ''}</td>
        <td class="align-middle">${row.nome_gerente ? row.nome_gerente : ''} ${row.sobre_gerente ? row.sobre_gerente : ''}</td>
        <td class="align-middle">${row.nome_supervisor ? row.nome_supervisor : ''} ${row.sobre_supervisor ? row.sobre_supervisor : ''}</td>
        <td class="align-middle">
            ${row.cnpj ? row.uf : ''}
        </td>
        <td class="align-middle">
            ${row.cnpj ? row.cidade : ''}
        </td>
        <td class="align-middle">
            ${row.cnpj ? row.cnpj : ''}
        </td>
        <td class="align-middle">
            <a href="mailto:${row.email ? row.email : ''}"><b>${row.email ? row.email : ''}</b></a>
        </td>
        <td class="align-middle">
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel Celular" href="tel:${row.celular ? row.celular : ''}"><span class="badge bg-dark rounded-pill">${row.celular ? row.celular : ''}</span></a>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel fixo" href="tel:${row.telefone ? row.telefone : ''}"><span class="badge bg-success rounded-pill">${row.telefone ? row.telefone : ''}</span></a>
        </td>
        <td class="align-middle">
            <div class="btn-group" role="group">
                <a href="${_baseUrl}admin/igreja/${row.id}" class="btn btn-primary btn-sm">
                    <!-- <i class="ri-pencil-line"></i> --> EDITAR
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
        });

}