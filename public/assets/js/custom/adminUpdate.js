function searchUpdate(id) {
    if (id) {
        // Monta a URL da requisição AJAX com os parâmetros search e page, se estiverem definidos
        var url = _baseUrl + `api/v1/administradores/${id}`;
        $.getJSON(url)
            .done(function(data, textStatus, jqXHR) {
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
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $("#fotoPerfil").attr('src', 'https://placehold.co/50/00000/FFF?text=V');
                //console.error("Erro ao carregar os dados:", textStatus, errorThrown);
                Swal.fire({
                    title: 'Os dados não foram encontrados',
                    icon: 'error'
                }).then(function(result) {
                    history.back();
                });
                $('.loadResult').hide();
            });
        // Tratamento de erro para a imagem
        $('#fotoPerfil').on('error', function() {
            $(this).attr('src', 'https://placehold.co/50/00000/FFF?text=V');
        });
    }
}

function updateTexts(id) {
    $('.formGeral').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function(responseText, statusText, xhr, $form) {
            searchUpdate(id)
            Swal.fire({
                text: 'Atualizado com sucesso!',
                icon: 'success'
            })
        },
        error: function(xhr, status, error) {
            Swal.fire({
                text: 'Erro ao atualizar...',
                icon: 'error'
            });
        }
    });
}

function updateImage(id) {
    $("#profile-img-file-input").on('change', function() {
        $('.formUpload').submit();
    });
    $('.formUpload').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            console.log('Enviando...')
        },
        success: function(responseText, statusText, xhr, $form) {
            searchUpdate(id);
            Swal.fire({
                text: 'Imagem atualizada com sucesso!',
                icon: 'success'
            })
        },
        error: function(xhr, status, error) {
            Swal.fire({
                text: 'Erro ao atualizar imagem',
                icon: 'error'
            });
        }
    });
}

function updateLinks(id) {
    $(".enviaLinks").on('change', function() {
        $('.formTexts').submit();
    });

    $('.formTexts').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            options.type = 'PUT'
        },
        success: function(responseText, statusText, xhr, $form) {
            searchUpdate(id);
            $(".alertAlterado").show(),
                setTimeout(() => {
                    $(".alertAlterado").fadeOut()
                }, 1200);
        },
        error: function(xhr, status, error) {

        }
    });
}

function formatInputs() {
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
}

$(document).ready(function() {
    searchUpdate(_idSearch)
    formatInputs()
    updateLinks(_idSearch)
    updateImage(_idSearch)
    updateTexts(_idSearch)
});