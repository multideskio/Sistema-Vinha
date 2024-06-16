<?= $this->extend('admin/template') ?>
<?= $this->section('css'); ?>
<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<!-- Filepond css -->
<link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css" type="text/css" />
<link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
<?= $this->endSection(); ?>
<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Gerenciamento de pastores</p>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#cadastrarPastor">
        <i class="ri-user-settings-line"></i> Cadastrar pastor
    </button>
</div>

<div class="col-12 mt-2">
    <div class="card">
        <div class="card-body">
            <?= $this->include('admin/pages/includes/search.php') ?>
            <div class="table-responsive">
                <div id="cardResult" style="display: none">
                    <div class="alert alert-info" role="alert">
                        <strong>A região é herdada do supervisor</strong>
                    </div>
                    <div class="page-link" id="numResults"></div>
                    <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>id</th>
                                <th>Nome completo</th>
                                <th>Gerente</th>
                                <th>Supervisor</th>
                                <th>Região</th>
                                <th>CPF</th>
                                <th>E-mail</th>
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
                <!--  -->
                <div class="noresult" style="display: none">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                        <p class="text-muted mb-0">Cadastre supervisores...</p>
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

<?= $this->include('admin/pages/pastores/modal.php') ?>

<?= $this->endSection(); ?>
<?= $this->section('js') ?>
<script src="/assets/libs/filepond/filepond.min.js"></script>
<script src="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
<script src="/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
<script src="/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js"></script>
<script src="/assets/js/custom/functions.min.js"></script>
<script src="/assets/js/custom/pastores.min.js"></script>
<?= $this->endSection(); ?>