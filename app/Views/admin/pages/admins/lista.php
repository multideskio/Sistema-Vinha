<?= $this->extend('admin/template') ?>
<?= $this->section('css'); ?>
<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>
<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Gerenciamento de administradores</p>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#cadastrarAdmin">
        <i class="ri-user-settings-line"></i> Cadastrar administrador
    </button>
</div>


<div class="col-12 mt-2">
    <div class="card">
        <div class="card-body">
            <?= $this->include('admin/pages/includes/search.php') ?>
            <div class="table-responsive">
                <div style="display: none" id="cardResult">
                    <div class="page-link" id="numResults"></div>
                    <table class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>id</th>
                                <th>Nome completo</th>
                                <th>CPF</th>
                                <th>Email</th>
                                <th>Telefones</th>
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
                        <p class="text-muted mb-0">Cadastre administradores...</p>
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


<?= $this->include('admin/pages/admins/modal.php') ?>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script src="/assets/js/custom/functions.min.js"></script>
<script src="/assets/js/custom/admins.js"></script>

<!-- REPETE -->
<?= $this->endSection('js') ?>