$(document).ready(function () {
	formataCampos();
	atualizarTabela();
	populateSupervisorSelect();

	$("#inSearchBtn").click(function () {
		var search = $("#inSearch").val();
		atualizarTabela(search);
	});

	$("#inSearch").keypress(function (e) {
		if (e.which === 13) {
			var search = $("#inSearch").val();
			atualizarTabela(search);
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

	$('#formCad').ajaxForm({
		beforeSubmit: function () { },
		success: function () {
			atualizarTabela();
			$('#formCad')[0].reset();
			Swal.fire({
				title: 'Cadastrado!',
				icon: 'success',
				confirmButtonClass: 'btn btn-primary w-xs mt-2',
				buttonsStyling: false,
			});
		},
		error: function (xhr) {
			var errorMessage = xhr.responseJSON && xhr.responseJSON.messages ? xhr.responseJSON : { messages: { error: 'Erro desconhecido.' } };
			exibirMensagem('error', errorMessage);
		}
	});
});

function initializeCleave(selector, options) {
	new Cleave(selector, options);
}

function populateSupervisorSelect() {
	$('#selectSupervisor').empty().removeAttr('required');
	$.getJSON(_baseUrl + "api/v1/supervisores/list", function (data) {
		$.each(data, function (index, supervisor) {
			var option = `<option value="${supervisor.id}">${supervisor.id} - ${supervisor.nome} ${supervisor.sobrenome}</option>`;
			$('#selectSupervisor').append(option);
		});
		$('#selectSupervisor').attr('required', true).attr('data-choices', true);
		new Choices('#selectSupervisor');
	});
}

function atualizarTabela(search = '', page = 1) {
	$('.noresult').hide();
	$('#perfilCards').empty();
	$('#cardResult').hide();
	$('.loadResult').show();

	var url = _baseUrl + "api/v1/pastores?" + $.param({ search, page });

	$.getJSON(url)
		.done(function (data) {
			$("#pager").html(data.pager);
			$("#numResults").html(data.num);
			if (data.rows.length === 0) {
				$('#cardResult').hide();
				$('.noresult').show();
			} else {
				$('#cardResult').show();
				$('.noresult').hide();
				$.each(data.rows, function (index, row) {
					var randomColor = Math.floor(Math.random() * 16777215).toString(16);

					$('#perfilCards').append(`
<div class="col-xl-3">
<div class="card shadow-sm h-100">
<div class="card-body text-center">
<div class="mx-auto avatar-md mb-3">
<img src="${row.foto}" onerror="this.onerror=null; this.src='https://placehold.co/50/${randomColor}/FFF?text=${row.nome.charAt(0)}';" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
</div>
<h5 class="card-title mb-1">#${row.id} - ${row.nome} ${row.sobrenome}</h5>
<hr>
<div class="text-start">
<p class="text-muted mb-2"><strong>Gerente:</strong> ${row.nome_gerente} ${row.sobre_gerente}</p>
<p class="text-muted mb-2"><strong>Supervisor:</strong> ${row.nome_supervisor} ${row.sobre_supervisor}</p>
<p class="text-muted mb-2"><strong>Região:</strong> ${row.regiao}</p>
<p class="text-muted mb-2"><strong>CPF:</strong> ${row.cpf}</p>
<p class="text-muted mb-2"><strong>Email:</strong> ${row.email}</p>
<p class="text-muted mb-2"><strong>Celular:</strong> ${row.celular}</p>
<p class="text-muted mb-0"><strong>Telefone:</strong> ${row.telefone}</p>
</div>
</div>
<a href="${_baseUrl}admin/pastor/${row.id}" class="btn text-white bg-primary card-footer">
<i class="ri-pencil-line"></i> EDITAR
</a>
</div>
</div>`)
				});
			}
			$('.loadResult').hide();
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.error("Erro ao carregar os dados:", textStatus, errorThrown);
		});
}

function formataCampos() {
	// Inicialização de formatação de inputs com Cleave.js
	initializeCleave('.cpf', {
		numericOnly: true,
		delimiters: ['.', '.', '-'],
		blocks: [3, 3, 3, 2],
		uppercase: true
	});

	initializeCleave('.cep', {
		numericOnly: true,
		delimiters: ['-'],
		blocks: [5, 3],
		uppercase: true
	});

	initializeCleave('.telFixo', {
		numericOnly: true,
		delimiters: ['(', ') ', '-'],
		blocks: [0, 2, 4, 4]
	});

	initializeCleave('.celular', {
		numericOnly: true,
		delimiters: ['+', ' (', ') ', ' ', '-'],
		blocks: [0, 2, 2, 1, 4, 4]
	});
}

// Função para exibir mensagens
function exibirMensagem(type, error) {
	let messages = error.messages;
	let errorMessage = '';

	for (let key in messages) {
		if (messages.hasOwnProperty(key)) {
			errorMessage += `${messages[key]}\n`;
		}
	}

	Swal.fire({
		title: type === 'error' ? "Erro ao incluir registro" : "Mensagem",
		text: errorMessage,
		icon: type,
		confirmButtonClass: "btn btn-primary w-xs mt-2",
		buttonsStyling: false,
	});
}
