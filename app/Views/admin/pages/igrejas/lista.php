<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>
<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder float-start">Gerenciamento de igrejas</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success float-end mb-2" data-bs-toggle="modal" data-bs-target="#cadastrarIgreja">
        <i class="ri-user-settings-line"></i> Cadastrar igreja
    </button>
</div>
<?= $this->include('admin/pages/includes/search.php') ?>
<div style="display: none" id="cardResult">
    <div class="page-link" id="numResults"></div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="perfilCards">
    </div>
    <div id="pager" class="mt-2"></div>
</div>
<div class="noresult" style="display: none">
    <div class="text-center">
        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
    </div>
</div>
<div class="loadResult">
    <div class="text-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="mt-2">Buscando registros</h5>
    </div>
</div>

<?= $this->include('admin/pages/igrejas/modal.php') ?>
<?= $this->endSection(); ?>
<?= $this->section('js') ?>
<script src="/assets/js/custom/functions.min.js"></script>
<script src="/assets/js/custom/igrejas.js"></script>
<?= $this->endSection(); ?>