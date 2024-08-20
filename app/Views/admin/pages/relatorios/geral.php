<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>

<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">Gerar relatório</h1>
</div>

<div class="input-group">
    <input id="dateSearch" type="text" class="form-control border-0 shadow">
    <button class="input-group-text bg-primary border-primary text-white" id="btnSearchDash">
        <i class="ri-calendar-2-line"></i>
    </button>
</div>


<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#dateSearch", {
            locale: "pt",
            mode: "range", // Ativa o modo de seleção de intervalo
            dateFormat: "d/m/Y", // Define o formato da data
            maxDate: "today", // Permite apenas datas até hoje
            minDate: new Date().fp_incr(-365),
            defaultDate: [new Date().fp_incr(-30), "today"]
        });
    });
</script>
<?= $this->endSection() ?>
