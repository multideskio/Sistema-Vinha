<?= $this->extend('igrejas/template') ?>

<?= $this->section('page') ?>
<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                <h1 class="fw-bolder">PAINEL DE ACOMPANHAMENTO</h1>
            </div>
        </div><!-- end card header -->
    </div>
    <!--end col-->
</div>
<!--end row-->
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-3 col-xl-3">
                <!-- card -->
                <div class="card card-animate card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Desempenho deste mês
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0" id="mesGrowth">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +0 %
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    R$ <span id="mesDash" class="counter-value">0,00</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-dark-subtle rounded fs-3">
                                    <i class="bi bi-speedometer text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xl-3">
                <!-- card -->
                <div class="card card-animate card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pix</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0" id="pixGrowth">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> 0%
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    R$ <span class="counter-value" id="dashPix">0,00</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bi bi-cash text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xl-3">
                <!-- card -->
                <div class="card card-animate card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Crédito</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="fs-14 mb-0" id="creditoGrowth">
                                    +0.00 %
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" id="dashCredito">R$ 0,00</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bi bi-credit-card text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xl-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Boletos</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="fs-14 mb-0" id="anualGrowth">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> 0 %
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    R$ <span class="counter-value" id="dashBoletos">0,00</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bi bi-receipt text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Movimentação anual</h4>
            </div>
            <!-- end card header -->
            <div class="card-body p-0 pb-2">
                <div class="w-100">
                    <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]'
                        data-colors-minimal='["--vz-light", "--vz-primary", "--vz-info"]'
                        data-colors-saas='["--vz-success", "--vz-info", "--vz-danger"]'
                        data-colors-modern='["--vz-warning", "--vz-primary", "--vz-success"]'
                        data-colors-interactive='["--vz-info", "--vz-primary", "--vz-danger"]'
                        data-colors-creative='["--vz-warning", "--vz-primary", "--vz-danger"]'
                        data-colors-corporate='["--vz-light", "--vz-primary", "--vz-secondary"]'
                        data-colors-galaxy='["--vz-secondary", "--vz-primary", "--vz-primary-rgb, 0.50"]'
                        data-colors-classic='["--vz-light", "--vz-primary", "--vz-secondary"]'
                        data-colors-vintage='["--vz-success", "--vz-primary", "--vz-secondary"]' class="apex-charts"
                        dir="ltr"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>



<?= $this->endSection() ?>

<?= $this->section("js") ?>
<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Dashboard init -->
<script src="/assets/js/pages/dashboard-ecommerce.init.js"></script>


<?= $this->endSection() ?>