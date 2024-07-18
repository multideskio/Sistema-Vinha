$(document).ready(function () {
    listTransacoes();
    atualizarTabela();

    $('#valor').maskMoney({
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: true
    });

    $('#formReembolso').removeAttr('action');

    // Formatação de inputs com Cleave.js
    var cleaveCpf = new Cleave('.cpf', {
        numericOnly: true,
        delimiters: ['.', '.', '-'],
        blocks: [3, 3, 3, 2],
        uppercase: true
    });

    var cleaveCep = new Cleave('.cep', {
        numericOnly: true,
        delimiters: ['-'],
        blocks: [5, 3],
        uppercase: true
    });

    var cleaveTelFixo = new Cleave('.telFixo', {
        numericOnly: true,
        delimiters: ['(', ') ', '-'],
        blocks: [0, 2, 4, 4]
    });

    var cleaveCelular = new Cleave('.celular', {
        numericOnly: true,
        delimiters: ['+', ' (', ') ', ' ', '-'],
        blocks: [0, 2, 2, 1, 4, 4]
    });

    searchUpdate(_idSearch)

    $(".enviaLinks").on('change', function () {
        $('.formTexts').submit();
    });

    $('.formTexts').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function (responseText, statusText, xhr, $form) {
            $(".alertAlterado").show(),
                setTimeout(() => {
                    $(".alertAlterado").fadeOut()
                }, 1200);
        },
        error: function (xhr, status, error) {
            console.log(xhr)
            console.log(status)
            console.log(error)
        }
    });

    $('.formGeral').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function (responseText, statusText, xhr, $form) {
            Swal.fire({
                title: 'OK!',
                text: 'Atualizado com sucesso!',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: 'Erro ao atualizar...',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
        }
    });

    $("#profile-img-file-input").on('change', function () {
        $('.formUpload').submit();
    });

    $('.formUpload').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            console.log('Enviando...')
        },
        success: function (responseText, statusText, xhr, $form) {
            Swal.fire({
                title: 'OK!',
                text: 'Imagem atualizada com sucesso!',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: 'Erro ao atualizar imagem',
                icon: 'error',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
            console.log(xhr)
            console.log(status)
            console.log(error)
        }
    });




});



function searchUpdate(id) {
    if (id) {
        // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
        var url = _baseUrl + `api/v1/pastores/${id}`;
        $.getJSON(url)
            .done(function (data, textStatus, jqXHR) {
                if (data.foto) {
                    $("#fotoPerfil").attr('src', data.foto);
                }

                $("#viewNameUser").html(data.nome);
                $("#facebook").val(data.facebook);
                $("#website").val(data.website);
                $("#instagram").val(data.instagram);
                $("#nome").val(data.nome);
                $("#sobrenome").val(data.sobrenome);
                $("#cpf").val(data.cpf);
                $("#cel").val(data.celular);
                $("#email").val(data.email);
                $("#tel").val(data.telefone);
                $("#cep").val(data.cep);
                $("#uf").val(data.uf);
                $("#cidade").val(data.cidade);
                $("#bairro").val(data.bairro);
                $("#complemento").val(data.complemento);
                $("#dizimo").val(data.data_dizimo);

                //$("#gerente").val(data.gerente);
                //$("#regiao").val(data.regiao);

                populateSupervisorSelect(data.idSupervisor)
                /*listGerentes(data.idGerente)*/

            }).fail(function (jqXHR, textStatus, errorThrown) {

                $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');

                console.error("Erro ao carregar os dados:", textStatus, errorThrown);

                $('.loadResult').hide();

                Swal.fire({
                    title: 'Os dados não foram enconrados',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                    buttonsStyling: false,
                }).then(function (result) {
                    history.back();
                });;


            });

        // Tratamento de erro para a imagem
        $('#fotoPerfil').on('error', function () {
            $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
        });
    }
}


function populateSupervisorSelect(idAtual) {
    $('#selectSupervisor').empty().removeAttr('required');

    $.getJSON(_baseUrl + "api/v1/supervisores/list", function (data) {

        data.forEach(function (supervisor) {
            if (idAtual === supervisor.id) {
                var option = `<option selected value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
            } else {
                var option = `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
            }

            $('#selectSupervisor').append(option);
        });

        //console.log('Id atual '+ idAtual)
        //console.log('IDs '+ supervisor.id)
        // Adiciona os atributos e inicializa o plugin Choices após adicionar todas as opções
        $('#selectSupervisor').attr('required', true).attr('data-choices', true);

        new Choices('#selectSupervisor');

    }).fail(function () {
        Swal.fire({
            title: 'Cadastre supervisores antes...',
            icon: 'error',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
        }).then(function (result) {
            history.back();
        });
    });
}


function listTransacoes() {
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
    var url = `${_baseUrl}api/v1/transacoes/user/${_idSearch}?`;

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
                var btn;
                if (row.status == 'Pago') {
                    status = `<span class="badge bg-success">${row.status}</span>`;
                    btn = `<button class="btn btn-warning btn-sm" onclick="reembolsar('${row.id_transacao}', '${row.id}', '${row.valor}')">Reembolsar</button> <button class="btn btn-info btn-sm" onclick="sincronizar('${row.id_transacao}')">Sincronizar</button>`;
                } else if (row.status == 'Cancelado') {
                    status = `<span class="badge bg-danger">${row.status}</span>`;
                    btn = `<button class="btn btn-info btn-sm" onclick="sincronizar('${row.id_transacao}')">Sincronizar</button>`;
                } else if (row.status == 'Reembolsado') {
                    status = `<span class="badge bg-dark">${row.status}</span>`;
                    btn = `<button class="btn btn-info btn-sm" onclick="sincronizar('${row.id_transacao}')">Sincronizar</button>`;
                } else {
                    status = `<span class="badge bg-warning">${row.status}</span>`;
                    btn = `<button class="btn btn-info btn-sm" onclick="sincronizar('${row.id_transacao}')">Sincronizar</button>`
                }

                var newRow = `
                <tr>
                    <td>${row.id}</td>
                    <td>${row.descricao_lg ? row.descricao_lg : row.desc}</td>
                    <td>${row.data_criado}</td>
                    <td>${row.data_pag ? row.data_pag : ''}</td>
                    <td>${row.valor}</td>
                    <td>${status}</td>
                    <td>${row.forma_pg}</td>
                    <td>
                        ${btn}
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
            Swal.fire({
                text: 'Reembolso realizado com sucesso...',
                icon: 'success'
            })
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


function sincronizar(id) {
    Swal.fire({
        text: 'Sincronizando...',
        icon: 'info',
    });
    $.getJSON(`${_baseUrl}api/v1/cielo/payment-status/${id}`,
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
            }else if('Refunded' === data.statusName){
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