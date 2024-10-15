<?= $this->extend('igrejas/template') ?>
<?= $this->section('page') ?>
<div class="col-xxl-12 mb-3">
    <h1 class=" fw-bolder">Cartão de crédito</h1>
    <b>Faça um pagamento com o cartão de crédito</b>
    <hr>
</div>


<div class="row">
    <div class="col-lg-6">
        <div class="form-container">
            <div class="card">
                <div class="card-body">
                    <?= form_open('/api/v1/cielo/credit-card-charge', 'id="card-form-elem" class="formCredit" autocomplete="off"') ?>
                    <div class="mb-3">
                        <label for="valorCredito" class="text-dark">Informe o valor a ser pago</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" name="valor" id="valorCredito" class="form-control valor"
                                placeholder="0,00" aria-label="0,00" aria-describedby="valorCredito" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="card-number-input" class="form-label">Número do cartão</label>
                        <input class="form-control" placeholder="0000 0000 0000 0000" type="tel" id="card-number-input"
                            name="cartao">
                    </div>
                    <div class="mb-3">
                        <label for="card-name-input" class="form-label">Nome no cartão</label>
                        <input class="form-control" placeholder="Nome no cartão" type="text" id="card-name-input"
                            name="nome">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="card-expiry-input" class="form-label">Expiração</label>
                                <input class="form-control" placeholder="MM/YYYY" type="text" id="card-expiry-input"
                                    name="data">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="card-cvc-input" class="form-label">CVC</label>
                                <input class="form-control" placeholder="CVC" type="number" id="card-cvc-input"
                                    name="securicode">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tipoCredito" class="text-dark">Escolha uma opção</label>
                        <select name="tipo" id="tipoCredito" class="form-select" required>
                            <option value="" selected>Escolha uma opção</option>
                            <option value="dizimo">Dízimo</option>
                            <option value="oferta">Oferta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="descLonga">Caso seja necessário, dê mais detalhes sobre esse
                            pagamento</label>
                        <textarea class="form-control" name="desc" id="descLonga" rows="3"
                            placeholder="Ex: Oferta missões"></textarea>
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary btn-lg w-100" type="submit">PAGAR
                            AGORA</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-height-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Pagamento por cartão de crédito</h5>
            </div>
            <div class="card-body">
                <!-- Primary Alert -->
                <div class="alert alert-primary alert-dismissible bg-primary text-white alert-label-icon fade show"
                    role="alert">
                    <i class="ri-error-warning-line label-icon"></i><b>Seus dados de cartão de crédito não serão
                        armazenados por nós.</b>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                <div class="card-wrapper"></div>
                <!-- end card-body -->
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
                    $("#card-name-input").val(nomeCompleto);
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