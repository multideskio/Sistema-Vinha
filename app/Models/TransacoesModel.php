<?php

namespace App\Models;

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
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function updateStatus()
    {
        return true;
    }

    public function listTransacaoUsuario($id, $input = false, $limit = 10, $order = 'DESC')
    {
        helper('auxiliar');
        $page = $input['page'] ?? false;
        /*if ($page) {
            $cache = \Config\Services::cache();

            $search = $input['search'] ?? false;
            $currentPage = $page;
            $userId = session('data')['id_perfil'];
            $cacheKey = "transacoes_adm_{$userId}_{$search}_{$limit}_{$order}_{$currentPage}";

            // Check if the cache exists
            if ($cacheData = $cache->get($cacheKey)) {
                return $cacheData;
            }
        }*/

        $data = array();
        $currentPageTotal = 0; // Soma dos valores da página atual
        $allPagesTotal = 0; // Soma dos valores de todas as páginas da consulta atual

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        $this->select("transacoes.*")
            ->select('usuarios.tipo AS tipo_user')
            ->where('transacoes.id_cliente', $id)
            //->where('transacoes.id_user', session('data')['id'])
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
                    'id_transacao' => $transacao['id_transacao'],
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
                    'id_transacao' => $transacao['id_transacao'],
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

        /*if ($page) {
            // Save the result to cache
            $cache->save($cacheKey, $result, 3600); // Cache for 1 hour
        }*/


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
