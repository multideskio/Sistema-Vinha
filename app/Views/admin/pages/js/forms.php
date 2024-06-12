<!-- inserir linha -->
<script>
    $('#formCad').ajaxForm({
        beforeSubmit: function(formData, jqForm, options) {
            // Executar ações antes de enviar o formulário (se necessário)
        },
        success: function(responseText, statusText, xhr, $form) {
            atualizarTabela();
            // Limpar o formulário
            $('#formCad')[0].reset();
            // Exibir mensagem de sucesso
            //exibirMensagem('success', 'Sucesso: ' + responseText);
            Swal.fire({
                title: 'Cadastrado!',
                type: 'success',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                buttonsStyling: false,
            });
        },
        error: function(xhr, status, error) {
            // Verifica se a resposta é um JSON
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                // Exibir mensagem de erro vinda do servidor
                exibirMensagem('error', xhr.responseJSON);
            } else {
                // Exibir mensagem de erro genérica
                exibirMensagem({
                    messages: {
                        error: 'Erro desconhecido.'
                    }
                });
            }
        }
    });
</script>

<!-- FORM UPDATES -->
<script>
</script>

<!-- filepond js -->
<script src="/assets/libs/filepond/filepond.min.js"></script>
<script src="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
<script src="/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
<script src="/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js"></script>

<!-- form inputs -->
<script>
    $(document).ready(function() {
        FilePond.registerPlugin(
            // encodes the file as base64 data
            FilePondPluginFileEncode,
            // validates the size of the file
            FilePondPluginFileValidateSize,
            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,
            // previews dropped images
            FilePondPluginImagePreview
        );

        FilePond.create(
            document.querySelector('.filepond-input-circle'), {
                labelIdle: 'Clique para carregar a imagem',
                imagePreviewHeight: 170,
                imageCropAspectRatio: '1:1',
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                stylePanelLayout: 'compact circle',
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',
            }
        );

        //FORMATAÇÃO DE IMPUTS
        var cleave = new Cleave('.cpf', {
            numericOnly: true,
            delimiters: ['.', '.', '-'],
            blocks: [3, 3, 3, 2],
            uppercase: true
        });

        var cleave = new Cleave('.cep', {
            numericOnly: true,
            delimiters: ['-'],
            blocks: [5, 3],
            uppercase: true
        });

        var cleaveBlocks = new Cleave('.telFixo', {
            numericOnly: true,
            delimiters: ['(', ') ', '-'],
            blocks: [0, 2, 4, 4]
        });

        var cleaveBlocks = new Cleave('.celular', {
            numericOnly: true,
            delimiters: ['+', ' (', ') ', ' ', '-'],
            blocks: [0, 2, 2, 1, 4, 4]
        });
        ///
    });
</script>


<!-- Excluir linha -->
<script>
    function excluir(id, endPoint) {
        // Exibe uma mensagem de confirmação ao usuário antes de excluir o registro
        Swal.fire({
            title: "Tem certeza?",
            text: "Você não poderá reverter isso!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, exclua-o!",
            confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
            cancelButtonClass: 'btn btn-danger w-xs mt-2',
            buttonsStyling: false,
            showCloseButton: true
        }).then((result) => {
            // Verifica se o usuário confirmou a exclusão
            if (result.value) {
                // Envia uma requisição AJAX para excluir o registro
                $.ajax({
                    type: "DELETE",
                    url: `${_baseUrl}api/v1/${endPoint}/${id}`,
                    dataType: "JSON",
                }).done(() => {
                    // Exibe uma mensagem de sucesso após a exclusão e atualiza a tabela
                    Swal.fire({
                        title: 'Excluído!',
                        text: 'O registro foi excluído com sucesso.',
                        type: 'success',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false,
                    });

                    /*setTimeout(() => {
                        location.reload();
                    }, 1200);*/
                    atualizarTabela()


                }).fail(() => {
                    // Exibe uma mensagem de erro em caso de falha na requisição AJAX
                    Swal.fire({
                        title: "Erro ao excluir",
                        text: "Ocorreu um erro ao tentar excluir o registro.",
                        type: "error",
                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                        buttonsStyling: false,
                    });
                });
            }
        });
    }
</script>





<script>
    function exibirMensagem(type, error) {
        // Extrai as mensagens de erro do objeto 'error'
        let messages = error.messages;

        // Inicializa uma string para armazenar as mensagens formatadas
        let errorMessage = '';

        // Itera sobre as mensagens de erro e as formata
        for (let key in messages) {
            errorMessage += `${messages[key]}\n`;
        }

        // Exibe a mensagem de erro formatada
        Swal.fire({
            title: "Erro ao incluir registro",
            text: `${errorMessage}`,
            type: type,
            confirmButtonClass: "btn btn-primary w-xs mt-2",
            buttonsStyling: false,
        });
    }
</script>
