<?= $this->extend('admin/template') ?>

<?= $this->section('page') ?>
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h1 class="fw-bolder">PAINEL DE ACOMPANHAMENTO</h1>
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <div class="row g-3 mb-0 align-items-center">
                                <div class="col-sm-auto">
                                    <div class="input-group">
                                        <input id="dateSearch" name="dateSearch" type="text"
                                            class="form-control border-0 shadow">
                                        <button class="input-group-text bg-primary border-primary text-white"
                                            id="btnSearchDash">
                                            <i class="ri-calendar-2-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="row">
            <div class="col-md-4 col-xl-4">
                <!-- card -->
                <div class="card card-animate card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Esse mês</p>
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
                                    <span id="mesDash" class="counter-value">R$ 0,00</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-dark-subtle rounded fs-3">
                                    <i class="bx bx-dollar-circle text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-4">
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
                                    <span class="counter-value" id="dashPix">0</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bx bx-dollar-circle text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-4">
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
                                    <i class="bx bx-credit-card-alt text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xl-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total anual</p>
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
                                    <span class="counter-value" id="dashAnual">R$ 0,00</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bx bx-dollar-circle text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-4">
                <!-- card -->
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total geral</p>
                            </div>
                            <div class="flex-shrink-0">

                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" id="dashTotal"></span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bx bx-dollar-circle text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xl-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Usuários</p>
                            </div>
                            <div class="flex-shrink-0">

                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" id="dashUsers"></span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="bx bx-group text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Gráfico geral</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Download Report</a>
                            <a class="dropdown-item" href="#">Export</a>
                            <a class="dropdown-item" href="#">Import</a>
                        </div>
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">
                <div id="simple_pie_chart"
                    data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]'
                    class="apex-charts" dir="ltr"></div>
            </div>
        </div> <!-- .card-->
    </div>
</div>

<div class="row">
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <span>Últimos cadastros</span>
            </div>
            <div class="card-body">
                <div class="page-link" id="numResultsUsers"></div>
                <div id="tableUsers" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap" style="max-height: 50px !important;">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="listaUsuarios">
                            </tbody>
                        </table>
                    </div>
                    <div id="pagerUser" class="mt-2"></div>
                </div>
                <div class="noresult" style="display: none">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                    </div>
                </div>
                <div id="loadResultUsers">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-2">Buscando registros</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <span>Últimas transações</span>
            </div>
            <div class="card-body">
                <div class="page-link" id="numResults"></div>
                <div id="tableTransacoes" style="display: none;">
                    <div class="table-responsive">
                        <!-- Small Tables -->
                        <table class="table table-sm table-nowrap" style="max-height: 50px !important;">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="listaTransacoes">

                            </tbody>
                        </table>
                    </div>
                    <div id="pager" class="mt-2"></div>
                </div>
                <div class="noresult" style="display: none">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                    </div>
                </div>
                <div id="loadResult">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-2">Buscando registros</h5>
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
<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="/assets/js/custom/dashboard.min.js?v=1.0.0"></script>
<!-- Dashboard init -->
<script src="/assets/js/pages/dashboard-ecommerce.init.js"></script>
<?= $this->endSection() ?>