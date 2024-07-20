$(document).ready(function () {
    statisticas()
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
        console.log(data);

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