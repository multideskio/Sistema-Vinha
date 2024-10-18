<?php if (session()->getFlashdata('error')) : ?>
<div class="alert alert-danger text-center">
    <b><?= session()->getFlashdata('error'); ?></b>
</div>
<?php endif; ?>
<?= form_open("api/v1/authenticate?redirect=" . (isset($_GET['redirect']) ? $_GET['redirect'] : null)) ?>
<div class="mb-3">
    <label for="username" class="form-label">Informe seu e-mail</label>
    <input type="email" class="form-control" id="username" placeholder="exemplo@email.com" name="email" required>
</div>
<div class="mb-3">
    <div class="float-end">
        <a href="/recuperacao" class="text-muted">Recupere sua senha aqui!</a>
    </div>
    <label class="form-label" for="password-input">Senha</label>
    <div class="position-relative auth-pass-inputgroup mb-3">
        <input type="password" class="form-control pe-5 password-input" placeholder="Digite sua senha" name="senha"
            id="password-input" required autocomplete="off">
        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
            type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
    </div>
</div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
    <label class="form-check-label" for="auth-remember-check">Manter
        conectado</label>
</div>
<div class="mt-4">
    <button class="btn btn-dark w-100" type="submit">Entrar</button>
</div>
<p class="text-center w-100 mt-2 mb-2">Ou</p>
<div class="mt-4 text-center">
    <a href="/nova-conta" class="btn btn-primary w-100">Criar uma
        nova conta</a>
</div>
<!--
                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title"><?= lang('Paginas.login.opcoes') ?></h5>
                                        </div>
                                        <div>
                                            <a href="<?= site_url('api/v1/google') ?>" class="btn btn-danger waves-effect waves-light">
                                                <i class="ri-google-fill fs-16"></i>oogle
                                            </a>
                                        </div>
                                    </div> -->
</form>