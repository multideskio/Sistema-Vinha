<?php

namespace App\Gateways\Cielo;

use Exception;

class CieloCreditCard extends CieloBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function credito($nome, $valor, $cartao, $securicode, $data, $brand, $desc, $desc_l)
    {
        $cielo = $this->data();

        if (!$cielo['active_credito']) {
            throw new Exception('Cobrança por cartão de crédito não está ativa.');
        }

        $countOrders = $this->transactionsModel->select('id')->orderBy('id', 'DESC')->first();
        $numOrder    = ($countOrders) ? ++$countOrders['id'] : 1 ;
        $params      = [
            "MerchantOrderId" => $numOrder , // Pode ser substituído por um ID de pedido único do seu sistema
            "Customer"        => [
                "Name" => $nome,
            ],
            "Payment" => [
                "Type"         => "CreditCard",
                "Amount"       => $valor, // valor em centavos, 10000 = R$ 100,00
                "Installments" => 1,
                "Capture"      => true,
                "CreditCard"   => [
                    "CardNumber"     => $cartao,
                    "Holder"         => strtoupper($nome),
                    "ExpirationDate" => $data,
                    "SecurityCode"   => $securicode,
                    "Brand"          => $brand,
                ],
            ],
        ];

        return $this->createCreditCardCharge($params, $desc, $desc_l);
    }

    private function createCreditCardCharge($params, $descricao, $desc_l = null)
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);

            $endPoint = '/1/sales/';

            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');

            // Salvar a transação do cartão de crédito
            $this->saveTransactionCreditCard($params, $response, $descricao, 'Crédito', $desc_l);

            // Lista de códigos de erro conhecidos
            $codigosErro = [5, 57, 78, 99, 77, 70];

            // Adiciona código `00` à lista de sucesso
            $codigosSucesso = [4, 6, 00];

            // Verificar se o código de retorno está na lista de erros
            if (in_array($response['Payment']['ReturnCode'], $codigosErro)) {
                $errorMessage = 'Transação não autorizada: ' . $this->getErrorMessage($response['Payment']['ReturnCode']);

                throw new Exception($errorMessage . ' | Resposta: ' . $response['Payment']['ReturnMessage'], 1);
            }

            // Verificar se o código de retorno é um sucesso
            if (!in_array($response['Payment']['ReturnCode'], $codigosSucesso)) {
                $errorMessage = 'Transação não autorizada';

                throw new Exception($errorMessage . ' | Resposta: ' . $response['Payment']['ReturnMessage'], 1);
            }

            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança de cartão de crédito: " . $e->getMessage(), 1);
        }
    }

    /**
     * Obtém a mensagem de erro com base no código de retorno.
     *
     * @param int $codigoRetorno
     * @return string
     */
    private function getErrorMessage($codigoRetorno)
    {
        $mensagensErro = [
            5  => 'Não Autorizada',
            57 => 'Cartão Expirado',
            78 => 'Cartão Bloqueado',
            99 => 'Time Out',
            77 => 'Cartão Cancelado',
            70 => 'Problemas com o Cartão de Crédito',
        ];

        return isset($mensagensErro[$codigoRetorno]) ? $mensagensErro[$codigoRetorno] : 'Erro desconhecido';
    }

    public function refundCreditCard($paymentId, $amount)
    {
        try {
            if (empty($paymentId)) {
                throw new Exception('O ID do pagamento é obrigatório.');
            }

            $params = [
                "Amount" => $amount, // valor em centavos, 10000 = R$ 100,00
            ];

            $endPoint = "/1/sales/{$paymentId}/void";

            $response = $this->makeRequest('PUT', $endPoint, $params, 'handleRefundResponse');

            if ($response['Status'] != 10) { // 10 é o status para 'PaymentCancelled'
                throw new Exception("Reembolso não realizado. Status: {$response['Status']}");
            }

            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao realizar reembolso: " . $e->getMessage());
        }
    }

    protected function handleRefundResponse($response)
    {
        return [
            'paymentId'  => $response['PaymentId'],
            'status'     => $response['Status'],
            'statusName' => $this->getPaymentStatusName($response['Status']),
            'full'       => $response,
        ];
    }
}
