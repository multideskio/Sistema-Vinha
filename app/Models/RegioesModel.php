<?php

namespace App\Models;

use CodeIgniter\Model;

class RegioesModel extends Model
{
    protected $table            = 'regioes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'id_adm',
        'id_user',
        'nome',
        'descricao'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        //"nome" => "required|is_unique[regioes.nome]"
        "nome" => "required|max_length[60]|is_unique[regioes.nome]",
        //"descricao" => "required"
    ];

    protected $validationMessages   = [
        "nome" => [
            "is_unique"  => "Já existe uma região cadastrada com esse nome.",
            "required"   => "O campo nome da Região é obrigatório.",
            "max_length" => "O campo nome da região não pode ter mais do que 60 caracteres."
        ],
        /* "descricao" => [
            "required" => "O campo descricao é obrigatório.",
        ]*/
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ["filterHtml"];
    protected $afterInsert    = ["updateCache"];
    protected $beforeUpdate   = ["filterHtml"];
    protected $afterUpdate    = ["updateCache"];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ["updateCache"];



    protected function updateCache()
    {
        $cache = \Config\Services::cache();
        $cache->delete('regioes_Cache');
    }


    protected function filterHtml(array $data)
    {
        return esc($data);
    }


    //Cache dados de config
    public function cacheData()
    {
        helper('auxiliar');
        $cache = \Config\Services::cache();
        if (!$cache->get('regioes_Cache')) {
            $builder = $this->select('id, nome, descricao')->findAll();
            // Save into the cache for 365 days
            $cache->save('regioes_Cache', $builder, getCacheExpirationTimeInSeconds(365));
        } else {
            $builder = $cache->get('regioes_Cache');
        }
        return $builder;
    }

    public function listSearch($input = false)
    {
        // Obtém a instância do serviço de temporização
        $benchmark = \Config\Services::timer();

        // Variável para armazenar o tempo total das consultas
        $totalQueryTime = 0;

        // Define o evento para capturar o tempo de cada consulta
        \CodeIgniter\Events\Events::on('DBQuery', function ($query) use (&$totalQueryTime) {
            $totalQueryTime += $query->getDuration();
        });

        // Começa a contagem do tempo
        $benchmark->start('query_timer');

        // Construção da consulta
        $this->where('id_adm', session('data')['idAdm']);
        if (isset($input['search'])) {
            $this->like('nome', $input['search'])
                ->orLike('descricao', $input['search'])
                ->orLike('id', $input['search']);
        }
        $this->groupBy('id');

        // Executa a consulta e paginação
        $rows = $this->paginate(5);
        $pager = $this->pager->links('default', 'paginate');

        // Para a contagem do tempo
        $benchmark->stop('query_timer');

        // Obtém o tempo de execução total das consultas
        $executionTimeInSeconds = $totalQueryTime;

        // Converte o tempo de execução para milissegundos e formata
        $executionTimeInMilliseconds = number_format($executionTimeInSeconds * 1000, 3) . ' ms';

        // Prepara os dados para retorno
        $data = [
            'rows' => $rows,
            'pager' => $pager,
            'execution_time' => $executionTimeInMilliseconds
        ];

        return $data;
    }
}
