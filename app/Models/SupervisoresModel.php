<?php

namespace App\Models;

use CodeIgniter\Model;

class SupervisoresModel extends Model
{
    protected $table            = 'supervisores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'id_adm',
        'id_user',
        'id_regiao',
        'id_gerente',
        'nome',
        'sobrenome',
        'cpf',
        'email',
        'foto',
        'uf',
        'cidade',
        'cep',
        'complemento',
        'bairro',
        'data_dizimo',
        'telefone',
        'celular',
        'facebook',
        'instagram',
        'id_user',
        'id_adm'
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
    protected $beforeInsert   = ["filterHtml", "limpaStrings"];
    protected $afterInsert    = ["updateCache"];
    protected $beforeUpdate   = ["limpaStrings", "filterHtml"];
    protected $afterUpdate    = ["updateCache"];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ["updateCache"];


    protected function updateCache()
    {
        $cache = \Config\Services::cache();
        $cache->delete("supervisores_Cache");
    }
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



    /**
     * This PHP function caches data related to supervisors, regions, and managers for efficient
     * retrieval.
     * 
     * @return The `cacheData` function is returning the data of supervisors along with their region
     * and manager names. This data is retrieved from the database and stored in the cache for 365
     * days. If the data is already cached, it is directly fetched from the cache. The function returns
     * the builder object containing the supervisor data.
     */
    public function cacheData()
    {
        helper("auxiliar");
        $cache = \Config\Services::cache();
        if (!$cache->get("supervisores_Cache")) {

            $builder = $this->select('supervisores.*, regioes.nome as regiao_nome, gerentes.nome as gerente_nome')
                ->join('regioes',  'supervisores.id_regiao  = regioes.id', 'left')
                ->join('gerentes', 'supervisores.id_gerente = gerentes.id', 'left')
                ->findAll();

            // Save into the cache for 365 days
            $cache->save("supervisores_Cache", $builder, getCacheExpirationTimeInSeconds(365));
        } else {
            $builder = $cache->get("supervisores_Cache");
        }

        return $builder;
    }

    /**
     * This PHP function lists search results for supervisors with optional input parameters.
     * 
     * @param input The `listSearch` function is a method that performs a search operation on a
     * database table named `supervisores` with optional input parameters.
     * 
     * @return An array containing the 'rows' key with paginated search results and the 'pager' key
     * with pagination links.
     */
    public function listSearch($input = false, $limit = 10, $order = 'DESC'): array
    {

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        $this->orderBy('supervisores.id', $order)
            ->where('usuarios.tipo', 'supervisor')
            ->select('supervisores.*')
            ->select('usuarios.email as email')
            ->select('regioes.nome as regiao_nome')
            ->select('gerentes.nome as gerente_nome, gerentes.sobrenome as gerente_sobrenome')
            ->join('regioes',  'supervisores.id_regiao  = regioes.id', 'left')
            ->join('gerentes', 'supervisores.id_gerente = gerentes.id', 'left')
            ->join('usuarios', 'supervisores.id = usuarios.id_perfil', 'left');

        if ($search) {
            $this->groupStart()
                ->like('supervisores.nome', $search)
                ->orLike('supervisores.sobrenome', $search)
                ->orLike('supervisores.telefone', $search)
                ->orLike('supervisores.cpf', $search)
                ->orLike('supervisores.id', $search)
                ->orLike('usuarios.email', $search)
                ->groupEnd();
        }

        // Paginação dos resultados
        $supervisorores = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage = $this->pager->getCurrentPage();
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($supervisorores);
        if ($search) {
            if ($resultCount === 1) {
                $numMessage = "1 resultado encontrado.";
            } else {
                $numMessage = "{$resultCount} resultados encontrados.";
            }
        } else {
            $numMessage = "Exibindo resultados {$start} a {$end} de {$totalResults}.";
        }

        return [
            'rows'  => $supervisorores, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage
        ];
    }

    public function listFull()
    {
        return $this->findAll();
    }
}
