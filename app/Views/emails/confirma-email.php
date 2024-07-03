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
                                        <p style="font-family: 'Roboto', sans-serif; font-weight: 500;">Olá <?= $nome ?></p>
                                        <p>Por favor, verifique seu e-mail.</p>
                                        <p style="margin-bottom: 0; text-align: center;">Valide seu endereço de e-mail para começar a usar nosso sistema.</p>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 22px; text-align: center;" valign="top">
                                        <a href="<?= site_url("confirma/{$token}") ?>" itemprop="url" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: .8125rem; color: #FFF; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #405189; margin: 0; border-color: #405189; border-style: solid; border-width: 1px; padding: .5rem .9rem;">Confirme seu e-mail clicando aqui!</a>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="color: #878a99; text-align: center;font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0; padding-top: 5px" valign="top">
                                        <p style="margin-bottom: 10px;">Ou Confirme usando este link:</p>
                                        <a class="color: #405189;" href="<?= site_url("confirma/{$token}") ?>" target="_blank"><?= site_url("confirma/{$token}") ?></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div style="text-align: center; margin: 25px auto 0px auto;font-family: 'Roboto', sans-serif;">
                    <h4 style="font-weight: 500; line-height: 1.5;font-family: 'Roboto', sans-serif;">Precisa de ajuda ?</h4>
                    <p style="color: #878a99; line-height: 1.5;">Por favor, envie comentários ou informações sobre bugs para <a href="mailto:multidesk.io@gmail.com" style="font-weight: 500;">multidesk.io@gmail.com</a></p>
                    <p style="font-family: 'Roboto', sans-serif; font-size: 14px;color: #98a6ad; margin: 0px;">2024 Vinha. Design & Develop by Multidesk.io</p>
                </div>
            </div>
        </td>
    </tr>
</table>