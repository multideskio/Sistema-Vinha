<?= $this->extend('admin/template') ?>
<?= $this->section('css'); ?>

<!-- quill css -->
<link href="/assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
<!-- bubble css for bubble editor-->
<link href="/assets/libs/quill/quill.bubble.css" rel="stylesheet" type="text/css" />
<!-- snow css for snow editor-->
<link href="/assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />



<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<?= $this->endSection(); ?>
<?= $this->section('page') ?>
<div class="clearfix mb-1">
    <p class="text-muted float-start">Genrenciamento do blog de ajuda</p>
    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#cadastrarAjuda">
        <i class="ri-question-line"></i> Cadastrar tópico
    </button>
</div>





<?= $this->include('admin/pages/ajuda/modal.php') ?>



<?= $this->endSection(); ?>




<?= $this->section('js') ?>

<!-- quill js -->
<script src="/assets/libs/quill/quill.min.js"></script>

<!-- init js -->


<!-- inserir linha -->
<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });


    // Adicione um evento de clique ao botão de envio
    $('#submitBtn').on('click', function(event) {
        // Impedir o comportamento padrão do botão (envio do formulário)
        event.preventDefault();

        // Copiar o conteúdo do editor Quill para o campo oculto
        var htmlContent = quill.root.innerHTML;
        $('#conteudo').val(htmlContent);

        // Submeta o formulário via AJAX
        $('#formHelper').ajaxSubmit({
            success: function(responseText, statusText, xhr, $form) {
                atualizarTabela();
                // Limpar o formulário
                $('#formHelper')[0].reset();
                // Exibir mensagem de sucesso
                quill.setContents([]);
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
    });
</script>

<script>
    function atualizarTabela() {

    }
</script>
<?= $this->endSection(); ?>