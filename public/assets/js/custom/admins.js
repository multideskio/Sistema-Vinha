$(document).ready(function () {
    initialize();

    $('#cadastrarAdmin').on('shown.bs.modal', function () {
        $('#nome').trigger('focus');
    });

    function initialize() {
        setupInputMasks();
        setupSearchHandlers();
        setupFormSubmission();
        atualizarTabela();
    }

    function setupInputMasks() {
        var maskConfigs = [
            { selector: '.cpf', mask: '000.000.000-00' },
            { selector: '.cep', mask: '00000-000' },
            { selector: '.telFixo', mask: '(00) 0000-0000' },
            { selector: '.celular', mask: '+00 (00) 0 0000-0000' },
            { selector: '.cnpj', mask: '00.000.000/0000-00' }
        ];

        maskConfigs.forEach(function (config) {
            $(config.selector).mask(config.mask);
        });
    }

    function setupSearchHandlers() {
        $("#inSearchBtn").on('click', function () {
            handleSearch();
        });

        $("#inSearch").on('keypress', function (e) {
            if (e.which === 13) {
                handleSearch();
            }
        });

        $("#pager").on("click", "a", function (e) {
            e.preventDefault();
            var href = $(this).attr("href");
            var urlParams = new URLSearchParams(href);
            var page = urlParams.get('page');
            var search = urlParams.get('search');

            if (!isNaN(page)) {
                atualizarTabela(search, page);
            }
        });
    }

    function handleSearch() {
        var search = $("#inSearch").val();
        atualizarTabela(search);
    }

    function setupFormSubmission() {
        $('#form').ajaxForm({
            beforeSubmit: function () {
                showAlert('info', 'Enviando dados!');
            },
            success: function () {
                atualizarTabela();
                $('#form')[0].reset();
                showAlert('success', 'Cadastrado!');
            },
            error: function (xhr) {
                var errorMessage = (xhr.responseJSON && xhr.responseJSON.messages && xhr.responseJSON.messages.error) || 'Erro desconhecido...';
                showAlert('error', errorMessage);
            }
        });
    }

    function showAlert(icon, text) {
        Swal.fire({
            text: text,
            icon: icon
        });
    }

    function atualizarTabela(search, page) {
        if (typeof search === 'undefined') { search = ''; }
        if (typeof page === 'undefined') { page = 1; }

        $('.noresult').hide();
        $('#perfilCards').empty();
        $('#cardResult').hide();
        $('.loadResult').show();

        var url = buildUrl(search, page);

        $.getJSON(url)
            .done(renderTable)
            .fail(handleError)
            .always(function () {
                $('.loadResult').hide();
            });
    }

    function buildUrl(search, page) {
        var url = _baseUrl + "api/v1/administradores?";
        if (search) url += "search=" + encodeURIComponent(search) + "&";
        if (page) url += "page=" + page;
        return url;
    }

    function renderTable(data) {
        $("#numResults").html(data.num);
        $("#pager").html(data.pager);

        if (data.rows.length === 0) {
            $('#cardResult').hide();
            $('.noresult').show();
        } else {
            $('#cardResult').show();
            var cards = data.rows.map(createCard);
            $('#perfilCards').append(cards.join(''));
        }
    }

    function createCard(row) {
        var randomColor = Math.floor(Math.random() * 16777215).toString(16);
        return '<div class="col-xl-3">' +
            '<div class="card shadow-sm h-100">' +
            '<div class="card-body text-center">' +
            '<div class="mx-auto avatar-md mb-3">' +
            '<img src="' + row.foto + '" onerror="this.onerror=null; this.src=\'https://placehold.co/50/' + randomColor + '/FFF?text=' + row.nome.charAt(0) + '\';" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">' +
            '</div>' +
            '<h5 class="card-title mb-1">#' + row.id + ' - ' + row.nome + ' ' + row.sobrenome + '</h5>' +
            '<hr>' +
            '<div class="text-start">' +
            '<p class="text-muted mb-2"><strong>CPF:</strong> ' + row.cpf + '</p>' +
            '<p class="text-muted mb-2"><strong>Email:</strong> ' + row.email + '</p>' +
            '<p class="text-muted mb-2"><strong>Celular:</strong> ' + row.celular + '</p>' +
            '<p class="text-muted mb-0"><strong>Telefone:</strong> ' + row.telefone + '</p>' +
            '</div>' +
            '</div>' +
            '<a href="' + _baseUrl + 'admin/admin/' + row.id + '" class="btn text-white bg-primary card-footer">' +
            '<i class="bi bi-pencil-square"></i> EDITAR' +
            '</a>' +
            '</div>' +
            '</div>';
    }

    function handleError(jqXHR, textStatus, errorThrown) {
        console.error("Erro ao carregar os dados:", textStatus, errorThrown);
        showAlert('error', 'Erro ao carregar os dados.');
    }
});
