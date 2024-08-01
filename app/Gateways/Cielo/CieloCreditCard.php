<?php

namespace App\Gateways\Cielo;

use Exception;

class CieloCreditCard extends CieloBase
{

    public function credito($nome, $valor, $cartao, $securicode, $data, $brand, $desc, $desc_l)
    {
        $cielo = $this->data();
        if (!$cielo['active_credito']) {
            throw new Exception('Cobrança por cartão de crédito não está ativa.');
        }
        $params = [
            "MerchantOrderId" => time(), // Pode ser substituído por um ID de pedido único do seu sistema
            "Customer" => [
                "Name" => $nome
            ],
            "Payment" => [
                "Type" => "CreditCard",
                "Amount" => $valor, // valor em centavos, 10000 = R$ 100,00
                "Installments" => 1,
                "Capture" => false,
                "CreditCard" => [
                    "CardNumber" => $cartao,
                    "Holder" => strtoupper($nome),
                    "ExpirationDate" => $data,
                    "SecurityCode" => $securicode,
                    "Brand" => $brand
                ]
            ]
        ];
        return $this->createCreditCardCharge($params, $desc, $desc_l);
    }

    private function createCreditCardCharge($params, $descricao, $desc_l = null)
    {
        try {
            // Valida os parâmetros obrigatórios
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);

            $endPoint = '/1/sales/';

            // Faz a requisição ao endpoint
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');

            // Salva a transação do cartão de crédito
            $this->saveTransactionCreditCard($params, $response, $descricao, 'Crédito', $desc_l);

            // Verifica se o código de retorno é um código de erro
            $codigoRetorno = $response['Payment']['ReturnCode'];

            // Lista de códigos de erro conhecidos
            $codigosErro = [
                05 => 'Não Autorizada',
                57 => 'Cartão Expirado',
                78 => 'Cartão Bloqueado',
                99 => 'Time Out',
                77 => 'Cartão Cancelado',
                70 => 'Problemas com o Cartão de Crédito'
            ];

            if (!in_array($codigoRetorno, [4, 6])) {
                $mensagemErro = isset($codigosErro[$codigoRetorno]) ? $codigosErro[$codigoRetorno] : 'Erro desconhecido';
                throw new Exception('Transação não autorizada: ' . $mensagemErro.' </br> Code: '. $response['Payment']['ReturnCode'], 1);
            }

            return $response;
        } catch (Exception $e) {
            // Lança uma exceção com uma mensagem mais específica
            throw new Exception("Erro ao criar cobrança de cartão de crédito <br>" . $e->getMessage());
        }
    }


    public function refundCreditCard($paymentId, $amount)
    {
        try {
            if (empty($paymentId)) {
                throw new Exception('O ID do pagamento é obrigatório.');
            }

            $params = [
                "Amount" => $amount // valor em centavos, 10000 = R$ 100,00
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
            'paymentId' => $response['PaymentId'],
            'status' => $response['Status'],
            'statusName' => $this->getPaymentStatusName($response['Status']),
            'full' => $response
        ];
    }
}
