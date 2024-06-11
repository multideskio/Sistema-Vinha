<?php namespace App\Gateways\Cielo;

use App\Models\GatewaysModel;
use App\Models\TransacoesModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;

class GatewayCielo {

    private $merchantId;
    private $merchantKey;
    private $apiUrlQuery;
    private $apiUrl;
    private $headers;
    protected $modelGateway;
    protected $transactionsModel;
    protected $client;

    public function __construct()
    {
        $this->modelGateway = new GatewaysModel();
        $this->transactionsModel = new TransacoesModel();
        $this->client = new Client();
        $this->initializeCieloConfig();
    }

    private function initializeCieloConfig()
    {
        $cielo = $this->data();

        if ($cielo['status'] == 1) {
            $this->merchantId  = $cielo['merchantid_pro'];
            $this->merchantKey = $cielo['merchantkey_pro'];
            $this->apiUrlQuery = 'https://apiquery.cieloecommerce.cielo.com.br';
            $this->apiUrl = 'https://api.cieloecommerce.cielo.com.br';
        } else {
            $this->merchantId  = $cielo['merchantid_dev'];
            $this->merchantKey = $cielo['merchantkey_dev'];
            $this->apiUrlQuery = 'https://apiquerysandbox.cieloecommerce.cielo.com.br';
            $this->apiUrl = 'https://apisandbox.cieloecommerce.cielo.com.br';
        }

        $this->headers = [
            'Content-Type' => 'application/json',
            'MerchantId' => $this->merchantId,
            'MerchantKey' => $this->merchantKey,
        ];
    }

    private function data()
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

    public function credito($nome, $valor, $cartao, $securicode, $data, $brand = 'Visa')
    {
        $cielo = $this->data();
        if (!$cielo['active_credito']) {
            throw new Exception('Cobrança por cartão de crédito não está ativa.');
        }

        $params = [
            "MerchantOrderId" => time(), // Pode ser substituído por um ID de pedido único do seu sistema
            "Customer" => [
                "Name" => $nome
            ],
            "Payment" => [
                "Type" => "CreditCard",
                "Amount" => $valor, // valor em centavos, 10000 = R$ 100,00
                "Installments" => 1,
                "CreditCard" => [
                    "CardNumber" => $cartao,
                    "Holder" => $nome,
                    "ExpirationDate" => $data,
                    "SecurityCode" => $securicode,
                    "Brand" => $brand
                ]
            ]
        ];

        return $this->createCreditCardCharge($params);
    }

    public function debito($nome, $valor, $cartao, $securicode, $data, $brand = 'Visa')
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

    public function boleto($nome, $valor, $dataVencimento)
    {
        $cielo = $this->data();
        if (!$cielo['active_boletos']) {
            throw new Exception('Cobrança por boleto não está ativa.');
        }

        $params = [
            "MerchantOrderId" => time(),
            "Customer" => [
                "Name" => $nome
            ],
            "Payment" => [
                "Type" => "Boleto",
                "Amount" => $valor,
                "BoletoNumber" => time(),
                "Assignor" => "Empresa XYZ",
                "Demonstrative" => "Pagamento de Compra",
                "ExpirationDate" => $dataVencimento,
                "Identification" => "12345678909",
                "Instructions" => "Não receber após o vencimento"
            ]
        ];

        return $this->createBoletoCharge($params);
    }

    public function pix($nome, $valor)
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

        return $this->createPixCharge($params);
    }

    public function createCreditCardCharge($params)
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $endPoint = '/1/sales/';
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');
            //$this->saveTransaction($params, $response);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança de cartão de crédito: " . $e->getMessage());
        }
    }

    public function createDebitCardCharge($params)
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

    public function createBoletoCharge($params)
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $params['Payment']['Type'] = 'Boleto';
            $endPoint = '/1/sales/';
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');
            $this->saveTransaction($params, $response);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança de boleto: " . $e->getMessage());
        }
    }

    public function createPixCharge($params)
    {
        try {
            $this->validateParams($params, ['MerchantOrderId', 'Customer', 'Payment']);
            $params['Payment']['Type'] = 'Pix';
            $endPoint = '/1/sales/';
            $response = $this->makeRequest('POST', $endPoint, $params, 'handleCreateChargeResponse');
            $this->saveTransaction($params, $response);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao criar cobrança Pix: " . $e->getMessage());
        }
    }

    public function checkPaymentStatus($paymentId)
    {
        try {
            if (empty($paymentId)) {
                throw new Exception('O ID do pagamento é obrigatório.');
            }
            $endPoint = "/1/sales/{$paymentId}";
            $response = $this->makeRequest('GET', $endPoint, [], 'handleCheckPaymentStatusResponse', true); // true indica que está usando apiUrlQuery
            return $response;
        } catch (Exception $e) {
            throw new Exception("Erro ao verificar status do pagamento: " . $e->getMessage());
        }
    }

    private function validateParams($params, $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                throw new Exception("O campo {$field} é obrigatório.");
            }
        }
    }

    private function makeRequest($method, $endPoint, $params, $responseHandler, $useQueryUrl = false)
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

    private function handleCreateChargeResponse($response)
    {
        return [
            'paymentId' => $response['Payment']['PaymentId'],
            'status' => $response['Payment']['Status'],
            'statusName' => $this->getPaymentStatusName($response['Payment']['Status']),
            'returnCode' => $response['Payment']['ReturnCode'],
            'returnMessage' => $response['Payment']['ReturnMessage'],
            'authenticationUrl' => $response['Payment']['AuthenticationUrl'] ?? null
        ];
    }

    private function handleCheckPaymentStatusResponse($response)
    {
        return [
            'paymentId' => $response['Payment']['PaymentId'],
            'status' => $response['Payment']['Status'],
            'statusName' => $this->getPaymentStatusName($response['Payment']['Status'])
        ];
    }

    private function getPaymentStatusName($status)
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

    private function saveTransaction($params, $response)
    {
        /*$this->transactionsModel->insert([
            'payment_id' => $response['paymentId'],
            'merchant_order_id' => $params['MerchantOrderId'],
            'payment_type' => $params['Payment']['Type'],
            'amount' => $params['Payment']['Amount'],
            'status' => $response['status'],
            'return_code' => $response['returnCode'],
            'return_message' => $response['returnMessage']
        ]);*/
         return true;
    }
}
