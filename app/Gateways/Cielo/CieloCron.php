<?php

namespace App\Gateways\Cielo;

use Exception;
use CodeIgniter\I18n\Time;

class CieloCron extends CieloBase
{
    public function verifyTransaction()
    {
        $transactions = $this->getRelevantTransactions();
        $now = Time::now();

        foreach ($transactions as $transaction) {
            try {
                $createdAt = Time::parse($transaction['created_at']);
                $hoursDifference = $createdAt->difference($now)->getHours();

                if ($hoursDifference < 2) {
                    $dataReturn = $this->checkPaymentStatus($transaction['id_transacao']);
                    
                    // Verificar se a resposta é um erro e continuar sem lançar exceção
                    if ($dataReturn['status'] !== 'error') {
                        $this->updateTransactionStatus($transaction, $dataReturn);
                    } else {
                        log_message('error', "Falha ao atualizar status da transação ID {$transaction['id']}: " . $dataReturn['message']);
                    }
                } else {
                    if ($this->shouldCancelTransaction($transaction['status_text'])) {
                        $this->cancelTransaction($transaction);
                    }
                }
            } catch (Exception $e) {
                // Logar o erro e continuar com a próxima transação
                log_message('error', "Erro ao processar transação ID {$transaction['id']}: " . $e->getMessage());
                continue;
            }
        }

        return ['message' => 'Verificação de transações completa.'];
    }

    private function getRelevantTransactions()
    {
        return $this->transactionsModel
            ->where('gateway', 'cielo')
            ->where('status_text !=', 'Cancelado')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    private function updateTransactionStatus($transaction, $dataReturn)
    {
        $updateData = [
            'status' => 0, // Default status
            'status_text' => '',
            'tipo_pagamento' => $dataReturn['full']['Payment']['Type'],
            'log' => json_encode($dataReturn)
        ];

        switch ($dataReturn['statusName']) {
            case 'Authorized':
                $updateData['status'] = 1;
                $updateData['status_text'] = 'Pago';
                $updateData['data_pagamento'] = $dataReturn['full']['Payment']['CapturedDate'];
                break;
            case 'PaymentConfirmed':
                $updateData['status'] = 1;
                $updateData['status_text'] = 'Pago';
                break;
            case 'Refunded':
                $updateData['status_text'] = 'Reembolsado';
                $updateData['data_pagamento'] = $dataReturn['full']['Payment']['CapturedDate'];
                break;
            case 'Voided':
                $updateData['status_text'] = 'Cancelado pelo admin';
                break;
            case 'Pending':
            case 'NotFinished':
                $updateData['status_text'] = 'Pendente';
                break;
            case 'Denied':
            case 'Aborted':
                $updateData['status_text'] = 'Cancelado';
                break;
            default:
                $updateData['status_text'] = 'Cancelado';
                break;
        }

        $this->transactionsModel->update($transaction['id'], $updateData);
    }

    private function shouldCancelTransaction($statusText)
    {
        $nonCancelableStatuses = ['Pago', 'Reembolsado', 'Devolvido'];
        return !in_array($statusText, $nonCancelableStatuses);
    }

    private function cancelTransaction($transaction)
    {
        $dataUpdate = [
            'status' => 0,
            'status_text' => 'Cancelado'
        ];
        $this->transactionsModel->update($transaction['id'], $dataUpdate);
    }
}
