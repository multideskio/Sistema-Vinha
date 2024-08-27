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
            log_message('alert', 'Cobrança por Pix não está ativa.');
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

            $userType = session('data')['tipo'];
            $userModel = $this->getUserModel($userType);
            $rowPastor = $userModel->find(session('data')['id_perfil']);

            $sendCieloWhatsApp = new CieloWhatsApp();
            $sendCieloWhatsApp->pixGerado($rowPastor, $response);

            $this->saveTransactionPix($params, $response, $descricao, 'PIX', $desc_longa);

            $cache = service('cache');
            $cache->deleteMatching('transacoes_*');
            $cache->deleteMatching('*_transacoes_*');
            $cache->deleteMatching('*_transacoes');

            return $response;
        } catch (Exception $e) {
            log_message('error', "Erro ao criar cobrança Pix: " . $e->getMessage());
            throw new Exception("Erro ao criar cobrança Pix: " . $e->getMessage());
        }
    }

    public function refundPix($paymentId, $amount)
    {
        try {
            $cielo = $this->data();
            if (!$cielo['active_pix']) {
                log_message('alert', 'Reembolso por Pix não está ativo.');
                throw new Exception('Reembolso por Pix não está ativo.');
            }

            if (empty($paymentId)) {
                log_message('error', 'O ID do pagamento é obrigatório.');
                throw new Exception('O ID do pagamento é obrigatório.');
            }
            if ($amount <= 0) {
                log_message('error', 'O valor do reembolso deve ser maior que zero.');
                throw new Exception('O valor do reembolso deve ser maior que zero.');
            }

            $params = [
                "Amount" => $amount
            ];

            $endPoint = "/1/sales/{$paymentId}/void";
            $response = $this->makeRequest('PUT', $endPoint, $params, 'handleRefundResponse');

            $this->saveTransactionRefund($paymentId, $amount, $response); 

            return $response;
        } catch (Exception $e) {
            log_message('error', "Erro ao processar o reembolso Pix: " . $e->getMessage());
            throw new Exception("Erro ao processar o reembolso Pix: " . $e->getMessage());
        }
    }

    protected function handleRefundResponse(array $response): array
    {
        return $response;
    }

    protected function saveTransactionRefund($paymentId, $amount, $response)
    {
        if ($response['ReasonMessage'] == 'Successful') {
            $data = [
                'id_transacao' => $paymentId,
                'status_text' => 'Reembolsado'
            ];
            $dataId = $this->transactionsModel->where('id_transacao', $paymentId)->select('id')->first();
            $this->transactionsModel->update($dataId['id'], $data);
        }else{
            $data = [
                'id_transacao' => $paymentId,
                'status_text' => 'Reembolso em andamento'
            ];
            $dataId = $this->transactionsModel->where('id_transacao', $paymentId)->select('id')->first();
            $this->transactionsModel->update($dataId['id'], $data);
        }
    }

    private function getUserModel($userType)
    {
        switch ($userType) {
            case 'pastor':
                return new PastoresModel();
            case 'igreja':
                return new IgrejasModel();
            case 'supervisor':
                return new SupervisoresModel();
            case 'gerente':
                return new GerentesModel();
            case 'superadmin':
                return new AdminModel();
            default:
                throw new Exception('Tipo de usuário desconhecido.');
        }
    }
}
