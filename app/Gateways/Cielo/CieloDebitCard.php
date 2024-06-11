<?php namespace App\Gateways\Cielo;

use Exception;

/**
 * Class CieloDebitCard
 *
 * Esta classe é responsável por processar pagamentos via cartão de débito utilizando a API da Cielo.
 */
class CieloDebitCard extends CieloBase {

    /**
     * Cria uma cobrança no cartão de débito.
     *
     * @param string $nome       Nome do cliente.
     * @param int $valor         Valor da cobrança em centavos.
     * @param string $cartao     Número do cartão de débito.
     * @param string $securicode Código de segurança do cartão.
     * @param string $data       Data de expiração do cartão (MM/AA).
     * @param string $brand      Bandeira do cartão (padrão: 'Visa').
     * @param string $urlRetorno URL de retorno para a autenticação.
     * 
     * @return array             Resposta da API da Cielo.
     * @throws Exception         Se a cobrança por cartão de débito não estiver ativa ou se ocorrer um erro na requisição.
     */
    public function debito( $nome,  $valor,  $cartao,  $securicode,  $data,  $brand = 'Visa',  $urlRetorno): array
    {
        $cielo = $this->data();
        if (!$cielo['active_debito']) {
            throw new Exception('Cobrança por cartão de débito não está ativa.');
        }

        $params = [
            "MerchantOrderId" => time(),
            "Customer" => [
                "Name" => $nome
            ],
            "Payment" => [
                "Type" => "DebitCard",
                "Amount" => $valor,
                "ReturnUrl" => $urlRetorno,
                "Authenticate" => true,
                "DebitCard" => [
                    "CardNumber" => $cartao,
                    "Holder" => $nome,
                    "ExpirationDate" => $data,
                    "SecurityCode" => $securicode,
                    "Brand" => $brand
                ]
            ]
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
            $endPoint = '/1/sales/';
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');
            $this->saveTransaction($params, $response);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança de cartão de débito: " . $e->getMessage());
        }
    }
}
