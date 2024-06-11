<?php

namespace App\Controllers;

use App\Gateways\Cielo\GatewayCielo;
use App\Libraries\WhatsappLibraries;
use App\Models\ConfigMensagensModel;
use App\Models\GerentesModel;
use Exception;

class Home extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;
        $this->data['rowConfig'] = $this->modelConfig->cacheData();
    }

    public function index()
    {
        return view('login/login', $this->data);
    }

    public function sair()
    {
        session_destroy();

        return redirect()->to(site_url());
    }

    public function pix()
    {
        $cielo = new GatewayCielo();
        $params = [
            'MerchantOrderId' => '2020102601',
            'Customer' => [
                'Name' => 'Paulo Henrique',
                'Identity' => '03762839123',
                'IdentityType' => 'cpf'
            ],
            'Payment' => [
                'Type' => 'Pix',
                'Amount' => 1
            ]
        ];
        try {
            $response = $cielo->createPixCharge($params);
            echo "<pre>";
            echo "Cobrança Pix criada com sucesso. ID do Pagamento: ";
            print_r($response);
            echo "<img src='data:image/png;base64,{$response['Payment']['QrCodeBase64Image']}'>";
        } catch (Exception $e) {
            echo "Erro ao criar cobrança Pix: " . $e->getMessage();
        }
    }

    public function debito()
    {

        $cielo = new GatewayCielo;

        $params = [
            "MerchantOrderId" => "2014111704",
            "Customer" => [
                "Name" => "Comprador débito"
            ],
            "Payment" => [
                "Type" => "DebitCard",
                "Amount" => 15700,
                "ReturnUrl" => "https://n8n.conect.app/webhook-test/92d28dc6-70e9-48f7-9118-3e175a06d428",
                "DebitCard" => [
                    "CardNumber" => "1234123412341231",
                    "Holder" => "Teste Holder",
                    "ExpirationDate" => "12/2030",
                    "SecurityCode" => "123",
                    "Brand" => "Visa"
                ]
            ]
        ];

        try {
            $response = $cielo->createDebitCardCharge($params);
            echo "Cobrança criada com sucesso: " . json_encode($response);
        } catch (Exception $e) {
            echo "Erro ao criar cobrança: " . $e->getMessage();
        }
    }
    public function credito()
    {
        $cielo = new GatewayCielo;

        $params = [
            "MerchantOrderId" => "2014111703",
            "Customer" => [
                "Name" => "Comprador crédito a vista"
            ],
            "Payment" => [
                "Type" => "CreditCard",
                "Amount" => 15700,
                "Installments" => 1,
                "SoftDescriptor" => "Loja Exemplo",
                "CreditCard" => [
                    "CardNumber" => "1234123412341231",
                    "Holder" => "Teste Holder",
                    "ExpirationDate" => "12/2030",
                    "SecurityCode" => "123",
                    "Brand" => "Visa"
                ]
            ]
        ];



        try {
            $response = $cielo->createCreditCardCharge($params);
            echo "Cobrança criada com sucesso: " . json_encode($response);
        } catch (Exception $e) {
            echo "Erro ao criar cobrança: " . $e->getMessage();
        }
    }


    function teste00()
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();
        $headers = $this->request->getHeaders();
        $body = $this->request->getBody();

        $logData = [
            'method' => $method,
            'uri' => $uri,
            'headers' => $headers,
            'body' => $body,
        ];

        $logFile = WRITEPATH . 'logs/requests.log';

        // Escreve a requisição no arquivo de log
        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Request logged']);
    }

    public function teste()
    {
        $whatsapp = new WhatsappLibraries;
        $modelMsg = new ConfigMensagensModel();
        $rowMessage = $modelMsg->where(['id_adm' => session('data')['idAdm'], 'tipo' => 'novo_usuario', 'status' => true])->findAll(1);
        if (count($rowMessage)) {
            $data = [
                '{nome}' => "Paulo Henrique",
                '{sistema}' => "Boletos Vinha",
                'number' => "5562981154120",
                '{COD}' => "4120",
            ];
            $msg = $rowMessage[0]['mensagem'];
            // Faz a substituição
            $mensagem_final = str_replace(array_keys($data), array_values($data), $msg);
            // Exibe a mensagem final e verifica o número
            echo "<pre>";
            
            $ret = $whatsapp->verifyNumber(['message' => $mensagem_final, 'image' => ''], $data['number'], 'image');

            print_r($ret);
        }
    }


    public function teste0()
    {
        $model = new GerentesModel();

        // use the factory to create a Faker\Generator instance
        $faker = \Faker\Factory::create();
        $faker = \Faker\Factory::create('pt_BR');

        $data = array();

        $get = $this->request->getGet('num') ?? 3;

        for ($i = 0; $i < $get; $i++) {

            $data[] = [
                'nome' => $faker->firstName(),
                'sobrenome' => $faker->lastName(),
                'cpf' => $faker->cpf(false), // Use o método cpf(false) para gerar CPFs formatados
                'email' => $faker->email(),
                'foto' => $faker->imageUrl(200, 200), // Supondo que 'foto' seja uma URL de imagem
                'uf' => $faker->stateAbbr(),
                'cidade' => $faker->city(),
                'cep' => $faker->postcode(),
                'complemento' => $faker->secondaryAddress(),
                'bairro' => $faker->streetSuffix(),
                'data_dizimo' => $faker->dateTimeThisCentury()->format('Y-m-d'),
                'telefone' => $faker->phoneNumber(),
                'celular' => $faker->phoneNumber(),
                'facebook' => $faker->userName(),
                'instagram' => $faker->userName(),
            ];
        }

        $model->insertBatch($data);

        $cache = \Config\Services::cache();

        //sleep(3);
        $cache->delete("gerentes_Cache");
    }
}
