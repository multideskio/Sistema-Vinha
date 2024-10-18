<?php

namespace App\Controllers\Apis\V1;

use App\Gateways\Cielo\CieloCron;
use App\Gateways\Cielo\CieloPix;
use App\Models\ReembolsosModel;
use App\Models\RelatoriosGeradosModel;
use App\Models\TransacoesModel;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

use Config\Redis as RedisConfig;
use DateTime;
use Predis\Client as RedisClient;

class Transacoes extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    protected TransacoesModel $modelTransacoes;
    protected ReembolsosModel $modelReembolso;
    protected CieloCron $cieloCron;
    protected CieloPix $cieloPix;
    protected RedisClient $redis;

    public function __construct()
    {
        $this->modelTransacoes = new TransacoesModel();
        $this->modelReembolso  = new ReembolsosModel();
        $this->cieloCron       = new CieloCron();
        $this->cieloPix        = new CieloPix();

        // Carrega as configurações do Redis
        $config = new RedisConfig();

        // Tenta conectar ao Redis com as configurações fornecidas
        try {
            $this->redis = new RedisClient($config->default);
            // Testa a conexão
            $this->redis->ping();
        } catch (\Exception $e) {
            // Tratamento de erro ao conectar ao Redis
            log_message('error', 'Erro ao conectar ao Redis: ' . $e->getMessage());
            die('Não foi possível conectar ao Redis: ' . $e->getMessage());
        }

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

        try {
            $modelUser = new UsuariosModel();
            $dateIn    = $this->request->getGet('dateIn');
            $dateOut   = $this->request->getGet('dateOut') . ' 23:59:59';

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
            $currentMonth   = $getValor($this->modelTransacoes->dashMensal());
            $currentBoletos = $getValor($this->modelTransacoes->dashBoletos($dateIn, $dateOut));
            $currentPix     = $getValor($this->modelTransacoes->dashPix($dateIn, $dateOut));
            $currentCredito = $getValor($this->modelTransacoes->dashCredito($dateIn, $dateOut));
            $currentDebito  = $getValor($this->modelTransacoes->dashDebito($dateIn, $dateOut));
            $currentYear    = $getValor($this->modelTransacoes->dashAnual());
            $currentTotal   = $getValor($this->modelTransacoes->dashTotal());

            // Obter valores do mês anterior
            $previousMonth   = $getValor($this->modelTransacoes->dashMensalAnterior());
            $previousBoletos = $getValor($this->modelTransacoes->dashBoletosAnterior());
            $previousPix     = $getValor($this->modelTransacoes->dashPixAnterior());
            $previousCredito = $getValor($this->modelTransacoes->dashCreditoAnterior());
            $previousDebito  = $getValor($this->modelTransacoes->dashDebitoAnterior());
            $previousYear    = $getValor($this->modelTransacoes->dashAnualAnterior());
            $previousTotal   = $getValor($this->modelTransacoes->dashTotal());

            // Calcular variações percentuais
            $growthRateMonth   = $calculateGrowthRate($currentMonth, $previousMonth);
            $growthRateBoletos = $calculateGrowthRate($currentBoletos, $previousBoletos);
            $growthRatePix     = $calculateGrowthRate($currentPix, $previousPix);
            $growthRateCredito = $calculateGrowthRate($currentCredito, $previousCredito);
            $growthRateDebito  = $calculateGrowthRate($currentDebito, $previousDebito);
            $growthRateYear    = $calculateGrowthRate($currentYear, $previousYear);
            $growthRateTotal   = $calculateGrowthRate($currentTotal, $previousTotal);

            $data['mes'] = [
                'valor'       => decimalParaReaisBrasil($currentMonth),
                'crescimento' => $growthRateMonth,
            ];
            $data['boletos'] = [
                'valor'       => decimalParaReaisBrasil($currentBoletos),
                'crescimento' => $growthRateBoletos,
            ];
            $data['pix'] = [
                'valor'       => decimalParaReaisBrasil($currentPix),
                'crescimento' => $growthRatePix,
            ];
            $data['credito'] = [
                'valor'       => decimalParaReaisBrasil($currentCredito),
                'crescimento' => $growthRateCredito,
            ];
            $data['debito'] = [
                'valor'       => decimalParaReaisBrasil($currentDebito),
                'crescimento' => $growthRateDebito,
            ];
            $data['totalAnual'] = [
                'valor'       => decimalParaReaisBrasil($currentYear),
                'crescimento' => $growthRateYear,
            ];
            $data['totalGeral'] = [
                'valor'       => decimalParaReaisBrasil($currentTotal),
                'crescimento' => $growthRateTotal,
            ];
            $data['totalUsers'] = $modelUser->countAllResults();

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function ultimosCadastros()
    {
    }

    public function ultimasTransacoes()
    {
        return $this->respond($this->modelTransacoes->transacoes());
    }

    public function reembolso($id)
    {
        try {
            $input = $this->request->getPost();
            //GRAVA DADAOS DO REEMBOLSO
            $valor = intval(limparString($input['valor']));
            $data  = [
                "id_admin"     => session('data')['idAdm'],
                "id_user"      => session('data')['id'],
                "valor"        => centavosParaReais($valor),
                'id_transacao' => $input['id_transacao'],
                'descricao'    => $input['desc'],
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

    // File: app/Controllers/RelatorioController.php

    public function gerarRelatorio()
    {
        // Parâmetros da requisição
        $data = $this->request->getVar('dateSearch');

        if (!$data || strpos($data, ' até ') === false) {
            return $this->respond(['status' => 'error', 'message' => 'Parâmetro de data inválido.'], 400);
        }

        $separa = explode(" até ", $data);

        // Função para converter a data de dd/mm/yyyy para yyyy-mm-dd
        function converterData($data)
        {
            $dateTime = DateTime::createFromFormat('d/m/Y', $data);

            return $dateTime ? $dateTime->format('Y-m-d') : null;
        }

        $dataInicio    = converterData($separa[0]);
        $dataFim       = converterData($separa[1]);
        $tipoPagamento = ($this->request->getVar('tipoPagamento') === 'Todos') ? false : $this->request->getVar('tipoPagamento');
        $status        = ($this->request->getVar('statusPagamento') === 'Todos') ? false : $this->request->getVar('statusPagamento');

        // Verifica se as datas foram convertidas corretamente
        if (!$dataInicio || !$dataFim) {
            return $this->respond(['status' => 'error', 'message' => 'Erro ao converter as datas fornecidas.'], 400);
        }

        try {
            // Consulta para contar a quantidade de registros
            $transacoesQuery = (new \App\Models\TransacoesModel())
                ->where('created_at >=', $dataInicio)
                ->where('created_at <=', $dataFim);

            if ($tipoPagamento) {
                $transacoesQuery->where('tipo_pagamento', $tipoPagamento);
            }

            if ($status) {
                $transacoesQuery->where('status_text', $status);
            }

            // Contar o número de transações que satisfazem os critérios
            $totalRegistros = $transacoesQuery->countAllResults(false);

            // Se não houver registros, retornar 204 No Content
            if ($totalRegistros === 0) {
                return $this->respond(['status' => 'error', 'message' => 'Não houve resultados para essa pesquisa.']);
            }

            // Defina o limite de registros para decidir o processamento
            $limiteParaFila = 1; // Ajuste conforme sua necessidade

            if ($totalRegistros <= $limiteParaFila) {
                // Executa a tarefa em primeiro plano
                $job = new \App\Jobs\GenerateReportJob();
                $job->handle([
                    'data_inicio'    => $dataInicio,
                    'data_fim'       => $dataFim,
                    'tipo_pagamento' => $tipoPagamento,
                    'status'         => $status,
                    'id_admin'       => session('data')['idAdm'],
                    'id_user'        => session('data')['id'],
                    'whatsapp'       => session('data')['celular'],
                ]);

                log_message('info', 'Relatório gerado em primeiro plano com sucesso.');

                return $this->respondCreated(['status' => 'success', 'message' => 'Relatório gerado com sucesso em primeiro plano.']);
            } else {
                // Adicionar a tarefa na fila Redis
                $job = [
                    'handler' => 'App\Jobs\GenerateReportJob',
                    'data'    => [
                        'data_inicio'    => $dataInicio,
                        'data_fim'       => $dataFim,
                        'tipo_pagamento' => $tipoPagamento,
                        'status'         => $status,
                        'id_admin'       => session('data')['idAdm'],
                        'id_user'        => session('data')['id'],
                        'whatsapp'       => session('data')['celular'],
                    ],
                ];

                // Adiciona a tarefa na fila chamada "jobs_queue"
                $status = $this->redis->rpush('jobs_queue', json_encode($job));

                if ($status) {
                    log_message('info', 'Tarefa adicionada à fila Redis: ' . json_encode($job));

                    return $this->respond(['status' => 'success', 'message' => 'Relatório sendo gerado na fila.'], 202);
                } else {
                    log_message('error', 'Falha ao adicionar a tarefa à fila Redis.');

                    return $this->respond(['status' => 'error', 'message' => 'Falha ao adicionar a tarefa na fila.'], 500);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro ao gerar o relatório: ' . $e->getMessage());

            return $this->respond(['status' => 'error', 'message' => 'Erro ao gerar o relatório.'], 500);
        }
    }

    public function listRelatorios()
    {
        $modelRelatorios = new RelatoriosGeradosModel();
        $data            = $modelRelatorios->listSearch($this->request->getGet());

        return $this->respond($data);
    }

    public function transacoesUserIgrejaPastor()
    {
        $data = $this->modelTransacoes->listSearchIgrejaPastor($this->request->getGet(), 10);

        return $this->respond($data);
    }
}