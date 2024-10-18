<?= $this->extend('igrejas/template') ?>
<?= $this->section('page') ?>
<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">Realizando pagamento por cartão de débito</h1>
</div>

<?= $this->endSection() ?>
<?= $this->section("js") ?>
<!-- Swiper Js -->
<script src="/assets/libs/swiper/swiper-bundle.min.js"></script>
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