$(document).ready(function () {
    // Inicialização de plugins FilePond
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
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

    // Função para inicializar Cleave.js
    const initCleave = (selector, options) => new Cleave(selector, options);

    initCleave('.cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        uppercase: true
    });

    initCleave('.cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3],
        uppercase: true
    });

    initCleave('.telFixo', {
        numericOnly: true,
        delimiters: ['(', ') ', '-'],
        blocks: [0, 2, 4, 4]
    });

    initCleave('.celular', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });

    // Atualiza a tabela ao carregar a página
    atualizarTabela();
    listRegioes();
    listGerentes();

    // Clique no botão de pesquisa e enter no campo de pesquisa
    const searchHandler = () => {
        const search = $("#inSearch").val();
        atualizarTabela(search);
    };

    $("#inSearchBtn").click(searchHandler);

    $("#inSearch").keypress(function (e) {
        if (e.which === 13) {
            searchHandler();
        }
    });

    // Paginação
    $("#pager").on("click", "a", function (e) {
        e.preventDefault();
        const href = $(this).attr("href");
        const urlParams = new URLSearchParams(href);
        const page = urlParams.get('page');
        const search = urlParams.get('search');

        if (!isNaN(page)) {
            atualizarTabela(search, page);
        }
    });

    // Inicialização do formulário AJAX
    $('#formCad').ajaxForm({
        beforeSubmit: () => {
            // Ações antes de enviar o formulário, se necessário
        },
        success: (responseText, statusText, xhr, $form) => {
            atualizarTabela();
            $('#formCad')[0].reset();
            Swal.fire({
                title: 'Cadastrado!',
                icon: 'success',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
        },
        error: (xhr) => {
            const errorMsg = xhr.responseJSON && xhr.responseJSON.messages
                ? xhr.responseJSON
                : { messages: { error: 'Erro desconhecido.' } };
            exibirMensagem('error', errorMsg);
        }
    });
});

function exibirMensagem(type, error) {
    let errorMessage = '';
    for (const key in error.messages) {
        if (error.messages.hasOwnProperty(key)) {
            errorMessage += `${error.messages[key]}\n`;
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

function listRegioes() {
    $('#selectRegiao').empty().removeAttr('required');
    $.getJSON(`${_baseUrl}api/v1/regioes`, {}, (data) => {
        data.rows.forEach(regiao => {
            $('#selectRegiao').append(`<option value="${regiao.id}">${regiao.id} - ${regiao.nome}</option>`);
        });
        $('#selectGerentes').attr('required data-choices', true);
        new Choices('#selectRegiao');
    });
}

function listGerentes() {
    $('#selectGerentes').empty().removeAttr('required');
    $.getJSON(`${_baseUrl}api/v1/gerentes/list`, {}, (data) => {
        data.forEach(gerente => {
            $('#selectGerentes').append(`<option value="${gerente.id}">${gerente.id} - ${gerente.nome} ${gerente.sobrenome}</option>`);
        });
        $('#selectGerentes').attr('required data-choices', true);
        new Choices('#selectGerentes');
    });
}

function atualizarTabela(search = false, page = 1) {
    $('#tabela-dados').empty();
    $('#cardResult').hide();
    $('.loadResult').show();

    let url = `${_baseUrl}api/v1/supervisores?`;
    if (search) url += `search=${search}&`;
    if (page) url += `page=${page}`;

    $.getJSON(url)
        .done((data) => {
            $("#numResults").html(data.num);
            $("#pager").html(data.pager);
            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show();
            } else {
                $('#cardResult').show();
                $('.noresult').hide();
            }

            data.rows.forEach(row => {
                const randomColor = Math.floor(Math.random() * 16777215).toString(16);
                $('#tabela-dados').append(`
                    <tr>
                        <td>
                            <div class="image-container" style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%;">
                                <img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.nome.charAt(0)}';" style="width: 100%; height: 100%; object-fit: cover;" class="rounded-circle">
                            </div>
                        </td>
                        <td class="align-middle">#${row.id}</td>
                        <td class="align-middle">${row.nome || ''} ${row.sobrenome || ''}</td>
                        <td class="align-middle">${row.gerente_nome || ''} ${row.gerente_sobrenome || ''}</td>
                        <td class="align-middle">${row.regiao_nome || ''}</td>
                        <td class="align-middle">${row.cpf || ''}</td>
                        <td class="align-middle">
                            <a href="mailto:${row.email || ''}"><b>${row.email || ''}</b></a>
                        </td>
                        <td class="align-middle">
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel Celular" href="tel:${row.celular || ''}"><span class="badge bg-dark rounded-pill">${row.celular || ''}</span></a>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Tel fixo" href="tel:${row.telefone || ''}"><span class="badge bg-success rounded-pill">${row.telefone || ''}</span></a>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group" role="group">
                                <a href="#" onclick="recursoindisponivel()" class="btn btn-dark btn-sm">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="#" onclick="recursoindisponivel()" class="btn btn-danger btn-sm sa-warning">
                                    <i class="ri-delete-bin-6-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                `);
            });

            $('.loadResult').hide();
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
        });
}