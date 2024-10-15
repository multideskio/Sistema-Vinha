<?php

namespace App\Controllers;

use App\Gateways\Cielo\CieloBoleto;
use App\Libraries\RedisLibrary;
use App\Libraries\WebSocketLibrary;
use App\Models\AjudaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Home extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel();
        //$this->data['rowConfig']  = $this->modelConfig->searchCacheData(1);
        $this->data['textResult'] = "";
        $this->data['titlePage']  = "";
    }

    public function index()
    {

        //$this->cachePage(getCacheExpirationTimeInSeconds(60));
        return view('login/login', [
            'data'      => $this->modelConfig,
            'titlePage' => 'Entrar',
        ]);
    }

    public function busca_ajuda()
    {
        //$this->cachePage(getCacheExpirationTimeInSeconds(60));
        $modelAjuda = new AjudaModel();

        if ($this->request->getGet('search')) {
            $rows = $modelAjuda->groupStart()
                ->like('titulo', $this->request->getGet('search'))
                ->orLike('tags', $this->request->getGet('search'))
                ->orLike('conteudo', $this->request->getGet('search'))
                ->groupEnd()
                ->findAll();

            $this->data['rows'] = $rows;

            if (count($rows) == 1) {
                $this->data['textResult'] = "<h1 class='text-primary mt-3'>1 resultado encontrado!</h1>";
            } elseif (count($rows) > 1) {
                $tt                       = count($rows);
                $this->data['textResult'] = "<h1 class='text-primary mt-3'>{$tt} resultados encontrados!</h1>";
            } else {
                $this->data['textResult'] = "<h1 class='text-danger mt-3'>Nenhum resultado encontrado!</h1>";
            }
        } else {
            $this->data['rows'] = $modelAjuda->findAll();
        }
        helper('text');

        return view('ajuda/home', $this->data);
    }

    public function ajuda($slug)
    {
        $modelAjuda = new AjudaModel();
        helper('auxiliar');
        $cache = \Config\Services::cache();

        if (!$cache->get($slug)) {
            $row = $modelAjuda->where('slug', $slug)->first();
            $cache->save($slug, $row, getCacheExpirationTimeInSeconds(30));
        } else {
            $row = $cache->get($slug);
        }

        if (!$row) {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->data['result'] = $row;

        return view('ajuda/post', $this->data);
    }

    public function sair()
    {
        session_destroy();

        return redirect()->to(site_url());
    }

    /*public function pix()
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
    public function teste()
    {
        $newEmail = new EmailsLibraries;
        $data = [
            'nome' => "Paulo",
            'token' => time()
        ];

        $message = view('emails/confirma-email', $data);

        //return $newEmail->envioTeste('igrsysten@gmail.com', 'Teste de envio', $message);
    }*/

    public function Teste()
    {
        try {
            $boletoClass = new CieloBoleto();
            $boleto      = $boletoClass->boleto('Paulo', 1, 7, '03762839123', 'Boleto', 'Multidesk.io', 'Isso é um teste.', []);

            if (isset($boleto['status']) && $boleto['status'] === 'success') {
                // Exibir o link do boleto
                return "<a href='{$boleto['boletoUrl']}'>Clique aqui para visualizar o boleto</a>";
            } else {
                return "Erro ao gerar o boleto.";
            }
        } catch(\Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            echo "</pre>";

        }
    }

    public function phpinfo()
    {
        phpinfo();
    }

    /*public function teste()
    {
        //Predis\Client as RedisClient;
        //Config\Redis as RedisConfig;

        $redis = new \Predis\Client((new \Config\Redis())->default);

        // Adicionar a tarefa na fila Redis
        $job = [
            'handler' => 'App\Jobs\Avisos',
            'data' => [
                'aviso' => 'Você é legal',
                'tel' => '5562981154120'
            ]
        ];

        // Tente adicionar à fila e verifique se houve sucesso
        if ($redis->rpush('jobs_avisos', json_encode($job))) {
            log_message('info', 'Tarefa adicionada à fila Redis: ' . json_encode($job));
        } else {
            log_message('error', 'Falha ao adicionar a tarefa à fila Redis.');
        }
    }*/

    public function teste00()
    {
        $data = [
            [
                "tipo"    => "gerado",
                "message" => "Um novo 'boleto, pix' foi gerado",
            ],
            [
                "tipo"    => "pago",
                "message" => "Uma nova fatura foi paga",
            ],
        ];

        $webSocket = new WebSocketLibrary();
        $redis     = new RedisLibrary();

        foreach ($data as $list) {
            // Enviar notificação via WebSocket para clientes conectados
            $webSocket->sendMessage([
                'tipo'    => $list['tipo'],
                'message' => $list['message'],
            ]);

            // Publicar notificação no Redis
            $redis->publish('cliente_event', json_encode([
                'tipo'    => $list['tipo'],
                'message' => $list['message'],
            ]));

        }
    }
}
