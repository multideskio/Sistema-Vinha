<table class="body-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: transparent; margin: 0;">
    <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
        <td class="container" width="600" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
            <div class="content" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border-style: solid; border-width: 1px; border-color: rgba(30,32,37,.06); ">
                    <tr style="font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0;">
                        <td class="content-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; box-shadow: 0 3px 15px rgba(30,32,37,.06); ;border-radius: 7px; background-color: #fff;" valign="top">
                            <meta itemprop="name" content="Confirm Email" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                        <div style="text-align: center;margin-bottom: 15px;">
                                            <img src="https://d2mlkbg44iajgz.cloudfront.net/logo-dark.png" alt="" height="23">
                                        </div>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; margin: 0;">
                                    <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; vertical-align: top; margin: 0; padding: 0 0 15px;" valign="top">
                                        <p style="font-family: 'Roboto', sans-serif; font-weight: 500;">Olá...</p>
                                        <p>Para recuperar sua senha, clique no botão a baixo.</p>
                                        <p>Caso não tenha pedido essa recuperação de senha, desconsidere essa mensagem.</p>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 22px; text-align: center;" valign="top">
                                        <a href="<?= site_url("recupera/{$token}") ?>" itemprop="url" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: .8125rem; color: #FFF; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #405189; margin: 0; border-color: #405189; border-style: solid; border-width: 1px; padding: .5rem .9rem;">Defina uma nova senha clicando aqui!</a>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="color: #878a99; text-align: center;font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0; padding-top: 5px" valign="top">
                                        <p style="margin-bottom: 10px;">Ou recupere sua conta usando este link:</p>
                                        <a class="color: #405189;" href="<?= site_url("recupera/{$token}") ?>" target="_blank"><?= site_url("recupera/{$token}") ?></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <?= $this->include('emails/includes/footer.php') ?>
            </div>
        </td>
    </tr>
</table>