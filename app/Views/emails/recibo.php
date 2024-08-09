<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Pagamento</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #fff;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background-color: #000; color: #fff; height: 30px;">
                <td colspan="2" style="padding: 10px; text-align: center;">
                    <h2 style="margin: 0;">Comprovante de Pagamento</h2>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px; font-weight: bold;">ID do Pagamento:</td>
                <td style="padding: 10px;"><?= $response['Payment']['PaymentId'] ?></td>
            </tr>
            <tr style="background-color: #f9f9f9;">
                <td style="padding: 10px; font-weight: bold;">Data do Pagamento:</td>
                <td style="padding: 10px;"><?= $response['Payment']['CapturedDate'] ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; font-weight: bold;">Status:</td>
                <td style="padding: 10px;">Pago</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 10px; text-align: center;">
                    Obrigado pelo seu pagamento. <br>
                    Caso tenha alguma dúvida, entre em contato conosco.
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 10px; text-align: center;">
                    Atenciosamente,<br>
                    <strong>Sua Empresa</strong>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
