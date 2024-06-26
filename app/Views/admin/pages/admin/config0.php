<?= $this->extend('admin/template') ?>
<?= $this->section('css'); ?>
<!-- Sweet Alert css-->
<link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection(); ?>
<?= $this->section('page') ?>
<div class="clearfix">
    <p class="text-muted float-start">Configure toda sua plataforma</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>
                    <b>Informações sobre a empresa</b>
                </p>

                <?= form_open() ?>

               

                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>Configuração de envio de e-mail SMTP</p>
                <?= form_open() ?>
                
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>Dados da API de WhatsApp</p>
                <?= form_open() ?>
                
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>