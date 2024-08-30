<?php

namespace App\Models;

use App\Libraries\WhatsappLibraries;
use CodeIgniter\Model;

class TransacoesModel extends Model
{
    protected $table            = 'transacoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pedido',
        'id_adm',
        'id_user',
        'id_cliente',
        'id_transacao',
        'gateway',
        'status',
        'valor',
        'log',
        'tipo_pagamento',
        'descricao',
        'data_pagamento',
        'status_text',
        'descricao_longa'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = ['limparCache'];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = ['limparCache'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ['limparCache'];

    protected function limparCache()
    {
        $cache = service('cache');
        $cache->deleteMatching("transacoesList_" . '*');
    }

    public function updateStatus()
    {
        return true;
    }

    /**
     * Verifica e envia lembretes de pagamento aos usuários.
     *
     * Este método verifica se o horário atual está dentro do intervalo permitido
     * (entre 08:00 e 18:00), busca as mensagens de lembrete de pagamento ativas e,
     * em seguida, envia lembretes de pagamento para usuários que não efetuaram
     * pagamentos no mês atual. Os lembretes são enviados via WhatsApp.
     *
     * @return bool Retorna false se o envio de lembrete está desativado ou fora do horário permitido, true caso contrário.
     */

    public function verificarEnvioDeLembretes()
    {
        // Verifica se o horário atual está dentro do intervalo permitido (08:00 - 18:00)
        $hora = date('H');

        if ($hora < 8 || $hora >= 18) {
            log_message('info', 'Fora de horário comercial');
            return false;
        }

        $dataEnvios = [];
        $db = \Config\Database::connect();
        $usuariosQuery = $db->table('usuarios')->select('usuarios.*')->where('usuarios.tipo !=', 'superadmin')->get();

        $usuarios            = $usuariosQuery->getResultArray();
        $controleEnviosModel = new ControleEnviosModel();
        $hoje                = date('Y-m-d');
        $mesAtual            = date('Y-m');

        // Configuração do Redis com tratamento de exceção
        try {
            $redis = new \Predis\Client((new \Config\Redis())->default);
        } catch (\Exception $e) {
            log_message('error', 'Erro ao conectar ao Redis: ' . $e->getMessage());
            return;
        }

        foreach ($usuarios as $usuario) {
            
            $perfil = $this->obterPerfilUsuario($usuario);

            if (!$perfil) {
                continue;
            }

            $melhorDia = $perfil['data_dizimo'];
            $dataPagamento = date('Y-m-' . $melhorDia);

            // Verifica se já foi enviado lembrete recente
            $dataEnvioUltimoLembrete = $controleEnviosModel->where('id_user', $usuario['id'])->orderBy('created_at', 'desc')->first();

            $diasDiferenca = (strtotime($hoje) - strtotime($dataPagamento)) / (60 * 60 * 24);

            if (abs($diasDiferenca) <= 3 && (!$dataEnvioUltimoLembrete || date('Y-m-d', strtotime($dataEnvioUltimoLembrete['created_at'])) != $hoje)) {

                // Adiciona a tarefa de lembrete na fila Redis
                $job = [
                    'handler' => 'App\Jobs\AvisosWhatsApp',
                    'data' => [
                        'usuario' => $perfil,
                        'diasDiferenca' => $diasDiferenca
                    ]
                ];

                try {
                    // Adiciona à fila e verifica se foi bem-sucedido
                    if ($redis->rpush('jobs_queue', json_encode($job))) {
                        log_message('info', 'Tarefa de lembrete adicionada à fila Redis: ' . json_encode($job));
                        $dataEnvios[] = ['id_user' => $usuario['id']];
                    } else {
                        log_message('error', 'Falha ao adicionar a tarefa de lembrete à fila Redis.');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Erro ao adicionar tarefa à fila Redis: ' . $e->getMessage());
                }
            } else {
                log_message('info', 'NÃO REGISTROU O ENVIO: ' . $perfil['id']);
            }
        }

        // Inserção em batch no banco de dados se houver envios registrados
        if ($dataEnvios) {
            try {
                $controleEnviosModel->insertBatch($dataEnvios);
                log_message('info', 'Envios registrados com sucesso no banco de dados.');
            } catch (\Exception $e) {
                log_message('error', 'Erro ao registrar envios no banco de dados: ' . $e->getMessage());
            }
        }
    }

    public function obterPerfilUsuario($usuario)
    {
        switch ($usuario['tipo']) {
            case 'gerente':
                $model = new \App\Models\GerentesModel();
                break;
            case 'supervisor':
                $model = new \App\Models\SupervisoresModel();
                break;
            case 'pastor':
                $model = new \App\Models\PastoresModel();
                break;
            case 'igreja':
                $model = new \App\Models\IgrejasModel();
                break;
            default:
                throw new \ErrorException("Tipo de permissão não definida");
        }
        return $model->find($usuario['id_perfil']);
    }

    private function enviarLembrete($usuario, $diasDiferenca)
    {
        $modelMessages = new ConfigMensagensModel();
        /**BUSCA MENSAGEM DE LEMBRETE DE PAGAMENTO */
        $lembrete_pagamento = $modelMessages
            ->where('status', 1)
            ->where('tipo', 'lembrete_pagamento')->first();

        if (!$lembrete_pagamento) {
            log_message('info', 'envio de lembrete desativado');
            return false;
        }

        /** BUSCA MENSAGEM DE PAGAMENTO EM ATRASO */
        $pagamento_atrasado = $modelMessages
            ->where('status', 1)
            ->where('tipo', 'pagamento_atrasado')->first();

        if (!$pagamento_atrasado) {
            log_message('info', 'envio de lembrete desativado');
            return false;
        }


        // Determina o nome do usuário
        $nome = !empty($usuario['nome']) ? $usuario['nome'] : (!empty($usuario['razao_social']) ? $usuario['razao_social'] : false);

        if ($nome) {
            // Mensagem dinâmica informando os dias restantes ou passados para o pagamento
            if ($diasDiferenca < 0) {
                $diasRestantes = abs($diasDiferenca);
                $dados = [
                    '{nome}' => $nome,
                    'number' => $usuario['celular'],
                    '{dias}' => ($diasRestantes > 1) ? $diasRestantes . ' dias' : $diasRestantes . ' dia',
                    '{data}' => $usuario['data_dizimo'],
                    '{site}' => site_url()
                ];
                $mensagem = str_replace(array_keys($dados), array_values($dados), $lembrete_pagamento['mensagem']);
            } else {
                $diasPassados = $diasDiferenca;
                $dados = [
                    '{nome}' => $nome,
                    'number' => $usuario['celular'],
                    '{dias}' => ($diasPassados > 1) ? $diasPassados . ' dias' : $diasPassados . ' dia',
                    '{data}' => $usuario['data_dizimo'],
                    '{site}' => site_url()
                ];
                $mensagem = str_replace(array_keys($dados), array_values($dados), $pagamento_atrasado['mensagem']);
            }

            // Implementação do envio de lembrete (e.g., envio de email ou WhatsApp)
            // Exemplo:
            // mail($usuario['email'], 'Lembrete de Pagamento', $mensagem);
            $whatsApp = new WhatsappLibraries();
            $whatsApp->verifyNumber(['message' => $mensagem], '5562981154120', 'text');
        } else {
            log_message('info', 'NÃO ENVIOU: Nome ou razão social não disponível para o usuário ' . json_encode($usuario));
        }
    }



    public function transacoes($input = false, $limit = 10, $order = 'DESC'): array
    {
        $request = service('request');

        // Criação de uma chave de cache única baseada nos parâmetros
        $cacheKey = "transacoesList_{$limit}_{$order}_" . md5(json_encode($input));

        $cache = \Config\Services::cache();

        // Verifica se os dados estão em cache
        if ($data = $cache->get($cacheKey)) {
            return $data;
        }

        $data = [];
        $currentPageTotal = 0;
        $allPagesTotal = 0;

        $search = $input['search'] ?? false;

        $this->select("transacoes.*")
            ->select('usuarios.tipo AS tipo_user, usuarios.email')
            ->join('usuarios', 'usuarios.id = transacoes.id_user')
            ->orderBy('transacoes.id', $order)
            ->limit($limit);

        $modelPastor = new PastoresModel();
        $modelIgreja = new IgrejasModel();

        $transacoes = $this->paginate($limit);

        foreach ($transacoes as $transacao) {
            if ($transacao['tipo_user'] == 'pastor') {
                $rowPastor = $modelPastor->find($transacao['id_cliente']);
                $data[] = [
                    'id'          => $transacao['id'],
                    'nome'        => $rowPastor['nome'] . ' ' . $rowPastor['sobrenome'],
                    'email'       => $transacao['email'],
                    'tipo'        => 'pastor',
                    'id_transacao' => $transacao['id_transacao'],
                    'uf'          => $rowPastor['uf'],
                    'cidade'      => $rowPastor['cidade'],
                    'desc'        => $transacao['descricao'],
                    'data_criado' => formatDate($transacao['created_at']),
                    'data_pag'    => formatDate($transacao['data_pagamento']),
                    'valor'       => decimalParaReaisBrasil($transacao['valor']),
                    'status'      => $transacao['status_text'],
                    'forma_pg'    => $transacao['tipo_pagamento'],
                    'url'         => site_url("admin/pastor/{$transacao['id_cliente']}"),
                    'descricao_lg' => $transacao['descricao_longa']
                ];

                if ($transacao['status_text'] == 'Pago') {
                    $currentPageTotal += $transacao['valor'];
                }
            } elseif ($transacao['tipo_user'] == 'igreja') {
                $rowIgreja = $modelIgreja->find($transacao['id_cliente']);
                $data[] = [
                    'id'          => intval($transacao['id']),
                    'nome'        => $rowIgreja['razao_social'],
                    'email'       => $transacao['email'],
                    'tipo'        => 'igreja',
                    'id_transacao' => $transacao['id_transacao'],
                    'uf'          => $rowIgreja['uf'],
                    'cidade'      => $rowIgreja['cidade'],
                    'desc'        => $transacao['descricao'],
                    'data_criado' => formatDate($transacao['created_at']),
                    'data_pag'    => formatDate($transacao['data_pagamento']),
                    'valor'       => decimalParaReaisBrasil($transacao['valor']),
                    'status'      => $transacao['status_text'],
                    'forma_pg'    => $transacao['tipo_pagamento'],
                    'url'         => site_url("admin/igreja/{$transacao['id_cliente']}"),
                    'descricao_lg' => $transacao['descricao_longa']
                ];

                if ($transacao['status_text'] == 'Pago') {
                    $currentPageTotal += $transacao['valor'];
                }
            }
        }

        $allPagesTotalResult = $this->select('SUM(valor) AS total')
            ->where('status_text', 'Pago')
            ->get()
            ->getRow();
        $allPagesTotal = $allPagesTotalResult->total;

        $totalResults = $this->countAllResults();
        $currentPage = $request->getGet('page') ?? 1;
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        $resultCount = count($transacoes);
        if ($search) {
            $numMessage = $resultCount === 1 ? "1 resultado encontrado." : "{$resultCount} resultados encontrados.";
        } else {
            $numMessage = "Exibindo resultados {$start} a {$end} de {$totalResults}.";
        }

        $result = [
            'rows' => $data,
            'pager' => $this->pager->links('default', 'paginate'),
            'num' => $numMessage,
            'currentPageTotal' => decimalParaReaisBrasil($currentPageTotal),
            'allPagesTotal' => decimalParaReaisBrasil($allPagesTotal)
        ];

        helper('auxiliar');
        // Armazena os dados no cache
        $cache->save($cacheKey, $result, getCacheExpirationTimeInSeconds(7)); // Cache por 5 minutos (300 segundos)

        return $result;
    }



    public function listTransacaoUsuario($id, $input = false, $limit = 10, $order = 'DESC')
    {
        helper('auxiliar');
        $page = $input['page'] ?? false;

        $data = [];
        $currentPageTotal = 0; // Soma dos valores da página atual
        $allPagesTotal = 0; // Soma dos valores de todas as páginas da consulta atual

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        // Prepara a consulta
        $this->select("transacoes.*")
            ->select('usuarios.tipo AS tipo_user')
            ->where('transacoes.id_user', $id)
            ->join('usuarios', 'usuarios.id = transacoes.id_user')
            ->orderBy('transacoes.id', $order);

        if ($search) {
            $this->groupStart()
                ->like('transacoes.status_text', $search)
                ->orLike('transacoes.tipo_pagamento', $search)
                ->orLike('transacoes.descricao', $search)
                ->orLike('transacoes.descricao_longa', $search)
                ->orLike('transacoes.id', $search)
                ->groupEnd();
        }

        // Pagina os resultados
        $transacoes = $this->paginate($limit);

        // Instancia os modelos
        $modelPastor = new PastoresModel();
        $modelIgreja = new IgrejasModel();
        $modelReembolso = new ReembolsosModel();

        foreach ($transacoes as $transacao) {
            if ($transacao['tipo_user'] == 'pastor') {
                $rowPastor = $modelPastor->find($transacao['id_cliente']);
                $rowRembolso = $modelReembolso->where('id_transacao', $transacao['id'])->first();

                if ($rowRembolso) {
                    $dataTransacao = $rowRembolso;
                } else {
                    $dataTransacao = [];
                }
                $data[] = [
                    'id'          => $transacao['id'],
                    'nome'        => $rowPastor['nome'] . ' ' . $rowPastor['sobrenome'],
                    'tipo'        => 'Pastor',
                    'id_transacao' => $transacao['id_transacao'],
                    'uf'          => $rowPastor['uf'],
                    'cidade'      => $rowPastor['cidade'],
                    'desc'        => $transacao['descricao'],
                    'data_criado' => formatDate($transacao['created_at']),
                    'data_pag'    => formatDate($transacao['data_pagamento']),
                    'valor'       => decimalParaReaisBrasil($transacao['valor']),
                    'status'      => $transacao['status_text'],
                    'forma_pg'    => $transacao['tipo_pagamento'],
                    'descricao_lg' => $transacao['descricao_longa'],
                    'reembolso' => $dataTransacao
                ];

                if ($transacao['status_text'] == 'Pago') {
                    // Adiciona o valor da transação à soma da página atual
                    $currentPageTotal += $transacao['valor'];
                }
            } elseif ($transacao['tipo_user'] == 'igreja') {
                $rowIgreja = $modelIgreja->find($transacao['id_cliente']);

                $rowRembolso = $modelReembolso->select('descricao, created_at')->where('id_transacao', $transacao['id'])->first();

                if ($rowRembolso) {
                    $dataTransacao = $rowRembolso;
                } else {
                    $dataTransacao = null;
                }

                $data[] = [
                    'id'          => intval($transacao['id']),
                    'nome'        => $rowIgreja['razao_social'],
                    'tipo'        => 'Igreja',
                    'id_transacao' => $transacao['id_transacao'],
                    'uf'          => $rowIgreja['uf'],
                    'cidade'      => $rowIgreja['cidade'],
                    'desc'        => $transacao['descricao'],
                    'data_criado' => formatDate($transacao['created_at']),
                    'data_pag'    => formatDate($transacao['data_pagamento']),
                    'valor'       => decimalParaReaisBrasil($transacao['valor']),
                    'status'      => $transacao['status_text'],
                    'forma_pg'    => $transacao['tipo_pagamento'],
                    'descricao_lg' => $transacao['descricao_longa'],
                    'reembolso' => $dataTransacao
                ];

                if ($transacao['status_text'] == 'Pago') {
                    // Adiciona o valor da transação à soma da página atual
                    $currentPageTotal += $transacao['valor'];
                }
            }
        }

        // Calcula a soma dos valores de todas as páginas da consulta atual
        $this->resetQuery(); // Reseta a consulta para calcular a soma de todas as páginas
        $allPagesTotal = $this->selectSum('valor')->where('transacoes.id_user', $id)->get()->getRow()->valor;

        // Paginação dos resultados
        $totalResults = $this->where('transacoes.id_user', $id)->countAllResults();
        $currentPage = $this->pager->getCurrentPage();
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($transacoes);
        if ($search) {
            $numMessage = $resultCount === 1 ? "1 resultado encontrado." : "{$resultCount} resultados encontrados.";
        } else {
            $numMessage = "Exibindo resultados {$start} a {$end} de {$totalResults}.";
        }

        $result = [
            'rows' => $data,
            'pager' => $this->pager->links('default', 'paginate'),
            'num' => $numMessage,
            'currentPageTotal' => decimalParaReaisBrasil($currentPageTotal),
            'allPagesTotal' => decimalParaReaisBrasil($allPagesTotal) // Certifique-se de formatar a soma total
        ];

        return $result;
    }


    public function listSearchUsers($input = false, $limit = 10, $order = 'DESC')
    {
        helper('auxiliar');

        $page = $input['page'] ?? false;

        if ($page) {
            $cache = \Config\Services::cache();

            $search = $input['search'] ?? false;
            $currentPage = $page;
            $userId = session('data')['id_perfil'];
            $cacheKey = "transacoes_{$userId}_{$search}_{$limit}_{$order}_{$currentPage}";

            // Check if the cache exists
            if ($cacheData = $cache->get($cacheKey)) {
                return $cacheData;
            }
        }


        $data = array();
        $currentPageTotal = 0; // Soma dos valores da página atual
        $allPagesTotal = 0; // Soma dos valores de todas as páginas da consulta atual

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        $this->select("transacoes.*")
            ->select('usuarios.tipo AS tipo_user')
            ->where('transacoes.id_user', session('data')['id'])
            ->join('usuarios', 'usuarios.id = transacoes.id_user');

        $this->orderBy('transacoes.id', $order);

        if ($search) {
            $this->groupStart()
                ->like('transacoes.status_text', $search)
                ->orLike('transacoes.tipo_pagamento', $search)
                ->orLike('transacoes.descricao', $search)
                ->orLike('transacoes.descricao_longa', $search)
                ->orLike('transacoes.id', $search)
                ->groupEnd();
        }

        $transacoes = $this->paginate($limit);

        $modelPastor = new PastoresModel();
        $modelIgreja = new IgrejasModel();

        foreach ($transacoes as $transacao) {

            if ($transacao['tipo_user'] == 'pastor') {
                $rowPastor = $modelPastor->find($transacao['id_cliente']);
                $data[] = [
                    'id'          => $transacao['id'],
                    'nome'        => $rowPastor['nome'] . ' ' . $rowPastor['sobrenome'],
                    'tipo'        => 'Pastor',
                    'uf'          => $rowPastor['uf'],
                    'cidade'      => $rowPastor['cidade'],
                    'desc'        => $transacao['descricao'],
                    'data_criado' => formatDate($transacao['created_at']),
                    'data_pag'    => formatDate($transacao['data_pagamento']),
                    'valor'       => decimalParaReaisBrasil($transacao['valor']),
                    'status'      => $transacao['status_text'],
                    'forma_pg'    => $transacao['tipo_pagamento'],
                    'descricao_lg' => $transacao['descricao_longa']
                ];

                // Adiciona o valor da transação à soma da página atual
                $currentPageTotal += $transacao['valor'];
            }

            if ($transacao['tipo_user'] == 'igreja') {
                $rowPastor = $modelIgreja->find($transacao['id_cliente']);
                $data[] = [
                    'id'          => intval($transacao['id']),
                    'nome'        => $rowPastor['razao_social'],
                    'tipo'        => 'Igreja',
                    'uf'          => $rowPastor['uf'],
                    'cidade'      => $rowPastor['cidade'],
                    'desc'        => $transacao['descricao'],
                    'data_criado' => formatDate($transacao['created_at']),
                    'data_pag'    => formatDate($transacao['data_pagamento']),
                    'valor'       => decimalParaReaisBrasil($transacao['valor']),
                    'status'      => $transacao['status_text'],
                    'forma_pg'    => $transacao['tipo_pagamento'],
                    'descricao_lg' => $transacao['descricao_longa']
                ];

                // Adiciona o valor da transação à soma da página atual
                $currentPageTotal += $transacao['valor'];
            }
        };

        // Calcula a soma dos valores de todas as páginas da consulta atual
        $allPagesTotalQuery = $this->where('transacoes.id_cliente', session('data')['id_perfil']);
        $allPagesTotal = $allPagesTotalQuery->selectSum('valor')->find();

        // Paginação dos resultados
        $totalResults = $this->where('transacoes.id_cliente', session('data')['id_perfil'])->countAllResults();
        $currentPage = $this->pager->getCurrentPage();
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($transacoes);
        if ($search) {
            if ($resultCount === 1) {
                $numMessage = "1 resultado encontrado.";
            } else {
                $numMessage = "{$resultCount} resultados encontrados.";
            }
        } else {
            $numMessage = "Exibindo resultados {$start} a {$end} de {$totalResults}.";
        }

        $result = [
            'rows' => $data,
            'pager' => $this->pager->links('default', 'paginate'),
            'num' => $numMessage,
            'currentPageTotal' => decimalParaReaisBrasil($currentPageTotal),
            'allPagesTotal' => decimalParaReaisBrasil($allPagesTotal[0]['valor'])
        ];

        if ($page) {
            // Save the result to cache
            $cache->save($cacheKey, $result, 3600); // Cache for 1 hour
        }


        return $result;
    }

    public function dashCredito($dateIn = null, $dateOut = null)
    {
        return $this->dashPaymentType('Credito', $dateIn, $dateOut);
    }

    public function dashDebito($dateIn = null, $dateOut = null)
    {
        return $this->dashPaymentType('Debito', $dateIn, $dateOut);
    }

    public function dashBoletos($dateIn = null, $dateOut = null)
    {
        return $this->dashPaymentType('Boleto', $dateIn, $dateOut);
    }

    public function dashPix($dateIn = null, $dateOut = null)
    {
        return $this->dashPaymentType('Pix', $dateIn, $dateOut);
    }

    private function dashPaymentType($type, $dateIn = null, $dateOut = null)
    {
        if ($dateIn && $dateOut) {
            $this->where([
                'data_pagamento >=' => $dateIn,
                'data_pagamento <=' => $dateOut
            ]);
        } else {
            $this->like('data_pagamento', date('Y-m'));
        }

        $this->where('status_text', 'Pago');
        $this->where('tipo_pagamento', $type);
        $this->selectSum('valor');
        return $this->first();
    }

    public function dashMensal()
    {
        $this->like('data_pagamento', date('Y-m'));
        $this->where('status_text', 'Pago');
        $this->selectSum('valor');
        return $this->first();
    }

    public function dashAnual()
    {
        $this->like('data_pagamento', date('Y'));
        $this->where('status_text', 'Pago');
        $this->selectSum('valor');
        return $this->first();
    }

    public function dashTotal()
    {
        $this->where('status_text', 'Pago');
        $this->selectSum('valor');
        return $this->first();
    }

    public function dashCreditoAnterior()
    {
        return $this->dashPaymentTypeAnterior('Credito');
    }

    public function dashDebitoAnterior()
    {
        return $this->dashPaymentTypeAnterior('Debito');
    }

    public function dashBoletosAnterior()
    {
        return $this->dashPaymentTypeAnterior('Boleto');
    }

    public function dashPixAnterior()
    {
        return $this->dashPaymentTypeAnterior('Pix');
    }

    private function dashPaymentTypeAnterior($type)
    {
        $previousMonth = date('Y-m', strtotime('first day of last month'));
        $this->like('data_pagamento', $previousMonth);
        $this->where('status_text', 'Pago');
        $this->where('tipo_pagamento', $type);
        $this->selectSum('valor');
        return $this->first();
    }

    public function dashMensalAnterior()
    {
        $previousMonth = date('Y-m', strtotime('first day of last month'));
        $this->like('data_pagamento', $previousMonth);
        $this->where('status_text', 'Pago');
        $this->selectSum('valor');
        return $this->first();
    }

    public function dashAnualAnterior()
    {
        $previousYear = date('Y', strtotime('first day of January last year'));
        $this->like('data_pagamento', $previousYear);
        $this->where('status_text', 'Pago');
        $this->selectSum('valor');
        return $this->first();
    }
}
