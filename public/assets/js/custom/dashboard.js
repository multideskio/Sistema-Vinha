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
    statisticas()
    listaTransacoes()
    search()
    listaUsuarios()
    searchUser()

    $("#btnSearchDash").on('click', function () {
        var search = $("#testDate").val();

        if (search) {
            Swal.fire({
                text: 'Atualizando painel...',
                icon: 'info'
            });

            statisticas(search);

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

    element.html(`
        <i class="${iconClass} fs-13 align-middle"></i> ${growthRate}
    `).removeClass('text-danger text-success').addClass(textClass);
}

function statisticas(search = null) {

    var url = `${_baseUrl}api/v1/transacoes/dashboard?`;
    if (search) {
        var dates = search.split(" to ")
        var dateIn = dates[0];
        var dateOut = dates[1];
        url += `dateIn=${dateIn}&dateOut=${dateOut}`
    }

    $.getJSON(url, function (data, textStatus, jqXHR) {
        //console.log(data);

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


function dividirData01(inputString) {
    // Dividir a string usando o texto " to "
    const partes = inputString.split(" to ");

    if (partes.length === 2) {
        const dataInicio = partes[0]; // 2024-06-01
        const dataFim = partes[1];    // 2024-06-30

        return {
            dataInicio: dataInicio,
            dataFim: dataFim
        };
    } else {
        // Caso a string não tenha sido dividida corretamente
        console.error("Formato de entrada inválido.");
        return null;
    }
}


function dividirData(inputString) {
    // Dividir a string usando o texto " to "
    const partes = inputString.split(" to ");

    if (partes.length === 2) {
        const dataInicio = partes[0]; // 2024-06-01
        const dataFim = partes[1];    // 2024-06-30

        return {
            dataInicio: dataInicio,
            dataFim: dataFim
        };
    } else {
        // Caso a string não tenha sido dividida corretamente
        console.error("Formato de entrada inválido.");
        return null;
    }
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

        //console.log(page);

        // Verifica se o parâmetro "page" é um número
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
            let status
            if (row.status === 'Pago') {
                status = '<span class="badge bg-success-subtle text-success">Pago</span>'
            } else if (row.status === 'Cancelado') {
                status = '<span class="badge bg-danger-subtle text-danger">Cancelado</span>'
            } else if (row.status === 'Reembolsado') {
                status = '<span class="badge bg-dark-subtle text-dark">Reembolsado</span>'
            } else {
                status = '<span class="badge bg-warning-subtle text-warning">Aguardando</span>'
            }

            $("#listaTransacoes").append(`<tr>
                                <th scope="row">${row.id}</th>
                                <td>${row.nome}<br><small class="text-muted">${row.email}</small></td>
                                <td>${row.valor}</td>
                                <td>${status}</td>
                                <td>${row.forma_pg}</td>
                                <td>
                                <a href="${row.url}" class="btn btn-sm btn-soft-dark waves-effect waves-light">ver</a>
                                </td>
                            </tr>`);
        });

        $("#tableTransacoes").show();
        $("#loadResult").hide();
    })
}





function searchUser() {

    // Paginação
    $("#pagerUser").on("click", "a", function (e) {
        e.preventDefault();
        var href = $(this).attr("href");
        var urlParams = new URLSearchParams(href);
        var page = urlParams.get('page');
        var search = urlParams.get('search');

        //console.log(page);

        // Verifica se o parâmetro "page" é um número
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
        //console.log(data);
        $.each(data.rows, function (indexInArray, row) {
            $("#listaUsuarios").append(`<tr>
                                    <th scope="row">${row.id}</th>
                                    <td>${row.nome}<br><small class="text-muted">${row.email}</small></td>
                                    <td>${row.tipo}</td>
                                    <td>
                                    <a href="${row.url}" class="btn btn-sm btn-soft-dark waves-effect waves-light">ver</a>
                                    </td>
                                </tr>`);
        });

        $("#tableUsers").show();
        $("#loadResultUsers").hide();

        $("#numResultsUsers").html(data.num);
        $("#pagerUser").html(data.pager);



    })
}