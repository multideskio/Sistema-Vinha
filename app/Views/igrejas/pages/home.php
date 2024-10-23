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
            <div class="col-md-6 col-xl-3">
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
                                    <!-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +0 % -->
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-1 fw-semibold ff-secondary mb-4">
                                    <span id="mesDash" class="counter-value">R$ 0,00</span>
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
            <div class="col-md-6 col-xl-3">
                <!-- card -->
                <div class="card card-animate card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pix</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0" id="pixGrowth">
                                    <!-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> 0% -->
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-1 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" id="dashPix">R$ 0,00</span>
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
            <div class="col-md-6 col-xl-3">
                <!-- card -->
                <div class="card card-animate card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Crédito</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="fs-14 mb-0" id="creditoGrowth">
                                    <!-- +0.00 % -->
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-1 fw-semibold ff-secondary mb-4">
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
            <div class="col-md-6 col-xl-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Boletos</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="fs-1 mb-0" id="anualGrowth">
                                    <!-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> 0 % -->
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-1 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" id="dashBoletos">R$ 0,00</span>
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
                    <div id="anual"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>



<?= $this->endSection() ?>

<?= $this->section("js") ?>
<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<script>
function formatCurrency(value) {
    return value.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

function statusDashboard() {
    $.getJSON(`${_baseUrl}/igreja/api/v1/dashboard`, null, function(data, textStatus, jqXHR) {
        // Formata e insere os valores no HTML
        $("#dashBoletos").text(formatCurrency(data.boleto));
        $("#dashPix").text(formatCurrency(data.pix));
        $("#dashCredito").text(formatCurrency(data.credito));
        $("#mesDash").text(formatCurrency(data.total));
    });
}


statusDashboard()

function appChart() {
    // Faz a requisição para a API
    $.getJSON(`${_baseUrl}/igreja/api/v1/grafico`, null, function(data, textStatus, jqXHR) {
        // Extraí os valores do retorno da API
        const valores = data.map(item => item.valor); // Extraí os valores de cada mês
        const meses = data.map(item => item.mes); // Extraí os nomes dos meses

        // Atualiza o gráfico com os dados da API
        chart.updateSeries([{
            name: 'Pago',
            type: 'column',
            data: valores // Atualiza os dados da série com os valores da API
        }]);

        // Atualiza os labels (meses) se necessário
        chart.updateOptions({
            xaxis: {
                categories: meses
            }
        });
    });
}

// Configurações do gráfico
var options = {
    chart: {
        type: 'line', // Gráfico do tipo linha para "Pedidos" e "Reembolsos"
        height: 350
    },
    series: [{
        name: 'Pago',
        type: 'column', // Inicia com barras
        data: [] // Os dados serão preenchidos dinamicamente pela API
    }],
    stroke: {
        width: [4, 0, 4] // Largura das linhas e barras
    },
    plotOptions: {
        bar: {
            columnWidth: '40%', // Largura das colunas
            endingShape: 'flat'
        }
    },
    xaxis: {
        categories: [], // Os meses serão preenchidos dinamicamente pela API
        title: {
            text: 'Meses'
        }
    },
    yaxis: [{
        title: {
            text: 'Pedidos e Reembolsos'
        },
        min: 0
    }],
    tooltip: {
        shared: true,
        intersect: false
    },
    legend: {
        position: 'bottom',
        labels: {
            colors: ['#000']
        }
    }
}

// Inicializa o gráfico
var chart = new ApexCharts(document.querySelector("#anual"), options);
chart.render();

// Chama a função que faz a requisição e atualiza o gráfico
appChart();
</script>



<?= $this->endSection() ?>