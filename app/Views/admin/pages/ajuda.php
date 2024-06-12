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
<!-- inserir linha -->
<script src="/assets/js/custom/helper.min.js"></script>
<?= $this->endSection(); ?>