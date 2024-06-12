<?= $this->extend('admin/template') ?>
<?= $this->section('css'); ?>

<!-- quill css -->
<link href="/assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
<!-- bubble css for bubble editor-->
<link href="/assets/libs/quill/quill.bubble.css" rel="stylesheet" type="text/css" />
<!-- snow css for snow editor-->
<link href="/assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />



<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<?= $this->endSection(); ?>
<?= $this->section('page') ?>
<div class="clearfix mb-1">
    <p class="text-muted float-start">Genrenciamento do blog de ajuda</p>
    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#cadastrarAjuda">
        <i class="ri-question-line"></i> Cadastrar tópico
    </button>
</div>


<div class="col-12 mt-2">
    <div class="card">
        <div class="card-body">
            <?= $this->include('admin/pages/includes/search.php') ?>
            <h3>Tópicos cadastrados</h3>
            <div class="table-responsive">
                <div style="display: none;" id="cardResult">
                    <div class="page-link" id="numResults"></div>
                    <table class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Titulo</th>
                                <th>Tags</th>
                                <th>Conteúdo limitado</th>
                                <th>Criado em:</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-dados">
                        </tbody>
                    </table>
                    <div id="pager">
                    </div>
                </div>

                <div class="noresult" style="display: none">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                        <p class="text-muted mb-0">Cadastre conteúdos de ajuda...</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="loadResult">
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mt-2">Carregando registros</h5>
        </div>
    </div>
</div>




<?= $this->include('admin/pages/ajuda/modal.php') ?>



<?= $this->endSection(); ?>




<?= $this->section('js') ?>

<!-- quill js -->
<script src="/assets/libs/quill/quill.min.js"></script>

<!-- init js -->


<!-- inserir linha -->
<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });


    // Adicione um evento de clique ao botão de envio
    $('#submitBtn').on('click', function(event) {
        // Impedir o comportamento padrão do botão (envio do formulário)
        event.preventDefault();

        // Copiar o conteúdo do editor Quill para o campo oculto
        var htmlContent = quill.root.innerHTML;
        $('#conteudo').val(htmlContent);

        // Submeta o formulário via AJAX
        $('#formHelper').ajaxSubmit({
            success: function(responseText, statusText, xhr, $form) {
                atualizarTabela();
                // Limpar o formulário
                $('#formHelper')[0].reset();
                // Exibir mensagem de sucesso
                quill.setContents([]);
                //exibirMensagem('success', 'Sucesso: ' + responseText);
                Swal.fire({
                    title: 'Cadastrado!',
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
    });
</script>

<script>
    $(document).ready(function() {
        atualizarTabela();
    });

    function atualizarTabela(search = false, page = 1) {
        $('#tabela-dados').empty(); // Limpa o conteúdo da tabela antes de atualizar

        $('#cardResult').hide();
        $('.loadResult').show();

        // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
        var url = _baseUrl + "api/v1/ajuda?";
        if (search) {
            url += "search=" + search + "&";
        }
        if (page) {
            url += "page=" + page;
        }

        $.getJSON(url).done(function(data, textStatus, jqXHR) {
            $("#numResults").html(data.num);
            $("#pager").html(data.pager);

            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
            } else {
                $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
                $('#cardResult').show();

                // Itera sobre os dados recebidos e adiciona as linhas à tabela
                $.each(data.rows, function(index, row) {
                    var newRow = `
                <tr>
                    <td>${row.id}</td>
                    <td>${row.titulo}</td>
                    <td>${row.tags}</td>
                    <td>${row.conteudo}</td>
                    <td style="white-space: nowrap;">${row.data}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="${row.slug}" class="btn btn-dark btn-sm sa-dark" target="_blank" title="Ver conteúdo">
                                <i class="ri-links-line"></i>
                            </a>
                            <a href="#" onclick="deletarPost('${row.id}')" class="btn btn-danger btn-sm sa-warning" title="Excluir conteúdo">
                                <i class="ri-delete-bin-6-line"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                `;
                    $('#tabela-dados').append(newRow);
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
        }).always(function() {
            $('.loadResult').hide();
            // Certifique-se de que .loadResult seja ocultado após a conclusão da requisição, seja ela bem-sucedida ou com falha
        });
    }


    function deletarPost(id) {
        // Exibe uma mensagem de confirmação ao usuário antes de excluir o registro
        Swal.fire({
            title: "Tem certeza?",
            text: "Você não poderá reverter isso!",
            type: "warning",
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
                    url: `${_baseUrl}api/v1/ajuda/${id}`,
                    dataType: "JSON",
                }).done(() => {
                    // Exibe uma mensagem de sucesso após a exclusão e atualiza a tabela
                    Swal.fire({
                        title: 'Excluído!',
                        text: 'O registro foi excluído com sucesso.',
                        type: 'success',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false,
                    });

                    atualizarTabela()
                }).fail(() => {
                    // Exibe uma mensagem de erro em caso de falha na requisição AJAX
                    Swal.fire({
                        title: "Erro ao excluir",
                        text: "Ocorreu um erro ao tentar excluir o registro.",
                        type: "error",
                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                        buttonsStyling: false,
                    });
                });
            }
        });
    }
</script>
<?= $this->endSection(); ?>