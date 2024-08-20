<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>

<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">Gerar relatório</h1>
</div>

<div class="input-group">
    <input id="testDate" name="testDate" type="text" class="form-control border-0 shadow" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" placeholder="<?php echo date("Y-m-d"); ?>">
    <button class="input-group-text bg-primary border-primary text-white" id="btnSearchDash">
        <i class="ri-calendar-2-line"></i>
    </button>
</div>


<?= $this->endSection() ?>