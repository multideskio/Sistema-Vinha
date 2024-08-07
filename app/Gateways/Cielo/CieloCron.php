<?php

namespace App\Gateways\Cielo;

use Exception;
use CodeIgniter\I18n\Time;

class CieloCron extends CieloBase
{
    // Constantes para status de transação
    private const STATUS_PAID = 'Pago';
    private const STATUS_REFUNDED = 'Reembolsado';
    private const STATUS_CANCELED = 'Cancelado';
    private const STATUS_PENDING = 'Pendente';

    // Método principal para verificar e atualizar o status das transações
    public function verifyTransaction()
    {
        $transactions = $this->getRelevantTransactions(); // Busca transações relevantes
        $now = Time::now(); // Obtém a hora atual

        
        foreach ($transactions as $transaction) {
            try {
                $createdAt = Time::parse($transaction['created_at']); // Parseia a data de criação da transação
                $hoursDifference = $createdAt->difference($now)->getHours(); // Calcula a diferença em horas

                if ($hoursDifference < 2) {
                    $dataReturn = $this->checkPaymentStatus($transaction['id_transacao']); // Verifica o status do pagamento

                    // Verifica se a resposta não é um erro antes de atualizar o status da transação
                    if ($this->isValidDataReturn($dataReturn)) {
                        $this->updateTransactionStatus($transaction, $dataReturn);
                    } else {
                        // Loga a mensagem de erro se a resposta for um erro
                        log_message('error', "Falha ao atualizar status da transação ID {$transaction['id']}: " . $dataReturn['message']);
                    }
                } else {
                    // Verifica se deve cancelar a transação baseada no status
                    if ($this->shouldCancelTransaction($transaction['status_text'])) {
                        $this->cancelTransaction($transaction);
                    }
                }
            } catch (Exception $e) {
                // Loga o erro e continua com a próxima transação
                log_message('error', "Erro ao processar transação ID {$transaction['id']}: " . $e->getMessage());
                continue;
            }
        }

        // Verifica transações pagas para possível reembolso
        $this->checkAndUpdateRefundStatus();

        $cache = service('cache');
        $cache->deleteMatching('transacoes_*');
        $cache->deleteMatching('*_transacoes_*');
        $cache->deleteMatching('*_transacoes');

        return ['message' => 'Verificação de transações completa.'];
    }




    // Busca transações relevantes para verificação
    private function getRelevantTransactions(): array
    {
        $threeHoursAgo = Time::now()->subHours(3);

        log_message('info', 'Confirmando time: '.$threeHoursAgo);
        
        $data = $this->transactionsModel
        ->where('gateway', 'cielo')
        ->where('status_text !=', self::STATUS_CANCELED)
        ->where('created_at >=', $threeHoursAgo->toDateTimeString()) // Filtra transações criadas nas últimas 3 horas
        ->orderBy('id', 'DESC')
        ->findAll();

        if(!count($data)){
            return [];
        }
        //log_message('info', 'Resposta: '.json_encode($data));
        return $data;
    }




    // Atualiza o status da transação com base na resposta do pagamento
    private function updateTransactionStatus($transaction, $dataReturn)
    {
        $updateData = [
            'status' => 0, // Status padrão
            'status_text' => '',
            'tipo_pagamento' => $dataReturn['full']['Payment']['Type'],
            'log' => json_encode($dataReturn)
        ];

        // Atualiza os dados da transação com base no status retornado
        switch ($dataReturn['statusName']) {
            case 'Authorized':
                $updateData['status'] = 1;
                $updateData['status_text'] = self::STATUS_PAID;
                $updateData['data_pagamento'] = $dataReturn['full']['Payment']['CapturedDate'];
                break;
            case 'PaymentConfirmed':
                $updateData['status'] = 1;
                $updateData['status_text'] = self::STATUS_PAID;
                break;
            case 'Refunded':
                $updateData['status_text'] = self::STATUS_REFUNDED;
                $updateData['data_pagamento'] = $dataReturn['full']['Payment']['CapturedDate'];
                break;
            case 'Voided':
                $updateData['status_text'] = 'Cancelado pelo admin';
                break;
            case 'Pending':
            case 'NotFinished':
                $updateData['status_text'] = self::STATUS_PENDING;
                break;
            case 'Denied':
            case 'Aborted':
                $updateData['status_text'] = self::STATUS_CANCELED;
                break;
            default:
                $updateData['status_text'] = self::STATUS_CANCELED;
                break;
        }

        $this->transactionsModel->update($transaction['id'], $updateData);
    }

    // Verifica se a transação deve ser cancelada com base no texto do status
    private function shouldCancelTransaction($statusText)
    {
        $nonCancelableStatuses = [self::STATUS_PAID, self::STATUS_REFUNDED, 'Devolvido'];
        return !in_array($statusText, $nonCancelableStatuses);
    }

    // Cancela a transação
    private function cancelTransaction($transaction)
    {
        $dataUpdate = [
            'status' => 0,
            'status_text' => self::STATUS_CANCELED
        ];
        $this->transactionsModel->update($transaction['id'], $dataUpdate);
    }

    // Verifica e atualiza o status das transações pagas que foram reembolsadas
    private function checkAndUpdateRefundStatus()
    {
        $threeHoursAgo = Time::now()->subHours(3);

        $paidTransactions = $this->transactionsModel
            ->where('gateway', 'cielo')
            ->where('created_at >=', $threeHoursAgo->toDateTimeString())
            ->where('status_text', self::STATUS_PAID)
            ->findAll();

        foreach ($paidTransactions as $transaction) {
            try {
                $dataReturn = $this->checkPaymentStatus($transaction['id_transacao']);
                if ($this->isValidDataReturn($dataReturn) && $dataReturn['statusName'] === 'Refunded') {
                    $this->updateTransactionStatus($transaction, $dataReturn);
                }
            } catch (Exception $e) {
                log_message('error', "Erro ao verificar reembolso da transação ID {$transaction['id']}: " . $e->getMessage());
                continue;
            }
        }
    }

    // Verifica se a resposta de checkPaymentStatus é válida
    private function isValidDataReturn($dataReturn)
    {
        return isset($dataReturn['status']) && isset($dataReturn['statusName']) && isset($dataReturn['full']['Payment']);
    }
}
