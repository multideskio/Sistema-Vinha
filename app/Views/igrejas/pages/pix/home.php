<?= $this->extend('igrejas/template') ?>
<?= $this->section('page') ?>
<div class="col-xxl-12 mb-3">
    <h1 class="fw-bolder mb-0">PIX</h1>
    <b>Faça um pagamento por PIX</b>
    <hr>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <?= form_open('/api/v1/cielo/pix-charge', ['id' => 'formPix']) ?>
                <!-- <div class="mt-3">
                    <label for="nomePix" class="text-dark">Nome completo</label> -->
                <input type="hidden" class="form-control" placeholder="Seu nome completo" required minlength="5"
                    id="nomePix" name="nome" readonly>
                <!-- </div> -->

                <div class="mt-3">
                    <label for="valor" class="text-dark">Informe o valor:</label>
                    <div class="input-group">
                        <span class="input-group-text" id="valorPix">R$</span>
                        <input type="text" name="valor" id="valor" class="form-control valor" placeholder="0,00"
                            aria-label="0,00" aria-describedby="valorPix" required>
                    </div>
                </div>

                <!--<div class="mt-3"> <label for="docPix" class="text-dark">Documento</label> -->
                <input type="hidden" name="doc" id="docPix" class="form-control" placeholder="000.000.000-00" required>
                <!-- </div> -->

                <div class="mt-3">
                    <label for="tipoPix" class="text-dark">Escolha uma opção:</label>
                    <select name="tipo" id="tipoPix" class="form-select" required>
                        <option value="" selected>Escolha uma opção</option>
                        <option value="dizimo">Dízimo</option>
                        <option value="oferta">Oferta</option>
                    </select>
                </div>

                <div class="mb-3 mt-3">
                    <label for="descLongaPix">Caso seja necessário, dê mais detalhes sobre esse pagamento:</label>
                    <textarea class="form-control" name="descPix" id="descLongaPix" rows="3"
                        placeholder="Ex: Oferta missões"></textarea>
                </div>

                <div class="mt-3 mb-4 d-grid gap-2">
                    <button class="btn btn-primary" type="submit">GERAR PIX</button>
                </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="alert alert-warning alert-dismissible bg-warning text-dark alert-label-icon fade show" role="alert">
            <i class="ri-error-warning-line label-icon"></i><strong>Atenção!</strong> - Os dados para realizar o PIX
            aparecerão após informar os dados e clicar no botão <b>GERAR PIX</b>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPix" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalPixLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3 class="fw-bold text-center mb-3">Dados para realizar o pagamento</h3>
                <img src="<?= base_url("assets/images/file.png") ?>" id="qrCodeGer" alt="Multidesk.io" width="250px">
                <input type="text" class="form-control mt-2" id="copiaColaPix" readonly style="display: none;">
                <button class="btn btn-info waves-effect waves-light mt-2" style="display: none;"
                    id="btnCopiaColaPix">COPIAR CÓDIGO DE PAGAMENTO</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section("js") ?>
<!-- Swiper Js -->
<script src="/assets/libs/swiper/swiper-bundle.min.js"></script>
<script src="/assets/libs/card/card.js"></script>
<!-- Widget init -->
<script src="/assets/js/pages/new-widgets.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('.valor').mask('000.000.000.000.000,00', {
        reverse: true
    });
    dataUser();
});

function dataUser() {
    $.getJSON(_baseUrl + "api/v1/usuarios/user",
        function(data, textStatus, jqXHR) {
            if (data && data.perfil) {
                const nome = data.perfil.nome ? data.perfil.nome : (data.perfil.razao_social ? data.perfil
                    .razao_social : '');
                const sobrenome = data.perfil.sobrenome ? data.perfil.sobrenome : '';
                const nomeCompleto = `${nome} ${sobrenome}`.trim();
                const documento = data.perfil.cpf ? data.perfil.cpf : (data.perfil.cnpj ? data.perfil.cnpj :
                    '');
                $("#nomePix").val(nomeCompleto);
                $("#docPix").val(documento);
            } else {
                console.error("Perfil ou dados do usuário não encontrados.");
            }
        }
    );
}
</script>


<?= $this->include('igrejas/pages/js/pagamentos.php') ?>
<?= $this->endSection() ?>