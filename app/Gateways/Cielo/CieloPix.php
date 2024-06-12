<?php

namespace App\Gateways\Cielo;

use App\Models\AdminModel;
use App\Models\GerentesModel;
use App\Models\IgrejasModel;
use App\Models\PastoresModel;
use App\Models\SupervisoresModel;
use Exception;

class CieloPix extends CieloBase
{
    public function pix($nome, $valor, $descricao, $desc_longa)
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
        return $this->createPixCharge($params, $descricao, $desc_longa);
    }

    private function createPixCharge($params, $descricao, $desc_longa)
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            
            $params['Payment']['Type'] = 'Pix';
            
            $endPoint = '/1/sales/';

            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');

            if (session('data')['tipo'] == 'pastor') {
                $builderPerfil = new PastoresModel();
                $rowPastor = $builderPerfil->find(session('data')['id_perfil']);
            }

            if (session('data')['tipo'] == 'igreja') {
                $builderPerfil = new IgrejasModel();
                $rowPastor = $builderPerfil->find(session('data')['id_perfil']);
            }

            if (session('data')['tipo'] == 'supervisor') {
                $builderPerfil = new SupervisoresModel();
                $rowPastor = $builderPerfil->find(session('data')['id_perfil']);
            }

            if (session('data')['tipo'] == 'gerente') {
                $builderPerfil = new GerentesModel();
                $rowPastor = $builderPerfil->find(session('data')['id_perfil']);
            }

            if (session('data')['tipo'] == 'superadmin') {
                $builderPerfil = new AdminModel();
                $rowPastor = $builderPerfil->find(session('data')['id_perfil']);
            }

            $sendCieloWhatsApp = new CieloWhatsApp;
            
            $sendCieloWhatsApp->pixGerado($rowPastor, $response);
            
            $this->saveTransactionPix($params, $response, $descricao, 'PIX', $desc_longa);
            
            return $response;

        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança Pix: " . $e->getMessage());
        }
    }
}
