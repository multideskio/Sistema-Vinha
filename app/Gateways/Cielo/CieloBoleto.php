<?php

namespace App\Gateways\Cielo;

use Exception;

class CieloBoleto extends CieloBase
{
    public function boleto($nome, $valor, $days, $cpf, $tipo, $empresa, $instrucao)
    {
        $cielo = $this->data();
        if (!$cielo['active_boletos']) {
            throw new Exception('Cobrança por boleto não está ativa.');
        }

        $dataVencimento = $this->calculateDueDate($days);

        $params = [
            "MerchantOrderId" => time(),
            "Customer" => [
                "Name" => $nome,
                "Identity" => $cpf,
                "Address" =>[
                    "Street" => "Avenida Marechal Câmara",
                    "Number" => "160",
                    "Complement" => "Sala 934",
                    "ZipCode"  => "22750012",
                    "District" => "Centro",
                    "City" => "Rio de Janeiro",
                    "State" => "RJ",
                    "Country" => "BRA"
                ],
            ],
            "Payment" => [
                "Type" => "Boleto",
                "Provider" => "bradesco2",
                "Amount" => $valor,
                "BoletoNumber" => time(),
                "Assignor" => $empresa,
                "Demonstrative" => $tipo,
                "ExpirationDate" => $dataVencimento,
                "Identification" => $cpf,
                "Instructions" => $instrucao
            ],
        ];

        return $this->createBoletoCharge($params);
    }

    private function calculateDueDate($days)
    {
        $currentDate = new \DateTime();
        $currentDate->modify("+$days days");
        return $currentDate->format('Y-m-d');
    }

    private function createBoletoCharge($params)
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $params['Payment']['Type'] = 'Boleto';
            $endPoint = '/1/sales/';
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');
            $this->saveTransaction($params, $response);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança de boleto: " . $e->getMessage());
        }
    }

    private function saveTransaction(array $params, array $response)
    {
        if (isset($response['Payment']['Status']) && $response['Payment']['Status'] == 'Approved') {
            $data = [
                'id_transacao' => $response['Payment']['Tid'],
                'valor' => $params['Payment']['Amount'],
                'log' => json_encode($response),
                'status_text' => 'Aprovado'
            ];

            $this->transactionsModel->insert($data);
        } else {
            $logger = service('logger');
            // Caso o status não seja 'Aprovado', pode-se tratar o erro aqui
            $logger->warning('Cobrança de cartão de débito não aprovada.', ['response' => $response]);
        }
    }
}
