<?php

namespace App\Controllers\Apis\V1;

use App\Gateways\Cielo\CieloBase;
use App\Gateways\Cielo\CieloBoleto;
use App\Gateways\Cielo\CieloCreditCard;
use App\Gateways\Cielo\CieloCron;
use App\Gateways\Cielo\CieloDebitCard;
use App\Gateways\Cielo\CieloPix;
use App\Libraries\WhatsappLibraries;
use App\Models\TransacoesModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Cielo extends ResourceController
{
    use ResponseTrait;

    protected $creditCardGateway;
    protected $debitCardGateway;
    protected $boletoGateway;
    protected $pixGateway;
    protected $cieloBase;

    public function __construct()
    {
        $this->creditCardGateway = new CieloCreditCard();
        //$this->debitCardGateway = new CieloDebitCard();
        $this->boletoGateway = new CieloBoleto();
        $this->pixGateway    = new CieloPix();
        $this->cieloBase     = new CieloBase();
        helper('auxiliar');
    }

    public function createCreditCardCharge()
    {
        $nome       = $this->request->getPost('nome');
        $valor      = limparString($this->request->getPost('valor'));
        $cartao     = limparString($this->request->getPost('cartao'));
        $securicode = $this->request->getPost('securicode');
        $data       = str_replace(' ', '', $this->request->getPost('data'));
        $desc       = $this->request->getPost('tipo');
        $desc_l     = $this->request->getPost('desc');

        try {
            if (!intval(limparString($valor))) {
                throw new Exception("O valor não foi informado.");
            }
            $response = $this->creditCardGateway->credito($nome, $valor, $cartao, $securicode, $data, getCardType($cartao), $desc, $desc_l);

            return $this->respond($response, 200);
        } catch (Exception $e) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'Transação não aprovada, caso o erro persista, entre em contato com suporte',
                'log'     => $e->getMessage(),
            ], 400);
        }
    }

    public function createDebitCardCharge()
    {
        $nome       = $this->request->getPost('nome');
        $valor      = intval(limparString($this->request->getPost('valor')));
        $cartao     = $this->request->getPost('cartao');
        $securicode = $this->request->getPost('securicode');
        $data       = $this->request->getPost('data');
        $urlRetorno = 'https://n8n.conect.app/webhook-test/92d28dc6-70e9-48f7-9118-3e175a06d428'; //$this->request->getPost('urlRetorno');

        try {
            if (!intval(limparString($valor))) {
                throw new Exception("O valor não foi informado.");
            }

            $response = $this->debitCardGateway->debito($nome, $valor, $cartao, $securicode, $data, 'Visa', $urlRetorno);

            return $this->respond([
                'status'         => 'success',
                'message'        => 'Cobrança criada com sucesso.',
                'data'           => $response,
                'payment_status' => $response['Payment']['Status'],
            ], 200);
        } catch (Exception $e) {
            return $this->fail([
                'status'  => 'error',
                'message' => 'Erro ao criar cobrança de cartão de débito: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function createBoletoCharge()
    {
        $input = $this->request->getPost();

        $valor = intval(limparString($input['valor']));
        $tipo  = esc($input['tipo']);
        $desc  = esc($input['desc']);

        try {
            $response = $this->boletoGateway->boleto($valor, $tipo, $desc);

            return $this->respond($response, 200);
        } catch (Exception $e) {
            return $this->fail([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function createPixCharge()
    {
        $nome      = $this->request->getPost('nome');
        $valor     = intval(limparString($this->request->getPost('valor')));
        $descricao = $this->request->getPost('tipo');
        $desc      = $this->request->getPost('descPix');

        try {
            if (!intval(limparString($valor))) {
                throw new Exception("O valor não foi informado.");
            }
            $response = $this->pixGateway->pix($nome, $valor, $descricao, $desc);

            return $this->respond($response, 200);
        } catch (Exception $e) {
            return $this->fail([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function checkPaymentStatus($paymentId)
    {
        try {
            $response = $this->cieloBase->checkPaymentStatusPix($paymentId);

            return $this->respond($response, 200);
        } catch (Exception $e) {
            return $this->fail([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function cron()
    {
        $cieloCron = new CieloCron();
        /*try{
            //$whatsApp = new WhatsappLibraries();
            //$msg = "Tarefa cron sendo executada \n".date('d/m/Y H:i:s');
            //$whatsApp->verifyNumber(['message' => $msg], '5562981154120', 'text');
        }catch(\Exception $e){
            log_message('error', 'Houve um erro ao tentar enviar o aviso de tarefa cron iniciada: '. $e->getMessage());
        }*/
        try {
            $modelTrans = new TransacoesModel();
            $modelTrans->verificarEnvioDeLembretes();

            return $this->respond($cieloCron->verifyTransaction());
        } catch (Exception $e) {
            return $this->fail([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
