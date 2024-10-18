<?= $this->extend('igrejas/template') ?>
<?= $this->section('page') ?>
<div class="col-xxl-12 mb-3">
    <h1 class="mb-0 fw-bolder">Boleto</h1>
    <b>Faça um pagamento por boleto bancário</b>
    <hr>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?= form_open("/api/v1/cielo/boleto-charge", 'id="formBoleto"') ?>

                <div class="mt-3">
                    <label for="valor" class="text-dark">Informe o valor</label>
                    <div class="input-group">
                        <span class="input-group-text" id="valorPix">R$</span>
                        <input type="text" name="valor" id="valor" class="form-control valor" placeholder="0,00"
                            aria-label="0,00" aria-describedby="valorPix" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="tipoPix" class="text-dark">Escolha uma opção</label>
                    <select name="tipo" id="tipoPix" class="form-select" required>
                        <option value="" selected>Escolha uma opção</option>
                        <option value="dizimo">Dízimo</option>
                        <option value="oferta">Oferta</option>
                    </select>
                </div>

                <div class="mb-3 mt-3">
                    <label for="desc">Caso seja necessário, dê mais detalhes sobre esse pagamento</label>
                    <textarea class="form-control" name="desc" id="desc" rows="3"
                        placeholder="Ex: Oferta missões"></textarea>
                </div>

                <div class="mt-3 mb-4 d-grid gap-2">
                    <button class="btn btn-primary" type="submit">GERAR BOLETO</button>
                </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-custom shadow-sm gutter-b d-none" id="boleto-info" style="border: 1px solid #e3e6f0;">
            <!-- Adicionei border e shadow -->
            <div class="card-header py-3" style="background-color: #f8f9fc;">
                <h3 class="card-title font-weight-bolder text-dark" style="font-size: 16px;">Boleto Gerado</h3>
            </div>
            <div class="card-body" style="font-size: 14px; padding: 20px;">
                <!-- Informações do cliente -->
                <div class="mb-4" style="border-bottom: 1px solid #e3e6f0; padding-bottom: 15px;">
                    <h5 class="card-label font-weight-bold mb-3">Cliente</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <p><strong>Nome:</strong> <span id="cliente-nome"></span></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <p><strong>CPF:</strong> <span id="cliente-cpf"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="mb-4" style="border-bottom: 1px solid #e3e6f0; padding-bottom: 15px;">
                    <h5 class="card-label font-weight-bold mb-3">Endereço</h5>
                    <p id="endereco" class="text-muted"></p>
                </div>

                <!-- Detalhes do pagamento -->
                <div class="mb-4" style="border-bottom: 1px solid #e3e6f0; padding-bottom: 15px;">
                    <h5 class="card-label font-weight-bold mb-3">Detalhes do Pagamento</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <p><strong>Vencimento:</strong> <span id="vencimento"></span></p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <p><strong>Valor:</strong> <span id="valorText"></span></p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <p><strong>Número do Boleto:</strong> <span id="numero-boleto"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Código de Barras -->
                <div class="mb-4" style="border-bottom: 1px solid #e3e6f0; padding-bottom: 15px;">
                    <h5 class="card-label font-weight-bold mb-3">Código de Barras</h5>
                    <p id="codigo-barras" class="font-weight-bolder text-primary" style="font-size: 15px;"></p>
                </div>

                <!-- Linha Digitável -->
                <div class="mb-4">
                    <h5 class="card-label font-weight-bold mb-3">Linha Digitável</h5>
                    <div class="d-flex align-items-center">
                        <p id="linha-digitavel" class="font-weight-bolder text-danger mb-0"
                            style="font-size: 15px; margin-right: 10px;"></p>
                        <button class="btn btn-light-primary btn-sm" id="copiar-linha-digitavel"
                            style="border: 1px solid #ddd;">
                            Copiar
                        </button>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="mt-4 d-flex">
                    <a href="#" id="ver-boleto" class="btn btn-primary font-weight-bold mr-3"
                        style="padding: 10px 20px;" target="_blank">
                        Visualizar Boleto
                    </a>
                    <a href="#" id="baixar-boleto" class="btn btn-success font-weight-bold" style="padding: 10px 20px;"
                        download="boleto.pdf" target="_blank">
                        Baixar Boleto
                    </a>
                </div>
            </div>
        </div>
        <div class="alert alert-warning alert-dismissible bg-warning text-dark alert-label-icon fade show" role="alert">
            <i class="bi bi-info-circle label-icon"></i><strong>Importante:</strong> Os dados para realizar o
            pagamento
            via PIX serão exibidos após o preenchimento dos campos e ao clicar em <b>GERAR PIX</b>.
        </div>
        <div class="alert alert-dark alert-dismissible bg-dark text-white alert-label-icon fade show" role="alert">
            <i class="bi bi-lightning-charge label-icon"></i><strong>Processo automático:</strong> O pagamento será
            confirmado automaticamente após sua conclusão.
        </div>
        <div class="alert alert-primary alert-dismissible bg-primary text-white alert-label-icon fade show"
            role="alert">
            <i class="bi bi-chat-dots label-icon"></i><strong>Atualize seu WhatsApp:</strong> Certifique-se de que o
            número está atualizado para receber as confirmações.
        </div>
        <div class="alert alert-success alert-dismissible bg-success text-white alert-label-icon fade show"
            role="alert">
            <i class="bi bi-shield-check label-icon"></i><strong>Segurança garantida:</strong> Estamos comprometidos
            com
            a proteção dos seus dados.
        </div>
    </div>
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


// Função para copiar a linha digitável para a área de transferência
document.getElementById('copiar-linha-digitavel').addEventListener('click', function() {
    var linhaDigitavel = document.getElementById('linha-digitavel').innerText;
    navigator.clipboard.writeText(linhaDigitavel).then(function() {
        alert('Linha digitável copiada com sucesso!');
    }, function(err) {
        alert('Erro ao copiar a linha digitável: ', err);
    });
});
</script>


<?= $this->include('igrejas/pages/js/pagamentos.php') ?>
<?= $this->endSection() ?>