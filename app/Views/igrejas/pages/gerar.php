<?= $this->extend('igrejas/template') ?>

<?= $this->section('page') ?>

<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">FAZER PAGAMENTO</h1>
</div>

<div class="col-xxl-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center"
                        role="tablist" aria-orientation="vertical">
                        <a class="nav-link active show" id="pix-tab-tab" data-bs-toggle="pill" href="#pix-tab"
                            role="tab" aria-controls="pix-tab" aria-selected="true">
                            <i class="ri-home-4-line d-block fs-20 mb-1"></i> PIX
                        </a>
                        <a class="nav-link" id="custom-v-pills-profile-tab" data-bs-toggle="pill"
                            href="#custom-v-pills-profile" role="tab" aria-controls="custom-v-pills-profile"
                            aria-selected="false">
                            <i class=" ri-bank-card-line d-block fs-20 mb-1"></i> CARTÃO DE CRÉDITO
                        </a>
                        <a class="nav-link" id="custom-v-pills-messages-tab" data-bs-toggle="pill"
                            href="#custom-v-pills-messages" role="tab" aria-controls="custom-v-pills-messages"
                            aria-selected="false">
                            <i class="ri-mail-line d-block fs-20 mb-1"></i> CARTÃO DE DÉBITO
                        </a>
                        <a class="nav-link" id="custom-v-pills-messages-tab" data-bs-toggle="pill"
                            href="#custom-v-pills-messages" role="tab" aria-controls="custom-v-pills-messages"
                            aria-selected="false">
                            <i class="ri-mail-line d-block fs-20 mb-1"></i> GERAR BOLETO
                        </a>
                    </div>
                </div>
                <!-- end col-->
                <div class="col-lg-9">
                    <div class="tab-content text-muted mt-3 mt-lg-0">

                        <!--end tab-pane-->
                        <div class="tab-pane fade" id="custom-v-pills-profile" role="tabpanel"
                            aria-labelledby="custom-v-pills-profile-tab">

                        </div>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane fade" id="custom-v-pills-messages" role="tabpanel"
                        aria-labelledby="custom-v-pills-messages-tab">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <img src="/assets/images/small/img-7.jpg" alt="" width="150" class="rounded">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party
                                    before they sold out master cleanse gluten-free squid scenester freegan cosby
                                    sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life
                                    echo park Austin. Cred vinyl keffiyeh DIY salvia PBR.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-grow-1 me-3">
                                <p class="mb-0">They all have something to say beyond the words on the page. They can
                                    come across as casual or neutral, exotic or graphic. That's why it's important to
                                    think about your message, then choose a font that fits. Cosby sweater eu banh mi,
                                    qui irure terry richardson ex squid.</p>
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



<button type="button" class="btn btn-primary btn-lg waves-effect waves-light" data-bs-toggle="modal"
    data-bs-target="#modalPix">
    Launch
</button>




<!-- Modal -->
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect waves-light"
                    data-bs-dismiss="modal">Fechar</button>
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

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function() {
    $('.valor').mask('000.000.000.000.000,00', {
        reverse: true
    });
    dataUser();
    /*$('.valor').maskMoney({
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: true
    });*/
});

function dataUser() {
    $.getJSON(_baseUrl + "api/v1/usuarios/user",
        function(data, textStatus, jqXHR) {
            if (data && data.perfil) {
                const nome = data.perfil.nome ? data.perfil.nome : (data.perfil.razao_social ? data.perfil
                    .razao_social : '');
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