<?php

namespace App\Controllers\Apis\V1;

use App\Gateways\Cielo\CieloCron;
use App\Gateways\Cielo\CieloPix;
use App\Models\ReembolsosModel;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Transacoes extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    protected $modelTransacoes;
    protected $modelReembolso;
    protected $cieloCron;
    protected $cieloPix;

    public function __construct()
    {
        $this->modelTransacoes = new \App\Models\TransacoesModel();
        $this->modelReembolso = new ReembolsosModel();
        $this->cieloCron = new CieloCron;
        $this->cieloPix = new CieloPix;

        helper('auxiliar');
    }
    public function index()
    {
        //
        /*if($this->request->getGet("search") == "false"){
            $data = $this->modelTransacoes->listSearch();
        }else{
            $data = $this->modelTransacoes->listSearch($this->request->getGet());
        }*/

        //$cielo = new CieloCron;
        $data = $this->cieloCron->verifyTransaction();

        return $this->respond($data);
    }

    public function usuario()
    {
        //
        $data = $this->modelTransacoes->listSearchUsers($this->request->getGet(), 10);
        return $this->respond($data);
    }

    public function adminUsers($id = null)
    {
        $data = $this->modelTransacoes->listTransacaoUsuario($id, $this->request->getGet(), 10);
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //

    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }

    public function dashboardAdmin()
    {
        $modelUser = new UsuariosModel();
        $dateIn = $this->request->getGet('dateIn');
        $dateOut = $this->request->getGet('dateOut') . ' 23:59:59';

        // Função auxiliar para garantir que o valor seja 0 se for null
        $getValor = function ($result) {
            return $result['valor'] ?? 0;
        };

        // Função auxiliar para calcular o crescimento percentual, arredondar e adicionar sinal
        $calculateGrowthRate = function ($current, $previous) {
            if ($previous > 0) {
                $growth = round((($current - $previous) / $previous) * 100, 2);
                return ($growth > 0 ? '+' : '') . $growth . '%';
            } else {
                return ($current > 0 ? '+100%' : '0%');
            }
        };

        // Obter valores do mês atual
        $currentMonth = $getValor($this->modelTransacoes->dashMensal());
        $currentBoletos = $getValor($this->modelTransacoes->dashBoletos($dateIn, $dateOut));
        $currentPix = $getValor($this->modelTransacoes->dashPix($dateIn, $dateOut));
        $currentCredito = $getValor($this->modelTransacoes->dashCredito($dateIn, $dateOut));
        $currentDebito = $getValor($this->modelTransacoes->dashDebito($dateIn, $dateOut));
        $currentYear = $getValor($this->modelTransacoes->dashAnual());
        $currentTotal = $getValor($this->modelTransacoes->dashTotal());

        // Obter valores do mês anterior
        $previousMonth = $getValor($this->modelTransacoes->dashMensalAnterior());
        $previousBoletos = $getValor($this->modelTransacoes->dashBoletosAnterior());
        $previousPix = $getValor($this->modelTransacoes->dashPixAnterior());
        $previousCredito = $getValor($this->modelTransacoes->dashCreditoAnterior());
        $previousDebito = $getValor($this->modelTransacoes->dashDebitoAnterior());
        $previousYear = $getValor($this->modelTransacoes->dashAnualAnterior());
        $previousTotal = $getValor($this->modelTransacoes->dashTotal());

        // Calcular variações percentuais
        $growthRateMonth = $calculateGrowthRate($currentMonth, $previousMonth);
        $growthRateBoletos = $calculateGrowthRate($currentBoletos, $previousBoletos);
        $growthRatePix = $calculateGrowthRate($currentPix, $previousPix);
        $growthRateCredito = $calculateGrowthRate($currentCredito, $previousCredito);
        $growthRateDebito = $calculateGrowthRate($currentDebito, $previousDebito);
        $growthRateYear = $calculateGrowthRate($currentYear, $previousYear);
        $growthRateTotal = $calculateGrowthRate($currentTotal, $previousTotal);

        $data['mes'] = [
            'valor' => decimalParaReaisBrasil($currentMonth),
            'crescimento' => $growthRateMonth
        ];
        $data['boletos'] = [
            'valor' => decimalParaReaisBrasil($currentBoletos),
            'crescimento' => $growthRateBoletos
        ];
        $data['pix'] = [
            'valor' => decimalParaReaisBrasil($currentPix),
            'crescimento' => $growthRatePix
        ];
        $data['credito'] = [
            'valor' => decimalParaReaisBrasil($currentCredito),
            'crescimento' => $growthRateCredito
        ];
        $data['debito'] = [
            'valor' => decimalParaReaisBrasil($currentDebito),
            'crescimento' => $growthRateDebito
        ];
        $data['totalAnual'] = [
            'valor' => decimalParaReaisBrasil($currentYear),
            'crescimento' => $growthRateYear
        ];
        $data['totalGeral'] = [
            'valor' => decimalParaReaisBrasil($currentTotal),
            'crescimento' => $growthRateTotal
        ];
        $data['totalUsers'] = $modelUser->countAllResults();

        return $this->respond($data);
    }

    public function reembolso($id)
    {
        try {
            $input = $this->request->getPost();
            //GRAVA DADAOS DO REEMBOLSO
            $valor = intval(limparString($input['valor']));
            $data = [
                "id_admin"     => session('data')['idAdm'],
                "id_user"      => session('data')['id'],
                "valor"        => centavosParaReais($valor),
                'id_transacao' => $input['id_transacao'],
                'descricao'    => $input['desc']
            ];
            $this->modelReembolso->transStart();
            $this->modelReembolso->insert($data);
            
            $reembolso = $this->cieloPix->refundPix($id, $valor);
            $this->modelReembolso->transComplete();
            //return $this->respond([$data, $valor]);
            return $this->respond($reembolso);
        } catch (\Exception $e) {
            $this->modelReembolso->transRollback();
            return $this->fail($e->getMessage());
        }
    }
}
