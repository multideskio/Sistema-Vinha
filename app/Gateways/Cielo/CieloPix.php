<?php

namespace App\Gateways\Cielo;

use App\Models\PastoresModel;
use Exception;

class CieloPix extends CieloBase
{
    public function pix($nome, $valor, $descricao)
    {
        $cielo = $this->data();
        if (!$cielo['active_pix']) {
            throw new Exception('Cobrança por Pix não está ativa.');
        }
        $params = [
            "MerchantOrderId" => time(),
            "Customer" => [
                "Name" => $nome
            ],
            "Payment" => [
                "Type" => "Pix",
                "Amount" => $valor
            ]
        ];
        return $this->createPixCharge($params, $descricao);
    }

    private function createPixCharge($params, $descricao)
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $params['Payment']['Type'] = 'Pix';
            $endPoint = '/1/sales/';
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');
            if (session('data')['tipo'] == 'pastor') {
                $builderPerfil = new PastoresModel();
                $rowPastor = $builderPerfil->find(session('data')['id_perfil']);
                $sendCieloWhatsApp = new CieloWhatsApp;
                $sendCieloWhatsApp->pixGerado($rowPastor, $response);
            }
            $this->saveTransactionPix($params, $response, $descricao, 'PIX');
            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança Pix: " . $e->getMessage());
        }
    }
}
