<?= $this->extend('igrejas/template') ?>

<?= $this->section('page') ?>
<div class="p-0 m-0">
    <div class="col-xxl-12">
        <h1 class="mb-3 fw-bolder">Ralizar um pagamento</h1>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active show" id="pix-tab-tab" data-bs-toggle="pill" href="#pix-tab" role="tab" aria-controls="pix-tab" aria-selected="true">
                                <i class="ri-home-4-line d-block fs-20 mb-1"></i> PIX
                            </a>
                            <a class="nav-link" id="custom-v-pills-profile-tab" data-bs-toggle="pill" href="#custom-v-pills-profile" role="tab" aria-controls="custom-v-pills-profile" aria-selected="false">
                                <i class=" ri-bank-card-line d-block fs-20 mb-1"></i> CARTÃO DE CRÉDITO
                            </a>
                            <!--  <a class="nav-link" id="custom-v-pills-messages-tab" data-bs-toggle="pill" href="#custom-v-pills-messages" role="tab" aria-controls="custom-v-pills-messages" aria-selected="false">
                                <i class="ri-mail-line d-block fs-20 mb-1"></i> CARTÃO DE DÉBITO
                            </a> -->
                        </div>
                    </div> <!-- end col-->
                    <div class="col-lg-9">
                        <div class="tab-content text-muted mt-3 mt-lg-0">
                            <div class="tab-pane fade active show" id="pix-tab" role="tabpanel" aria-labelledby="pix-tab-tab">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <?= form_open('/api/v1/cielo/pix-charge', ['id' => 'formPix']) ?>
                                        <h4>Realizando pagamento com o PIX</h4>
                                        <div class="alert alert-info">
                                            <b>A alteração dos dados nesse formulário influencia apenas na geração do PIX e não reeflete no seu cadastro geral.</b>
                                        </div>
                                        <div class="mt-3">
                                            <label form="nomePix" class="text-dark">Nome completo</label>
                                            <input type="text" class="form-control form-control-lg" placeholder="Seu nome completo" required minlength="5" id="nomePix" name="nome" readonly>
                                        </div>
                                        <div class="mt-3">
                                            <label for="valor" class="text-dark">Informe o valor</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text" id="valorPix">R$</span>
                                                <input type="text" name="valor" id="valor" class="form-control valor" placeholder="0,00" aria-label="0,00" aria-describedby="valorPix" required>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <label for="docPix" class="text-dark">Documento</label>
                                            <input type="text" name="doc" id="docPix" class="form-control form-control-lg" placeholder="000.000.000-00" required readonly>
                                        </div>
                                        <div class="mt-2">
                                            <label for="tipoPix" class="text-dark">Escolha uma opção</label>
                                            <select name="tipo" id="tipoPix" class="form-select form-select-lg" required>
                                                <option value="" selected>Escolha uma opção</option>
                                                <option value="dizimo">Dízimo</option>
                                                <option value="oferta">Oferta</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descLongaPix">Caso seja necessário, dê mais detalhes sobre esse pagamento</label>
                                            <textarea class="form-control form-control-lg" name="descPix" id="descLongaPix" rows="3" placeholder="Ex: Oferta missões"></textarea>
                                        </div>
                                        <div class="mt-2 mb-4">
                                            <button class="btn btn-lg btn-primary" type="submit">GERAR PIX</button>
                                        </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-6 text-center">
                                        <div id="areaStatusPixNo">
                                            <h5>Os dados para realizar o pix apareceram aqui:</h5>
                                            <img src="<?= base_url("assets/images/file.png") ?>" id="qrCodeGer" alt="Multidesk.io" width="250px">
                                            <input type="text" class="form-control mt-2" id="copiaColaPix" readonly style="display: none;">
                                            <button class="btn btn-info mt-2" style="display: none;" id="btnCopiaColaPix">COPIAR CÓDIGO DE PAGAMENTO</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane fade" id="custom-v-pills-profile" role="tabpanel" aria-labelledby="custom-v-pills-profile-tab">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="alert alert-danger bg-danger text-white">
                                            <b>Os seus dados do seu cartão de crédito não serão armazenados por nós.</b>
                                        </div>
                                        <div class="form-container active">
                                            <?= form_open('/api/v1/cielo/credit-card-charge', 'id="card-form-elem" class="formCredit" autocomplete="off"') ?>
                                            <div class="mb-3">
                                                <label for="valorCredito" class="text-dark">Informe o valor</label>
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text">R$</span>
                                                    <input type="text" name="valor" id="valorCredito" class="form-control valor" placeholder="0,00" aria-label="0,00" aria-describedby="valorCredito" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="card-number-input" class="form-label">Número do cartão</label>
                                                <input class="form-control form-control-lg" placeholder="0000 0000 0000 0000" type="tel" id="card-number-input" name="cartao">
                                            </div>
                                            <div class="mb-3">
                                                <label for="card-name-input" class="form-label">Nome no cartão</label>
                                                <input class="form-control form-control-lg" placeholder="Nome no cartão" type="text" id="card-name-input" name="nome">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="card-expiry-input" class="form-label">Expiração</label>
                                                        <input class="form-control form-control-lg" placeholder="MM/YYYY" type="text" id="card-expiry-input" name="data">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="card-cvc-input" class="form-label">CVC</label>
                                                        <input class="form-control form-control-lg" placeholder="CVC" type="number" id="card-cvc-input" name="securicode">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tipoCredito" class="text-dark">Escolha uma opção</label>
                                                <select name="tipo" id="tipoCredito" class="form-select form-select-lg" required>
                                                    <option value="" selected>Escolha uma opção</option>
                                                    <option value="dizimo">Dízimo</option>
                                                    <option value="oferta">Oferta</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descLonga">Caso seja necessário, dê mais detalhes sobre esse pagamento</label>
                                                <textarea class="form-control form-control-lg" name="desc" id="descLonga" rows="3" placeholder="Ex: Oferta missões"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-primary btn-lg w-100" type="submit">PAGAR AGORA</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="card card-height-100">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Pagamento por cartão de crédito</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-wrapper mb-3"></div>
                                                <div class="mt-3 text-center">
                                                    <img src="/assets/images/sistema/cards.png" class="img-fluid rounded-top" alt="" width="250px" />

                                                </div>
                                            </div>
                                            <!-- end card-body -->
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane fade" id="custom-v-pills-messages" role="tabpanel" aria-labelledby="custom-v-pills-messages-tab">
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <img src="/assets/images/small/img-7.jpg" alt="" width="150" class="rounded">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0">Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR.</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-0">They all have something to say beyond the words on the page. They can come across as casual or neutral, exotic or graphic. That's why it's important to think about your message, then choose a font that fits. Cosby sweater eu banh mi, qui irure terry richardson ex squid.</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <img src="/assets/images/small/img-8.jpg" alt="" width="150" class="rounded">
                                </div>
                            </div>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div> <!-- end col-->
            </div> <!-- end row-->
        </div><!-- end card-body -->
    </div>
    <!--end card-->
</div>
<!--end col-->
</div>

<?= $this->endSection() ?>

<?= $this->section("js") ?>
<!-- Swiper Js -->
<script src="/assets/libs/swiper/swiper-bundle.min.js"></script>

<script src="/assets/libs/card/card.js"></script>
<!-- Widget init -->
<script src="/assets/js/pages/new-widgets.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        dataUser();
        $('.valor').maskMoney({
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            affixesStay: true
        });
    });

    function dataUser() {
        $.getJSON(_baseUrl + "api/v1/usuarios/user",
            function(data, textStatus, jqXHR) {
                if (data && data.perfil) {
                    const nome = data.perfil.nome ? data.perfil.nome : (data.perfil.razao_social ? data.perfil.razao_social : '');
                    const sobrenome = data.perfil.sobrenome ? data.perfil.sobrenome : '';
                    const nomeCompleto = `${nome} ${sobrenome}`.trim();

                    const documento = data.perfil.cpf ? data.perfil.cpf : (data.perfil.cnpj ? data.perfil.cnpj : '');

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