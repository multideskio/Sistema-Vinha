<?= $this->extend('admin/template') ?>
<?= $this->section('css'); ?>
<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<!-- Filepond css -->
<link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css" type="text/css" />
<link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
<?= $this->endSection(); ?>
<?= $this->section('page') ?>
<div class="clearfix mb-1">
    <p class="text-muted float-start">Gerenciamento de portais para pagamentos</p>
</div>

<div class="row gx-3">
    <div class="col-md-6">
        <div class="card border card-border-primary">
            <div class="card-body">
                <?= form_open('api/v1/gateways', ['id' => 'formCad']) ?>
                <h3 class="card-title">CIELO</h3>
                <div class="row gx-1">
                    <div class="alert alert-danger bg-danger text-white" style="display: none;" id="desenvolvimento">
                        <b>Neste momento sua plataforma não está gerando cobranças reais</b>
                    </div>
                    <div class="alert alert-success bg-success text-white" style="display: none;" id="producao">
                        <b>Seu CheckOut está ativo</b>
                    </div>
                    <label for="statusgate">Status</label>
                    <select class="form-select" name="status" id="statusgate" required>
                        <option value="" selected>Escolha uma opção</option>
                        <option value="1">Em produção</option>
                        <option value="0">Em desenvolvimento</option>
                    </select>
                    <div class="row gx-1 p-0">
                        <div class="col-lg-6">
                            <div class="mt-2">
                                <label for="merchantid_pro">Merchantid de produção</label>
                                <input type="text" name="idPro" id="merchantid_pro" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mt-2">
                                <label for="merchantkey_pro">Merchantkey de produção</label>
                                <input type="text" name="keyPro" id="merchantkey_pro" class="form-control">
                            </div>
                        </div>
                    </div>
                    <legend class="fs-4 mt-2"><b>Configuração do checkout</b></legend>
                    <div class="col-lg-12 fs-6">
                        <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input" type="checkbox" role="switch" id="activePix" name="activePix" value="1">
                            <label class="form-check-label" for="activePix">Pix</label>
                        </div>
                        <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input" type="checkbox" role="switch" id="activeCredito" name="activeCredito" value="1">
                            <label class="form-check-label" for="activeCredito">Cartão de crédito</label>
                        </div>
                        <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input" type="checkbox" role="switch" id="activeDebito" name="activeDebito" value="1">
                            <label class="form-check-label" for="activeDebito">Cartão de débito</label>
                        </div>
                        <!-- <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input" type="checkbox" role="switch" id="activeBoletos" name="activeBoletos" value="1">
                            <label class="form-check-label" for="activeBoletos">Boletos</label>
                        </div> -->
                    </div>
                    <div class="mt-3 alert alert-warning bg-warning text-dark">
                        <b>Credenciais de desenvolvimento</b>
                    </div>
                    <div class="row gx-1 p-0">
                        <div class="col-lg-6">
                            <div class="">
                                <label for="merchantid_dev">Merchantid de Desenvolvimento</label>
                                <input type="text" name="idDev" id="merchantid_dev" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label for="merchantkey_dev">Merchantkey de desenvolvimento</label>
                                <input type="text" name="keyDev" id="merchantkey_dev" class="form-control">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="tipo" value="cielo">
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Atualizar credenciais</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-info">
            <h4>Atenção:</h4>
            <p>Os recursos a seguir estão liberados na plataforma</p>
            <b>Tipos de pagamentos:</b>
            <ul>
                <li>PIX (Cielo)</li>
                <li>Cartão de Crédio (Cielo)</li>
                <li>Cartão de débito (Cielo)</li>
                <!-- <li>Boleto (BRADESCO/CIELO)</li> -->
            </ul>
            <p>Estamos trabalhando para melhor a configuração de cada um dos meio de pagamento.</p>
        </div>
        <!--<div class="card border card-border-danger">
            <div class="card-header">
                <h3 class="card-title">BRADESCO</h3>
            </div>
            <div class="card-body">
                <?= form_open('api/v1/gateways') ?>
                </form>
            </div>
        </div>-->
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        atualizarTabela();
    });

    function atualizarTabela() {
        $.getJSON(_baseUrl + "/api/v1/gateways/cielo")
            .done(function(data) {
                const setCheckboxState = (selector, state) => {
                    $(selector).prop('checked', state);
                };

                $("#desenvolvimento").toggle(data.status != 1);
                $("#producao").toggle(data.status == 1);

                setCheckboxState("#activePix", data.active_pix == 1);
                setCheckboxState("#activeCredito", data.active_credito == 1);
                setCheckboxState("#activeDebito", data.active_debito == 1);
                setCheckboxState("#activeBoletos", data.active_boletos == 1);

                $("#merchantid_pro").val(data.merchantid_pro);
                $("#merchantkey_pro").val(data.merchantkey_pro);
                $("#merchantid_dev").val(data.merchantid_dev);
                $("#merchantkey_dev").val(data.merchantkey_dev);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Erro ao carregar dados: ", textStatus, errorThrown);
                // Aqui você pode adicionar código para notificar o usuário sobre o erro, se necessário.
            });
    }
</script>

<?= $this->endSection(); ?>