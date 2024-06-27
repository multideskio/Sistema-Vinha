<?php

namespace App\Controllers;

use App\Gateways\Cielo\GatewayCielo;
use App\Libraries\EmailsLibraries;
use App\Libraries\WhatsappLibraries;
use App\Models\AjudaModel;
use App\Models\ConfigMensagensModel;
use App\Models\GerentesModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    protected $modelConfig;
    protected $data;

    public function __construct()
    {
        $this->modelConfig = new \App\Models\AdminModel;
        $this->data['rowConfig'] = $this->modelConfig->cacheData();
        $this->data['textResult'] = "";
    }

    public function index()
    {
        return view('login/login', $this->data);
    }

    public function busca_ajuda()
    {
        
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
            } else if (count($rows) > 1) {
                $tt = count($rows);
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
        $row = $modelAjuda->where('slug', $slug)->findAll(1);
        if (!count($row) == 1) {
            throw PageNotFoundException::forPageNotFound();
        }
        $this->data['result'] = $row[0];
        return view('ajuda/post', $this->data);
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
    public function teste(){
        $newEmail = new EmailsLibraries;
        $data = [
            'nome' => "Paulo",
            'token' => time()
        ];
        
        $message = view('emails/confirma-email', $data);

        //return $newEmail->envioTeste('igrsysten@gmail.com', 'Teste de envio', $message);
    }

    public function phpinfo(){
        phpinfo();
    }
}
