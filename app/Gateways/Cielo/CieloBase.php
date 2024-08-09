<?php

namespace App\Gateways\Cielo;

use App\Libraries\EmailsLibraries;
use App\Models\AdminModel;
use App\Models\GatewaysModel;
use App\Models\GerentesModel;
use App\Models\IgrejasModel;
use App\Models\PastoresModel;
use App\Models\SupervisoresModel;
use App\Models\TransacoesModel;
use App\Models\UsuariosModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;

/**
 * Class CieloBase
 *
 * Base class for Cielo gateway integration.
 */
class CieloBase
{
    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $merchantKey;

    /**
     * @var string
     */
    protected $apiUrlQuery;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var GatewaysModel
     */
    protected $modelGateway;

    /**
     * @var TransacoesModel
     */
    protected $transactionsModel;

    /**
     * @var Client
     */
    protected $client;

    /**
     * CieloBase constructor.
     */
    public function __construct()
    {
        $this->modelGateway = new GatewaysModel();
        $this->transactionsModel = new TransacoesModel();
        $this->client = new Client();
        $this->initializeCieloConfig();
        helper('auxiliar');
    }

    /**
     * Initializes the Cielo configuration.
     *
     * @throws Exception
     */
    private function initializeCieloConfig(): void
    {
        $cielo = $this->data();

        if ($cielo['status'] == 1) {
            $this->merchantId = $cielo['merchantid_pro'];
            $this->merchantKey = $cielo['merchantkey_pro'];
            $this->apiUrlQuery = 'https://apiquery.cieloecommerce.cielo.com.br';
            $this->apiUrl = 'https://api.cieloecommerce.cielo.com.br';
        } else {
            $this->merchantId = $cielo['merchantid_dev'];
            $this->merchantKey = $cielo['merchantkey_dev'];
            $this->apiUrlQuery = 'https://apiquerysandbox.cieloecommerce.cielo.com.br';
            $this->apiUrl = 'https://apisandbox.cieloecommerce.cielo.com.br';
        }

        $this->headers = [
            'Content-Type' => 'application/json',
            'MerchantId'   => $this->merchantId,
            'MerchantKey'  => $this->merchantKey,
        ];
    }

    /**
     * Retrieves Cielo configuration data.
     *
     * @return array
     * @throws Exception
     */
    protected function data(): array
    {
        $row = $this->modelGateway->where('tipo', 'cielo')->findAll();

        if (count($row)) {
            return [
                'status' => $row[0]['status'],
                'merchantid_dev' => $row[0]['merchantid_dev'],
                'merchantkey_dev' => $row[0]['merchantkey_dev'],
                'merchantid_pro' => $row[0]['merchantid_pro'],
                'merchantkey_pro' => $row[0]['merchantkey_pro'],
                'active_pix' => $row[0]['active_pix'],
                'active_credito' => $row[0]['active_credito'],
                'active_debito' => $row[0]['active_debito'],
                'active_boletos' => $row[0]['active_boletos']
            ];
        }

        throw new Exception('Nenhuma configuração da Cielo encontrada.');
    }

    /**
     * Makes a request to the Cielo API.
     *
     * @param string $method
     * @param string $endPoint
     * @param array $params
     * @param string $responseHandler
     * @param bool $useQueryUrl
     * @return mixed
     * @throws Exception
     */
    protected function makeRequest(string $method, string $endPoint, array $params, string $responseHandler, bool $useQueryUrl = false)
    {
        try {
            
            $url = ($useQueryUrl ? $this->apiUrlQuery : $this->apiUrl) . $endPoint;
            
            $options = [
                'headers' => $this->headers,
                'json' => $params
            ];
            
            $response = $this->client->request($method, $url, $options);
            $body = json_decode($response->getBody(), true);
            
            if (isset($body['Code']) && isset($body['Message'])) {
                throw new Exception("Erro {$body['Code']}: {$body['Message']}");
            }

            return $this->$responseHandler($body);

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response ? $response->getBody()->getContents() : 'Sem resposta';
            throw new Exception("Erro na requisição: {$responseBodyAsString}");
        }
    }

    /**
     * Validates required parameters.
     *
     * @param array $params
     * @param array $requiredFields
     * @throws Exception
     */
    protected function validateParams(array $params, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                throw new Exception("O campo {$field} é obrigatório.");
            }
        }
    }

    /**
     * Handles the create charge response.
     *
     * @param array $response
     * @return array
     */
    protected function handleCreateChargeResponse(array $response): array
    {
        return $response;
    }

    /**
     * Handles the check payment status response.
     *
     * @param array $response
     * @return array
     */
    protected function handleCheckPaymentStatusResponse(array $response): array
    {
        if ($response['Payment']['Status'] === 2) {
            try {
                $rows = $this->transactionsModel->where('id_transacao', $response['Payment']['PaymentId'])->findAll();
                if (count($rows)) {
                    $row = $rows[0];
                    $status = $this->transactionsModel->update($row['id'], [
                        'data_pagamento' => $response['Payment']['CapturedDate'],
                        'status' => 1,
                        'status_text' => 'Pago'
                    ]);

                    if ($status === false) {
                        return $this->transactionsModel->errors();
                    }
                };
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        };

        return [
            'paymentId' => $response['Payment']['PaymentId'],
            'status' => $response['Payment']['Status'],
            'statusName' => $this->getPaymentStatusName($response['Payment']['Status']),
            'full' => $response
        ];
    }

    /**
     * Handles the check payment status response.
     *
     * @param array $response
     * @return array
     */
    protected function handleCheckPaymentStatusResponsePix(array $response): array
    {
        if ($response['Payment']['Status'] === 2) {

            try {
                
                $rows = $this->transactionsModel->where([
                    'id_transacao' => $response['Payment']['PaymentId'],
                    //'status !='    => 'Pago'
                    ])->findAll();
                
                if (count($rows)) {
                    $row = $rows[0];
                    $status = $this->transactionsModel->update($row['id'], [
                        'data_pagamento' => $response['Payment']['CapturedDate'],
                        'status' => 1,
                        'status_text' => 'Pago'
                    ]);
                    
                    $email = new EmailsLibraries;
                    $html = 'Recebemos seu pagamento';
                    $email->envioEmail(session('data')['email'], 'Comprovante de pagamento', $html);

                    if ($status === false) {
                        return $this->transactionsModel->errors();
                    }
                    $whatsApp = new CieloWhatsApp;
                    $dataClient = $this->buscaDadosMembro($rows);
                    $whatsApp->pago($dataClient, '');
                };
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        };

        return [
            'paymentId' => $response['Payment']['PaymentId'],
            'status' => $response['Payment']['Status'],
            'statusName' => $this->getPaymentStatusName($response['Payment']['Status']),
            'full' => $response
        ];
    }

    /**
     * Gets the payment status name.
     *
     * @param int $status
     * @return string
     */
    protected function getPaymentStatusName(int $status): string
    {
        $statusNames = [
            1 => 'NotFinished',
            2 => 'Authorized',
            3 => 'PaymentConfirmed',
            10 => 'PaymentCancelled',
            11 => 'Refunded',
            12 => 'Pending',
            13 => 'Aborted',
            20 => 'Scheduled'
        ];

        return $statusNames[$status] ?? 'Unknown';
    }

    /**
     * Saves the transaction.
     *
     * @param array $params
     * @param array $response
     * @return bool
     */
    protected function saveTransactionPix(array $params, array $response, string $descricao, string $tipo, string $desc_l): bool
    {

        $data = [
            'id_pedido' => $response['MerchantOrderId'],
            'id_adm' => session('data')['idAdm'],
            'id_user' => session('data')['id'],
            'id_cliente' => session('data')['id_perfil'],
            'id_transacao' => $response['Payment']['PaymentId'],
            'gateway' => 'cielo',
            'valor' => centavosParaReais($response['Payment']['Amount']),
            'log' => json_encode($response),
            'descricao' => $descricao,
            'tipo_pagamento' => $tipo,
            'descricao_longa' => $desc_l
        ];

        if (!$this->transactionsModel->where('id_transacao', $response['Payment']['PaymentId'])->countAllResults()) {
            $this->transactionsModel->insert($data);
        }
        return true;
    }

    /**
     * Saves the transaction.
     *
     * @param array $params
     * @param array $response
     * @return bool
     */
    protected function saveTransactionCreditCard(array $params, array $response, string $descricao, string $tipo, string $desc_l = null): bool
    {
        // Obter o código de retorno e a mensagem de retorno
        $returnCode = $response['Payment']['ReturnCode'];
        $returnMessage = $response['Payment']['ReturnMessage'];

        // Lista de códigos e mensagens de sucesso
        $successCodes = ['4', '6', '00']; // Códigos de sucesso
        $successMessages = ['Operation Successful', 'Transacao autorizada', 'Transacao capturada com sucesso'];

        // Verificar se o código ou a mensagem de retorno indica sucesso
        if (in_array($returnCode, $successCodes) || in_array($returnMessage, $successMessages)) {
            $status_text = 'Pago';
            $status = 1;
            $datePg = $response['Payment']['ReceivedDate'];

            // Obter o perfil do usuário baseado no tipo de sessão
            switch (session('data')['tipo']) {
                case 'pastor':
                    $builderPerfil = new PastoresModel();
                    break;
                case 'igreja':
                    $builderPerfil = new IgrejasModel();
                    break;
                case 'supervisor':
                    $builderPerfil = new SupervisoresModel();
                    break;
                case 'gerente':
                    $builderPerfil = new GerentesModel();
                    break;
                case 'superadmin':
                    $builderPerfil = new AdminModel();
                    break;
                default:
                    $builderPerfil = null;
                    break;
            }

            if ($builderPerfil) {
                $rowPastor = $builderPerfil->find(session('data')['id_perfil']);
                $sendCieloWhatsApp = new CieloWhatsApp();
                $sendCieloWhatsApp->pago($rowPastor, '');
            }
        } else {
            // Para outros códigos e mensagens, a transação é considerada cancelada
            $status_text = 'Cancelado';
            $status = 0;
            $datePg = null;
        }

        // Preparar os dados para inserção
        $data = [
            'id_pedido' => $response['MerchantOrderId'],
            'id_adm' => session('data')['idAdm'],
            'id_user' => session('data')['id'],
            'id_cliente' => session('data')['id_perfil'],
            'id_transacao' => $response['Payment']['PaymentId'],
            'gateway' => 'cielo',
            'valor' => centavosParaReais($response['Payment']['Amount']),
            'log' => json_encode($response),
            'descricao' => $descricao,
            'tipo_pagamento' => $tipo,
            'status_text' => $status_text,
            'status' => $status,
            'data_pagamento' => $datePg,
            'descricao_longa' => $desc_l
        ];

        // Verificar se a transação já existe antes de inserir
        if (!$this->transactionsModel->where('id_transacao', $response['Payment']['PaymentId'])->countAllResults()) {
            $this->transactionsModel->insert($data);
        }

        return true;
    }

    /**
     * Saves the transaction.
     *
     * @param array $params
     * @param array $response
     * @return bool
     */
    protected function saveTransactionCredit(array $params, array $response, string $descricao, string $tipo, string $desc_l = null): bool
    {

        if ($response['Payment']['ReturnMessage'] == 'Operation Successful') {
            $status_text = 'Pago';
            $status = 1;
            $datePg = $response['Payment']['ReceivedDate'];
        } elseif ($response['Payment']['ReturnMessage'] == 'Transacao autorizada') {
            $status_text = 'Pago';
            $status = 1;
            $datePg = $response['Payment']['ReceivedDate'];
        } else {
            $status_text = 'Falha';
            $status = 0;
            $datePg = null;
        }
        $data = [
            'id_pedido' => $response['MerchantOrderId'],
            'id_adm' => session('data')['idAdm'],
            'id_user' => session('data')['id'],
            'id_cliente' => session('data')['id_perfil'],
            'id_transacao' => $response['Payment']['PaymentId'],
            'gateway' => 'cielo',
            'valor' => centavosParaReais($response['Payment']['Amount']),
            'log' => json_encode($response),
            'descricao' => $descricao,
            'tipo_pagamento' => $tipo,
            'status_text' => $status_text,
            'status' => $status,
            'data_pagamento' => $datePg,
            'descricao_longa' => $desc_l
        ];

        if (!$this->transactionsModel->where('id_transacao', $response['Payment']['PaymentId'])->countAllResults()) {
            $this->transactionsModel->insert($data);
        }
        return true;
    }

    /**
     * Checks the payment status.
     *
     * @param string $paymentId
     * @return array
     * @throws Exception
     */
    public function checkPaymentStatus(string $paymentId): array
    {
        try {
            if (empty($paymentId)) {
                throw new Exception('O ID do pagamento é obrigatório.');
            }
            $endPoint = "/1/sales/{$paymentId}";
            return $this->makeRequest('GET', $endPoint, [], 'handleCheckPaymentStatusResponse', true);
        } catch (Exception $e) {
            // Logar o erro e retornar uma resposta de erro padrão
            log_message('error', "Erro ao verificar status do pagamento para ID {$paymentId}: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => "Erro ao verificar status do pagamento: " . $e->getMessage(),
                'statusName' => 'Unknown', // Status default para tratar como erro desconhecido
                'full' => [] // Dados adicionais vazios
            ];
        }
    }


    /**
     * Checks the payment status.
     *
     * @param string $paymentId
     * @return array
     * @throws Exception
     */
    public function checkPaymentStatusPix(string $paymentId): array
    {
        try {
            
            if (empty($paymentId)) {
                throw new Exception('O ID do pagamento é obrigatório.');
            }
            
            $endPoint = "/1/sales/{$paymentId}";
            
            return $this->makeRequest('GET', $endPoint, [], 'handleCheckPaymentStatusResponsePix', true);

        } catch (Exception $e) {
            // Logar o erro e retornar uma resposta de erro padrão
            log_message('error', "Erro ao verificar status do pagamento para ID {$paymentId}: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => "Erro ao verificar status do pagamento: " . $e->getMessage(),
                'statusName' => 'Unknown', // Status default para tratar como erro desconhecido
                'full' => [] // Dados adicionais vazios
            ];
        }
    }


    private function buscaDadosMembro(array $client)
    {
        $usuarios = new UsuariosModel();
        $build = $usuarios->find($client[0]['id_user']);
        if ($build['tipo'] == 'pastor') {
            $buildClient = new PastoresModel();
            return $buildClient->find($build['id_perfil']);
        }
        if ($build['tipo'] == 'igreja') {
            $buildClient = new IgrejasModel();
            return $buildClient->find($build['id_perfil']);
        }

        if ($build['tipo'] == 'supervisor') {
            $buildClient = new SupervisoresModel();
            return $buildClient->find($build['id_perfil']);
        }

        if ($build['tipo'] == 'gerente') {
            $buildClient = new GerentesModel();
            return $buildClient->find($build['id_perfil']);
        }

        if ($build['tipo'] == 'superadmin') {
            $buildClient = new AdminModel();
            return $buildClient->find($build['id_perfil']);
        }
    }
}
