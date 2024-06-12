$(document).ready(function() {
    atualizarTabela();
});

function atualizarTabela() {
    $.getJSON(_baseUrl + "/api/v1/gateways/cielo")
        .done(function(data) {
            const setCheckboxState = (selector, state) => {
                $(selector).prop('checked', state);
            };

            $("#desenvolvimento").toggle(data.status != 1);
            $("#producao").toggle(data.status == 1);

            setCheckboxState("#activePix", data.active_pix == 1);
            setCheckboxState("#activeCredito", data.active_credito == 1);
            setCheckboxState("#activeDebito", data.active_debito == 1);
            setCheckboxState("#activeBoletos", data.active_boletos == 1);

            $("#merchantid_pro").val(data.merchantid_pro);
            $("#merchantkey_pro").val(data.merchantkey_pro);
            $("#merchantid_dev").val(data.merchantid_dev);
            $("#merchantkey_dev").val(data.merchantkey_dev);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar dados: ", textStatus, errorThrown);
            // Aqui você pode adicionar código para notificar o usuário sobre o erro, se necessário.
        });
}