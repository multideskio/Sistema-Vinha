<?= $this->extend('admin/template') ?>
<?= $this->section('page') ?>

<div class="col-xxl-12">
    <h1 class="mb-3 fw-bolder">Gerar relatório</h1>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <?= form_open('', ['class' => 'formSend', 'autocomplete' => 'off']) ?>
                    <div class="mb-3">
                        <label for="" class="form-label">Defina uma data</label>
                        <div class="input-group">
                            <input id="dateSearch" type="text" class="form-control border-0 shadow">
                            <button class="input-group-text bg-primary border-primary text-white" id="btnSearchDash">
                                <i class="ri-calendar-2-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="" class="form-label">Forma de pagamento</label>
                        <select class="form-select" name="" id="">
                            <option selected>Todos</option>
                            <option value="pix">Pix</option>
                            <option value="credito">Crédito</option>
                            <option value="debito">Débito</option>
                            <option value="boleto">Boleto</option>
                        </select>
                    </div>

                    <button class="btn btn-info" type="submit">Gerar relátorio</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-body">
                Lista de relatórios gerados
                <table class="table">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>DATA</td>
                            <td>AÇÕES</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#dateSearch", {
            locale: "pt",
            mode: "range", // Ativa o modo de seleção de intervalo
            dateFormat: "d/m/Y", // Define o formato da data
            maxDate: "today", // Permite apenas datas até hoje
            minDate: new Date().fp_incr(-365),
            defaultDate: [new Date().fp_incr(-30), "today"]
        });
    });
</script>
<?= $this->endSection() ?>