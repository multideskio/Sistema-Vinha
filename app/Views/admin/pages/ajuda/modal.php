<!-- Modal -->
<div class="modal fade" id="cadastrarAjuda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastrarAjudaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarAjudaLabel">Cadastro de gerente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <?= form_open_multipart('api/v1/ajuda', ['id' => 'formHelper']); ?>
            <div class="modal-body ">
                <div class="row gx-1">
                    <label for="titulo">Titulo</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" required placeholder="Primeiro nome">
                </div>
                <div class="mt-2">
                    <label for="conteudo">Conteúdo</label>
                    <div class="snow-editor" id="editor" style="width: 100%; height: 200px;"></div>
                    <input type="hidden" name="conteudo" id="conteudo">
                </div>
                <div class="mt-2">
                    <label for="tags">Tags de marcação</label>
                    <input type="text" class="form-control" name="tags" id="tags" placeholder="Separe por vírgulas">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Cadastrar</button>
            </div>
            </form>
        </div>
    </div>
</div>