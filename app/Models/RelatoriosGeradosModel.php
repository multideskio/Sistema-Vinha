<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatoriosGeradosModel extends Model
{
    protected $table            = 'relatorios_gerados';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_adm',
        'id_user',
        'nome_arquivo',
        'url_download',
        'parametros_busca'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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
    protected $afterInsert    = ['clearCache'];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = ['clearCache'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ['clearCache'];


    protected function clearCache(array $data): array{
        cache()->deleteMatching("*_listSearchRelatorio") ;
        return $data;
    }

    public function listSearch($input = false, $limit = 15, $order = 'DESC')
    {
        $data = [];

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;
        $page = $input['page'] ?? 1;

        // Gera uma chave de cache única baseada nos parâmetros
        $cacheKey = "{$search}_{$page}_{$limit}_{$order}_listSearchRelatorio";

        // Verifica se os resultados já estão no cache
        $cachedData = cache()->get($cacheKey);

        if ($cachedData) {
            // Retorna os dados do cache
            return $cachedData;
        }

        // Configura a ordenação
        $this->orderBy('id', $order);

        // Executa a consulta paginada
        $relatorios = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage = $this->pager->getCurrentPage();
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($relatorios);
        if ($search) {
            if ($resultCount === 1) {
                $numMessage = "1 resultado encontrado.";
            } else {
                $numMessage = "{$resultCount} resultados encontrados.";
            }
        } else {
            $numMessage = "Exibindo resultados {$start} a {$end} de {$totalResults}.";
        }

        $data = [
            'rows'  => $relatorios, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage
        ];

        // Armazena os resultados no cache por 10 minutos (600 segundos)
        cache()->save($cacheKey, $data, 600);

        return $data;
    }


    public function listSearch00($input = false, $limit = 15, $order = 'DESC')
    {
        $data = [];

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;
        $page   = $input['page']   ?? false;

        $this->orderBy('id', $order);

        $relatorios = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage = $this->pager->getCurrentPage();
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($relatorios);
        if ($search) {
            if ($resultCount === 1) {
                $numMessage = "1 resultado encontrado.";
            } else {
                $numMessage = "{$resultCount} resultados encontrados.";
            }
        } else {
            $numMessage = "Exibindo resultados {$start} a {$end} de {$totalResults}.";
        }

        $data = [
            'rows'  => $relatorios, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage
        ];

        return $data;
    }
}
