<?= $this->extend('admin/template') ?>

<?= $this->section('css'); ?>


<?= $this->endSection(); ?>

<?= $this->section('page') ?>

<p class="text-muted mb-0">Gerenciamento de regiões</p>
<div class="row mt-3 gx-1">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h3>Cadastre uma nova região</h3>
                <?= form_open('api/v1/regioes', ["id" => "formCad"]) ?>
                <input type="text" name="regiao" class="form-control mb-3" placeholder="Ex: Centro-Oeste" required minlength="3" maxlength="60" autocomplete="off">
                <input type="hidden" name="id_adm" value="<?= session('data')['idAdm'] ?>">
                <input type="hidden" name="id_user" value="<?= session('data')['id'] ?>">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <h3>Lista de regiões</h3>
                <?= $this->include('admin/pages/includes/search.php') ?>
                <div class="table-responsive">
                    <div style="display: none" id="cardResult">
                        <table id="datatable" class="table nowrap dt-responsive align-middle table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Região</th>
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
                            <p class="text-muted mb-0">Cadastre regiões...</p>
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
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="updateRegiao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateRegiaoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateRegiaoLabel">Anterando região <span class="regiaoUpdate font-bold text-danger"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('api/v1/regioes', ["id" => "formUpdate"]) ?>
            <div class="modal-body">

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <b>Só é recomendado alterações em caso de nome incorreto. Essa alteração está sendo relacionada ao seu usuário.</b>
                </div>
                <div class="mb-2">
                    <label for="regiaoUpdate">Alterando nome da região</label>
                    <input type="text" class="form-control" placeholder="Nome da região" id="regiaoUpdate" name="regiaoUpdate" maxlength="60" autocomplete="off">
                </div>
                <div class="mb-2">
                    <label for="descUpdate">Descrição</label>
                    <input type="text" class="form-control" placeholder="Uma breve descrição" id="descUpdate" name="descUpdate" maxlength="60" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Alterar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection('page') ?>
<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        atualizarTabela();
    });

    $(document).ready(function() {
        // Configuração do plugin jQuery Form
        $('#formUpdate').ajaxForm({
            type: 'PUT',
            beforeSubmit: function(formData, jqForm, options) {
                // Executar ações antes de enviar o formulário (se necessário)
            },
            success: function(responseText, statusText, xhr, $form) {
                //$('#updateRegiao').modal('hide');
                atualizarTabela();
                // Limpar o formulário
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
</script>
<script>
    $("#inSearchBtn").click(function(e) {
        var search = $("#inSearch").val();
        atualizarTabela(search);
    });

    $("#inSearch").keypress(function(e) {
        // Verifica se a tecla pressionada é a tecla Enter (código 13)
        if (e.which === 13) {
            var search = $("#inSearch").val();
            atualizarTabela(search);
        }
    });

    $("#pager").on("click", "a", function(e) {
        e.preventDefault();
        var page = $(this).attr("href").split("=")[1];
        var searchParams = new URLSearchParams(window.location.search);
        var search = searchParams.get('search');

        // Verifica se o parâmetro "page" é um número
        if (!isNaN(page)) {
            // Chama a função atualizarTabela com os parâmetros corretos
            atualizarTabela(search, page);
        }
    });

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
            .done(function(data, textStatus, jqXHR) {
                $("#pager").html(data.pager);
                if (data.rows.length === 0) {
                    $('#cardResult').hide();
                    $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
                } else {
                    $('#cardResult').show();
                    $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
                }
                // Itera sobre os dados recebidos e adiciona as linhas à tabela
                $.each(data.rows, function(index, regiao) {
                    var newRow = `
                        <tr>
                            <td>#${regiao.id}</td>
                            <td>${regiao.nome}<br>${regiao.descricao}</td>
                            <td>
                            <div class="btn-group" role="group">
                                <a href="#" class="btn btn-dark btn-sm" onclick="update('${regiao.id}', '${regiao.nome}', '${regiao.descricao}')">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm sa-warning" onclick="excluir('${regiao.id}', 'regioes')">
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
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Erro ao carregar os dados:", textStatus, errorThrown);
            });

    }
</script>

<?= $this->endSection('js') ?>