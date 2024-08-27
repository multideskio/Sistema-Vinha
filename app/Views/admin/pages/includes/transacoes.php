<?= $this->include('admin/pages/includes/search.php') ?>
<div class="table-responsive">
    <div id="cardResult" style="display: none">
        <div class="page-link" id="numResults"></div>
        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Descrição</th>
                    <th title="Data de emissão">Dt.Em</th>
                    <th title="Data de pagamento">Dt.Pg</th>
                    <th>Valor</th>
                    <th>Situação</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tabela-dados">
            </tbody>
        </table>
        <div class="text-right">
            <b>Valor:</b> <span id="valorPageView"></span> <br>
            <b>Valor total:</b> <span id="valorTotalView"></span>
        </div>
        <div id="pager">
        </div>
    </div>
    <!--  -->
    <div class="noresult" style="display: none">
        <div class="text-center">
            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
            <h5 class="mt-2">Nunhuma transação foi encontrada para esse usuário.</h5>
        </div>
    </div>
</div>
<div class="loadResult">
    <div class="text-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="mt-2">Carregando registros</h5>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Reembolsar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('', 'class="formReembolso" id="formReembolso"') ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="descRembolso" class="text-danger">Descrição do reembolso</label>
                        <textarea required name="desc" id="descRembolso" class="form-control" placeholder="Descreva o motivo para esse reembolso" rows="5"></textarea>
                    </div>
                    <input type="hidden" name="valor" id="valor" required readonly>
                    <input type="hidden" id="id_transacao" name="id_transacao" required readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Devolver dinheiro</button>
                </div>
            </form>
        </div>
    </div>
</div>