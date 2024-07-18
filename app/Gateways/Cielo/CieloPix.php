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

            $cache = service('cache');
            $cache->deleteMatching('transacoes_*');
            $cache->deleteMatching('*_transacoes_*');
            $cache->deleteMatching('*_transacoes');

            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança Pix: " . $e->getMessage());
        }
    }


    public function refundPix($paymentId, $amount)
    {
        try {
            // Verifica se o reembolso está ativo
            $cielo = $this->data();
            if (!$cielo['active_pix']) {
                throw new Exception('Reembolso por Pix não está ativo.');
            }

            // Valida os parâmetros
            if (empty($paymentId)) {
                throw new Exception('O ID do pagamento é obrigatório.');
            }
            if ($amount <= 0) {
                throw new Exception('O valor do reembolso deve ser maior que zero.');
            }

            // Configura os parâmetros para a solicitação de reembolso
            $params = [
                "Amount" => $amount
            ];

            // Define o endpoint para o reembolso
            $endPoint = "/1/sales/{$paymentId}/void";

            // Faz a requisição para a API de reembolso
            $response = $this->makeRequest('PUT', $endPoint, $params, 'handleRefundResponse');

            // Registra a transação de reembolso no banco de dados
            $this->saveTransactionRefund($paymentId, $amount, $response);

            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao processar o reembolso Pix: " . $e->getMessage());
        }
    }

    protected function handleRefundResponse(array $response): array
    {
        return $response;
    }

    protected function saveTransactionRefund($paymentId, $amount, $response)
    {
        $data = [
            'id_transacao' => $paymentId,
            'valor' => centavosParaReais($amount),
            'log' => json_encode($response),
            'status_text' => 'Reembolsado'
        ];

        $dataId = $this->transactionsModel->where('id_transacao', $paymentId)->select('id')->first();

        // Atualiza a transação existente com o status de reembolso
        $this->transactionsModel->update($dataId['id'], $data);
    }
}
