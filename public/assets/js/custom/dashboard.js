var chartPieBasicColors = ['#008FFB', '#212529']; // Defina as cores do gráfico
var options = {
    series: [],
    chart: {
        height: 200,
        type: 'pie',
    },
    labels: ['CRÉDITO', 'PIX'],
    legend: {
        position: 'bottom'
    },
    dataLabels: {
        dropShadow: {
            enabled: false,
        }
    },
    colors: chartPieBasicColors
};
var chart = new ApexCharts(document.querySelector("#simple_pie_chart"), options);
chart.render();

$(document).ready(function () {
    statisticas();
    listaTransacoes();
    search();
    listaUsuarios();
    searchUser();
    searchDate();
    $("#btnSearchDash").on('click', function () {
        var search = $("#dateSearch").val();
        if (search) {
            Swal.fire({
                text: 'Atualizando painel...',
                icon: 'info'
            });
            statisticas(search);
            console.log(search);
        } else {
            Swal.fire({
                text: 'Defina uma data para gerar o relatório...',
                icon: 'error'
            });
        }
    });
});

function applyGrowthRate(elementId, growthRate) {
    const element = $("#" + elementId);
    const iconClass = growthRate.startsWith('-') ? 'ri-arrow-right-down-line' : 'ri-arrow-right-up-line';
    const textClass = growthRate.startsWith('-') ? 'text-danger' : 'text-success';
    element.html(`<i class="${iconClass} fs-13 align-middle"></i> ${growthRate}`).removeClass('text-danger text-success').addClass(textClass);
}

function dividirData(inputString) {
    const partes = inputString.split("até").map(part => part.trim()); // Remove espaços em branco ao redor de cada data
    if (partes.length === 2) {
        const dataInicio = formatarDataParaApi(partes[0]);
        const dataFim = formatarDataParaApi(partes[1]);
        return {
            dataInicio: dataInicio,
            dataFim: dataFim
        };
    } else {
        console.log("Formato de entrada inválido.");
        return null;
    }
}

function formatarDataParaApi(data) {
    const [dia, mes, ano] = data.split("/");
    return `${ano}-${mes}-${dia}`;
}


function statisticas(search = null) {
    var url = `${_baseUrl}api/v1/transacoes/dashboard?`;
    if (search) {
        var dates = dividirData(search);
        if (dates) {
            url += `dateIn=${dates.dataInicio}&dateOut=${dates.dataFim}`;
        }
    }
    $.getJSON(url, function (data, textStatus, jqXHR) {
        // Atualizar valores
        $("#mesDash").html(data.mes.valor);
        $("#dashBoletos").html(data.boletos.valor);
        $("#dashPix").html(data.pix.valor);
        $("#dashCredito").html(data.credito.valor);
        $("#dashDebito").html(data.debito.valor);
        $("#dashAnual").html(data.totalAnual.valor);
        $("#dashTotal").html(data.totalGeral.valor);
        $("#dashUsers").html(data.totalUsers);

        // Atualizar taxas de crescimento
        applyGrowthRate('mesGrowth', data.mes.crescimento);
        applyGrowthRate('boletosGrowth', data.boletos.crescimento);
        applyGrowthRate('pixGrowth', data.pix.crescimento);
        applyGrowthRate('creditoGrowth', data.credito.crescimento);
        applyGrowthRate('debitoGrowth', data.debito.crescimento);
        applyGrowthRate('anualGrowth', data.totalAnual.crescimento);
        applyGrowthRate('totalGrowth', data.totalGeral.crescimento);

        // Extrair os valores e remover o símbolo "R$"
        var creditoValue = parseFloat(data.credito.valor.replace('R$', '').replace(',', '.'));
        var pixValue = parseFloat(data.pix.valor.replace('R$', '').replace(',', '.'));

        // Atualizando a série do gráfico com os novos dados
        var seriesData = [creditoValue, pixValue];
        chart.updateSeries(seriesData);

        if (search) {
            Swal.fire({
                text: 'Atualizado...',
                icon: 'success'
            });
        }
    }).fail(() => {
        Swal.fire({
            text: 'Houve um erro ao atualizar...',
            icon: 'error'
        });
    });
}

function searchDate() {
    flatpickr("#dateSearch", {
        locale: "pt",
        mode: "range", // Ativa o modo de seleção de intervalo
        dateFormat: "d/m/Y", // Define o formato da data como dd/mm/yyyy
        maxDate: "today", // Permite apenas datas até hoje
        minDate: new Date().fp_incr(-365), // Permite selecionar datas até um ano atrás
        defaultDate: [new Date().fp_incr(-30), "today"]
    });
}

function search() {
    // Clique no botão de pesquisa
    $("#inSearchBtn").on('click', function (e) {
        var search = $("#inSearch").val();
        listaTransacoes(search);
    });
    // Pressiona Enter no campo de pesquisa
    $("#inSearch").on('keypress', function (e) {
        if (e.which === 13) {
            var search = $("#inSearch").val();
            listaTransacoes(search);
        }
    });
    // Paginação
    $("#pager").on("click", "a", function (e) {
        e.preventDefault();
        var href = $(this).attr("href");
        var urlParams = new URLSearchParams(href);
        var page = urlParams.get('page');
        var search = urlParams.get('search');
        if (!isNaN(page)) {
            listaTransacoes(search, page);
        }
    });
}

function listaTransacoes(search = false, page = 1) {
    $('#listaTransacoes').empty();
    $("#tableTransacoes").hide();
    $("#loadResult").show();
    var url = `${_baseUrl}api/v1/transacoes/lista?`;
    if (search) {
        url += "search=" + encodeURIComponent(search) + "&";
    }
    if (page) {
        url += "page=" + page;
    }
    $.getJSON(url, function (data, textStatus, jqXHR) {
        $("#numResults").html(data.num);
        $("#pager").html(data.pager);
        $.each(data.rows, function (indexInArray, row) {
            let status;
            if (row.status === 'Pago') {
                status = '<span class="badge bg-success-subtle text-success">Pago</span>';
            } else if (row.status === 'Cancelado') {
                status = '<span class="badge bg-danger-subtle text-danger">Cancelado</span>';
            } else if (row.status === 'Reembolsado') {
                status = '<span class="badge bg-dark-subtle text-dark">Reembolsado</span>';
            } else {
                status = '<span class="badge bg-warning-subtle text-warning">Aguardando</span>';
            }
            $("#listaTransacoes").append(`<tr><th scope="row">${row.id}</th><td>${row.nome}<br><small class="text-muted">${row.email}</small></td><td>${row.valor}</td><td>${status}</td><td>${row.forma_pg}</td><td><a href="${row.url}" class="btn btn-sm btn-soft-dark waves-effect waves-light">ver</a></td></tr>`);
        });
        $("#tableTransacoes").show();
        $("#loadResult").hide();
    });
}

function searchUser() {
    // Paginação
    $("#pagerUser").on("click", "a", function (e) {
        e.preventDefault();
        var href = $(this).attr("href");
        var urlParams = new URLSearchParams(href);
        var page = urlParams.get('page');
        var search = urlParams.get('search');
        if (!isNaN(page)) {
            listaUsuarios(search, page);
        }
    });
}

function listaUsuarios(search = false, page = 1) {
    $('#listaUsuarios').empty();
    $("#tableUsers").hide();
    $("#loadResultUsers").show();
    var url = `${_baseUrl}api/v1/usuarios?`;
    if (search) {
        url += "search=" + encodeURIComponent(search) + "&";
    }
    if (page) {
        url += "page=" + page;
    }
    $.getJSON(url, function (data, textStatus, jqXHR) {
        $.each(data.rows, function (indexInArray, row) {
            $("#listaUsuarios").append(`<tr><th scope="row">${row.id}</th><td>${row.nome}<br><small class="text-muted">${row.email}</small></td><td>${row.tipo}</td><td><a href="${row.url}" class="btn btn-sm btn-soft-dark waves-effect waves-light">ver</a></td></tr>`);
        });
        $("#tableUsers").show();
        $("#loadResultUsers").hide();
        $("#numResultsUsers").html(data.num);
        $("#pagerUser").html(data.pager);
    });
}
