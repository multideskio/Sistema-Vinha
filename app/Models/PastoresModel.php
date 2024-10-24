<?php

namespace App\Models;

use CodeIgniter\Model;

class PastoresModel extends Model
{
    protected $table            = 'pastores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_adm',
        'id_user',
        'id_supervisor',
        'nome',
        'sobrenome',
        'cpf',
        'nascimento',
        'foto',
        'uf',
        'cidade',
        'cep',
        'complemento',
        'bairro',
        "pais",
        "rua",
        "numero",
        'data_dizimo',
        'telefone',
        'celular',
        'facebook',
        'instagram',
        'website',
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
    protected $beforeInsert   = ["limpaStrings", "filterHtml"];
    protected $afterInsert    = ['clearCache'];
    protected $beforeUpdate   = ["filterHtml", "limpaStrings"];
    protected $afterUpdate    = ['clearCache'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ['clearCache'];

    protected function filterHtml(array $data)
    {
        // Verifica se o array $data['data'] existe e se possui elementos
        return esc($data);
    }

    protected function limpaStrings(array $data)
    {
        helper('auxiliar');

        if (array_key_exists('cpf', $data['data'])) {
            $data['data']['cpf'] = limparString($data['data']['cpf']);
        }

        if (array_key_exists('cep', $data['data'])) {
            $data['data']['cep'] = limparString($data['data']['cep']);
        }

        if (array_key_exists('telefone', $data['data'])) {
            $data['data']['telefone'] = limparString($data['data']['telefone']);
        }

        if (array_key_exists('celular', $data['data'])) {
            $data['data']['celular'] = limparString($data['data']['celular']);
        }

        return $data;
    }

    protected function clearCache(array $data): array
    {
        $cache = service('cache');
        $cache->deleteMatching("pastoresList_" . "*");

        return $data;
    }

    public function listSearch($input = false, $limit = 12, $order = 'DESC'): array
    {
        $data = [];

        // Define o termo de busca, se houver
        $search      = $input['search'] ?? false;
        $page        = $input['page']   ?? false;
        $searchCache = preg_replace('/[^a-zA-Z0-9]/', '', $search);

        // Gera uma chave de cache única baseada nos parâmetros de entrada
        $cacheKey = "pastoresList_{$searchCache}_{$limit}_{$order}_{$page}";

        // Verifica se os resultados já estão no cache
        $cachedData = cache()->get($cacheKey);

        if ($cachedData) {
            // Retorna os dados do cache
            return $cachedData;
        }

        // Configuração inicial da query
        $this->orderBy('pastores.id', $order)
            ->where('usuarios.tipo', 'pastor')
            ->select('pastores.*')
            ->select('supervisores.nome AS nome_supervisor, supervisores.sobrenome AS sobre_supervisor')
            ->select('gerentes.nome AS nome_gerente, gerentes.sobrenome AS sobre_gerente')
            ->select('regioes.nome AS regiao')
            ->select('usuarios.email AS email')
            ->join('usuarios', 'pastores.id = usuarios.id_perfil', 'left')
            ->join('supervisores', 'pastores.id_supervisor = supervisores.id', 'left')
            ->join('gerentes', 'supervisores.id_gerente = gerentes.id', 'left')
            ->join('regioes', 'supervisores.id_regiao = regioes.id', 'left');

        // Adiciona condições de busca se o termo estiver presente
        if ($search) {
            $this->groupStart()
                ->like('pastores.nome', $search)
                ->orLike('pastores.id', $search)
                ->orLike('pastores.sobrenome', $search)
                ->orLike('pastores.cpf', $search)
                ->orLike('usuarios.email', $search)
                ->orLike('regioes.nome', $search)
                ->groupEnd();
        }

        // Paginação dos resultados
        $pastores     = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage  = $this->pager->getCurrentPage();
        $start        = ($currentPage - 1) * $limit + 1;
        $end          = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($pastores);

        if ($search) {
            if ($resultCount === 1) {
                $numMessage = "1 resultado encontrado.";
            } else {
                $numMessage = "{$resultCount} resultados encontrados.";
            }
        } else {
            $numMessage = "Exibindo resultados {$start} a {$end} de {$totalResults}.";
        }

        //
        $data = [
            'rows'  => $pastores, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage,
        ];

        helper('auxiliar');
        // Armazena os resultados no cache por 10 minutos (600 segundos)
        cache()->save($cacheKey, $data, getCacheExpirationTimeInSeconds(1));

        return $data;
    }
}
