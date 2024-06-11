<?php

namespace App\Gateways\Cielo;

use Exception;
use CodeIgniter\I18n\Time;

class CieloCron extends CieloBase
{
    public function verifyTransaction()
    {
        $transactionsData = [];

        // Obter todas as transações com 'gateway' => 'cielo' e 'status_text' diferente de 'Cancelado'
        $transactions = $this->transactionsModel
            ->where([
                'gateway' => 'cielo',
                //'status_text' => 'Cancelado',
                'id' => 45
            ])
            ->orderBy('id', 'DESC')
            ->findAll();

        // Obter a hora atual
        $now = Time::now();

        foreach ($transactions as $transaction) {
            // Obter a data de criação da transação
            $createdAt = Time::parse($transaction['created_at']);
            // Calcular a diferença em horas entre a hora atual e a hora de criação
            $hoursDifference = $createdAt->difference($now)->getHours();

            // Armazenar a diferença de horas para fins de debug
            //$transactionsData[] = ['diff' => $hoursDifference];

            // Verificar se a diferença é menor que 2 horas
            if ($hoursDifference < 999) {
                // Verificar o status do pagamento
                $dataReturn = $this->checkPaymentStatus($transaction['id_transacao']);

                // Atualizar o status da transação com base no status retornado
                if ($dataReturn['statusName'] == 'Authorized') {
                    $transactionsData[] = ['ID' => $transaction['id'], "StatusConfirmed" => $dataReturn['statusName']];
                    $dataUpdate = [
                        'status' => 1,
                        'status_text' => 'Pago',
                        'data_pagamento' => $dataReturn['full']['Payment']['CapturedDate'],
                        'tipo_pagamento' => $dataReturn['full']['Payment']['Type'],
                        'log' => json_encode($dataReturn)
                    ];
                    $this->transactionsModel->update($transaction['id'], $dataUpdate);
                } elseif ($dataReturn['statusName'] == 'Refunded') {
                    $transactionsData[] = ['ID' => $transaction['id'], "StatusRefunded" => $dataReturn['statusName']];
                    $dataUpdate = [
                        'status' => 0,
                        'status_text' => 'Reembolsado',
                        'data_pagamento' => $dataReturn['full']['Payment']['CapturedDate'],
                        'tipo_pagamento' => $dataReturn['full']['Payment']['Type'],
                        'log' => json_encode($dataReturn)
                    ];
                    $this->transactionsModel->update($transaction['id'], $dataUpdate);
                } elseif($dataReturn['statusName'] == 'PaymentCancelled'){
                    $transactionsData[] = ['ID' => $transaction['id'], "StatusRefunded" => $dataReturn['statusName']];
                    $dataUpdate = [
                        'status' => 0,
                        'status_text' => 'Cancelado pelo admin',
                        //'data_pagamento' => $dataReturn['full']['Payment']['CapturedDate'],
                        'tipo_pagamento' => $dataReturn['full']['Payment']['Type'],
                        'log' => json_encode($dataReturn)
                    ];
                    $this->transactionsModel->update($transaction['id'], $dataUpdate);
                }else {
                    $transactionsData[] = ['ID' => $transaction['id'], "StatusPendete" => $dataReturn['statusName']];
                    $dataUpdate = [
                        'status' => 0,
                        'status_text' => 'Pendente',
                        'tipo_pagamento' => $dataReturn['full']['Payment']['Type'],
                        'log' => json_encode($dataReturn)
                    ];
                    $this->transactionsModel->update($transaction['id'], $dataUpdate);
                }
            } else {
                if ($transaction['status']) {
                    // Se a transação foi criada há mais de 2 horas, marcar como 'Cancelado'
                    $transactionsData[] = ['ID' => $transaction['id'], "StatusCancelado" => ''];
                    $dataUpdate = [
                        'status' => 0,
                        'status_text' => 'Cancelado'
                    ];
                    $this->transactionsModel->update($transaction['id'], $dataUpdate);
                }
            }
        }

        return $transactionsData;
    }
}
