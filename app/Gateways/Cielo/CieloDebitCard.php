<?php

namespace App\Gateways\Cielo;

// Certifique-se de que o modelo de transações está importado corretamente
use Exception;

/**
 * Class CieloDebitCard
 *
 * Esta classe é responsável por processar pagamentos via cartão de débito utilizando a API da Cielo.
 */
class CieloDebitCard extends CieloBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function debito($nome, $valor, $cartao, $securicode, $data, $brand = 'Visa', $urlRetorno): array
    {
        $cielo = $this->data();

        if (!$cielo['active_debito']) {
            throw new Exception('Cobrança por cartão de débito não está ativa.');
        }

        $params = [
            "MerchantOrderId" => time(),
            "Customer"        => [
                "Name" => $nome,
            ],
            "Payment" => [
                "Type"         => "DebitCard",
                "Amount"       => $valor,
                "ReturnUrl"    => $urlRetorno,
                "Authenticate" => true,
                "DebitCard"    => [
                    "CardNumber"     => $cartao,
                    "Holder"         => $nome,
                    "ExpirationDate" => $data,
                    "SecurityCode"   => $securicode,
                    "Brand"          => $brand,
                ],
            ],
        ];

        return $this->createDebitCardCharge($params);
    }

    /**
     * Faz a requisição para criar uma cobrança no cartão de débito.
     *
     * @param array $params      Parâmetros para a requisição.
     *
     * @return array             Resposta da API da Cielo.
     * @throws Exception         Se ocorrer um erro na criação da cobrança.
     */
    private function createDebitCardCharge(array $params): array
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $params['Payment']['Authenticate'] = true;
            $endPoint                          = '/1/sales/';
            $response                          = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');

            $this->saveTransaction($params, $response);

            // Limpeza de cache
            $cache = service('cache');
            $cache->deleteMatching('transacoes_*');
            $cache->deleteMatching('*_transacoes_*');
            $cache->deleteMatching('*_transacoes');

            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança de cartão de débito: " . $e->getMessage());
        }
    }

    /**
     * Salva a transação de cartão de débito no banco de dados.
     *
     * @param array $params      Parâmetros da transação.
     * @param array $response    Resposta da API da Cielo.
     */
    private function saveTransaction(array $params, array $response)
    {
        if (isset($response['Payment']['Status']) && $response['Payment']['Status'] == 'Approved') {
            $data = [
                'id_transacao' => $response['Payment']['Tid'],
                'valor'        => $params['Payment']['Amount'],
                'log'          => json_encode($response),
                'status_text'  => 'Aprovado',
            ];

            $this->transactionsModel->insert($data);
        } else {
            $logger = service('logger');
            // Caso o status não seja 'Aprovado', pode-se tratar o erro aqui
            $logger->warning('Cobrança de cartão de débito não aprovada.', ['response' => $response]);
        }
    }
}
