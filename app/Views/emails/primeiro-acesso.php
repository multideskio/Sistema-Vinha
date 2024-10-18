<table width="100%" cellpadding="0" cellspacing="0" border="0"
    style="font-family: 'Roboto', sans-serif; background-color: #f9f9f9; padding: 20px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" border="0"
                style="background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 5px;">
                <tr>
                    <td align="center" style="padding: 20px 0;">
                        <img src="https://d2mlkbg44iajgz.cloudfront.net/logo-dark.png" alt="Logo" height="23"
                            style="display: block;">
                    </td>
                </tr>
                <tr>
                    <td style="color: #495057; font-size: 16px; padding: 20px; text-align: left;">
                        <p>Olá <?= $nome ?>, seja bem-vindo!</p>
                        <p>Você foi cadastrado no sistema <b>Associados Vinha</b>.</p>
                        <p>Para cadastrar sua senha, clique no botão abaixo.</p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px;">
                        <a href="<?= site_url("/") ?>"
                            style="background-color: #405189; color: #FFFFFF; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; display: inline-block;">Acessar
                            o sistema</a>
                    </td>
                </tr>
                <tr>
                    <td style="color: #878a99; text-align: center; font-size: 14px; padding: 20px;">
                        <p>Redefinir senha:</p>
                        <a href="<?= site_url("primeiro-acesso/{$token}") ?>"
                            style="color: #405189; text-decoration: none;"
                            target="_blank"><?= site_url("primeiro-acesso/{$token}") ?></a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px;">
                        <?= $this->include('emails/includes/footer.php') ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>