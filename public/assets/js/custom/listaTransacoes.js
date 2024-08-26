function searchTransacoes() {
    $("#inSearchBtn").click(function (e) {
        var search = $("#inSearch").val();
        atualizarTabela(search);
        $(".loadResult").hide();
    });

    $("#inSearch").keypress(function (e) {
        $(".loadResult").hide();
        // Verifica se a tecla pressionada é a tecla Enter (código 13)
        if (e.which === 13) {
            var search = $("#inSearch").val();
            atualizarTabela(search);
        }
    });

    $("#pager").on("click", "a", function (e) {
        $(".loadResult").hide();
        e.preventDefault();
        var href = $(this).attr("href");
        var urlParams = new URLSearchParams(href);
        var page = urlParams.get('page');
        var search = urlParams.get('search');

        console.log(page);

        // Verifica se o parâmetro "page" é um número
        if (!isNaN(page)) {
            // Chama a função atualizarTabela com os parâmetros corretos
            atualizarTabela(search, page);
        }
    });
}

function atualizarTabela(search = false, page = 1) {
    $('#tabela-dados').empty(); // Limpa o conteúdo da tabela antes de atualizar
    $('#cardResult').hide();
    $('.loadResult').show();

    // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
    var url = `${_baseUrl}api/v1/transacoes/user/${globalIdLogin}?`;

    if (search) {
        url += "search=" + search + "&";
    }

    if (page) {
        url += "page=" + page;
    }

    $.getJSON(url)
        .done(function (data, textStatus, jqXHR) {
            $("#valorPageView").html(data.currentPageTotal);
            $("#valorTotalView").html(data.allPagesTotal);
            $("#numResults").html(data.num);
            if (data.rows.length === 0) {
                $('#cardResult').hide();
                $('.noresult').show(); // Exibe a mensagem de 'noresult' se não houver dados
            } else {
                $('#cardResult').show();
                $('.noresult').hide(); // Oculta a mensagem de 'noresult' se houver dados
            }
            $.each(data.rows, function (index, row) {
                var status;
                let btn;
                var desc;

                if (row.status == 'Pago') {
                    status = `<span class="badge bg-success">${row.status}</span>`;
                    btn = `<button class="btn btn-danger btn-sm" onclick="reembolsar('${row.id_transacao}', '${row.id}', '${row.valor}')">Reembolsar</button>`;
                } else if (row.status == 'Cancelado') {
                    status = `<span class="badge bg-danger">${row.status}</span>`;
                } else if (row.status == 'Reembolsado') {
                    status = `<span class="badge bg-dark">${row.status}</span>`;
                } else {
                    status = `<span class="badge bg-warning">${row.status}</span>`;
                    btn = `<button class="btn btn-info btn-sm" onclick="sincronizar('${row.id_transacao}')">Sincronizar</button>`
                }


                if (row.desc === 'oferta') {
                    desc = '<span class="badge bg-primary">Oferta</span>';
                } else {
                    desc = '<span class="badge bg-info">Dízimo</span>';
                }

                var newRow = `
                <tr>
                    <td>${row.id}</td>
                    <td>${row.descricao_lg}<br>${desc}</td>
                    <td>${row.data_criado}</td>
                    <td>${row.data_pag ? row.data_pag : ''}</td>
                    <td>${row.valor}</td>
                    <td>${status}</td>
                    <td>${row.forma_pg}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            ${btn}
                        </div>
                    </td>
                </tr>
            `;
                $('#tabela-dados').append(newRow);
            });

            $(".loadResult").hide();
            $("#pager").html(data.pager);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Erro ao carregar os dados:", textStatus, errorThrown);
        });
}

function reembolsar(id, id_transacao, valor) {
    //alert(id)
    $("#staticBackdrop").modal('show');
    var url = `${_baseUrl}api/v1/transacoes/user/reembolso/${id}?`;
    $("#formReembolso").attr('action', url);
    $('#id_transacao').val(id_transacao)
    $('#valor').val(valor)

    $('.formReembolso').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            Swal.fire({
                text: 'Solicitando reembolso...',
                icon: 'info',
            });
        },
        success: function (responseText, statusText, xhr, $form) {
            console.log(responseText.ReasonMessage)
            if (responseText.ReasonMessage === 'Successful') {
                Swal.fire({
                    text: 'Reembolso realizado com sucesso...',
                    icon: 'success'
                })
            } else {
                Swal.fire({
                    html: 'Reembolso negado.<br>Verifique se há saldo em sua conta da CIELO <br> Status: ' + responseText.ReasonMessage,
                    icon: 'error'
                })
            }

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseJSON.messages.error)
            Swal.fire({
                text: 'Erro ao tentar fazer o reembolso, se caso o erro persistir, entre com contato com suporte.',
                icon: 'error'
            })
        }
    });
}

function sincronizar(id_transacao) {
    Swal.fire({
        text: 'Sincronizando...',
        icon: 'info',
    });
    $.getJSON(`${_baseUrl}api/v1/cielo/payment-status/${id_transacao}`,
        function (data, textStatus, jqXHR) {
            console.log(data.statusName)
            if ('Pending' === data.statusName) {
                Swal.fire({
                    html: 'Transação sincronizada<br>status: Pendente',
                    icon: 'warning',
                });
            } else if ('Authorized' === data.statusName) {
                Swal.fire({
                    html: 'Transação sincronizada<br>status: Pago',
                    icon: 'success',
                }).then(() => {
                    atualizarTabela()
                });
            } else if ('PaymentConfirmed' === data.statusName) {
                Swal.fire({
                    html: 'Transação sincronizada<br>status: Pago',
                    icon: 'success',
                }).then(() => {
                    atualizarTabela()
                });
            } else if ('Refunded' === data.statusName) {
                Swal.fire({
                    html: 'Transação sincronizada<br>status: Reembolsado',
                    icon: 'info',
                })
            }
            else {
                Swal.fire({
                    html: 'Transação sincronizada<br>status: ' + data.statusName,
                    icon: 'error',
                });
            }
        }
    );
}

$(document).ready(function () {
    searchTransacoes();
    $('#formReembolso').removeAttr('action');
});