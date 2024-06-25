<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>
<h4 class="text-muted mb-0">Gerenciamento de regiões</h4>
<div class="row mt-3 gx-1">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <p>Cadastre uma nova região</p>
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
                <p>Lista de regiões</p>
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
<script src="/assets/js/custom/regioes.min.js"></script>
<?= $this->endSection('js') ?>