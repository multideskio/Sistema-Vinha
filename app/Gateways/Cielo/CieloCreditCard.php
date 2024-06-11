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
                "Capture" => true,
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
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $endPoint = '/1/sales/';
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');

            $this->saveTransactionCredit($params, $response, $descricao, 'Crédito', $desc_l);

            if(!$response['Payment']['ReturnMessage'] == 'Operation Successful'){
                throw new Exception('Tranzação não autorizada', 1);
            };

            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança de cartão de crédito: " . $e->getMessage());
        }
    }

    
}
