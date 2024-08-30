<?php

namespace App\Models;

use CodeIgniter\Model;

class GerentesModel extends Model
{
    protected $table            = "gerentes";
    protected $primaryKey       = "id";
    protected $useAutoIncrement = true;
    protected $returnType       = "array";
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "id",
        "nome",
        "sobrenome",
        "cpf",
        "foto",
        "uf",
        "cidade",
        "cep",
        "complemento",
        "bairro",
        "data_dizimo",
        "telefone",
        "celular",
        "facebook",
        "instagram",
        "id_user",
        "id_adm",
        "website"
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = "datetime";
    protected $createdField  = "created_at";
    protected $updatedField  = "updated_at";
    protected $deletedField  = "deleted_at";

    // Validation
    /*protected $validationRules      = [
        "email" => "is_unique[gerentes.email]"
    ];
    protected $validationMessages   = [
        'email' => [
            "is_unique" => "Já um gerente utilizando este endereço de e-mail. Por favor, informe outro endereço de email."
        ]
    ];*/
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ["filterHtml", "limpaStrings"];
    protected $afterInsert    = ["clearCache"];
    protected $beforeUpdate   = ["filterHtml", "limpaStrings"];
    protected $afterUpdate    = ["clearCache"];
    protected $beforeFind     = [];
    protected $afterFind      = ["filterHtml"];
    protected $beforeDelete   = [];
    protected $afterDelete    = ["clearCache"];

    protected function clearCache(array $data): array
    {
        $cache = service('cache');
        $cache->delete("gerentes_Cache");
        $cache->deleteMatching("gerentesList_" . "*");

        return $data;
    }

    protected function filterHtml(array $data)
    {
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


    //Cache dados de config
    public function cacheData()
    {
        helper("auxiliar");
        $cache = \Config\Services::cache();
        if (!$cache->get("gerentes_Cache")) {
            $builder = $this->orderBy('id', 'DESC')->findAll();
            // Save into the cache for 365 days
            $cache->save("gerentes_Cache", $builder, getCacheExpirationTimeInSeconds(365));
        } else {
            $builder = $cache->get("gerentes_Cache");
        }
        return $builder;
    }

    public function paginates($limit, $group = null, $offset = 0)
    {
        // Aqui você pode aplicar quaisquer filtros ou ordenações necessárias

        return $this->orderBy('id', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }


    public function listSearch($input = false, $limit = 12, $order = 'DESC'): array
    {
        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;
        $page   = $input['page'] ?? 1;
        $searchCache = preg_replace('/[^a-zA-Z0-9]/', '', $search);

        // Gera uma chave de cache única baseada nos parâmetros de entrada
        $cacheKey = "gerentesList_{$searchCache}_{$limit}_{$order}_{$page}";

        // Verifica se os resultados já estão no cache
        $cachedData = cache()->get($cacheKey);

        if ($cachedData) {
            // Retorna os dados do cache
            return $cachedData;
        }

        // Configuração inicial da query
        $this->orderBy('gerentes.id', $order)
            ->select('gerentes.*')
            ->select('usuarios.email AS email, usuarios.id AS id_login')
            ->where('usuarios.tipo', 'gerente')
            ->join('usuarios', 'usuarios.id_perfil = gerentes.id');

        // Adiciona condições de busca se o termo estiver presente
        if ($search) {
            $this->groupStart()
                ->like('gerentes.id', $search)
                ->orLike('gerentes.nome', $search)
                ->orLike('gerentes.sobrenome', $search)
                ->orLike('gerentes.cpf', $search)
                ->orLike('usuarios.email', $search)
                ->groupEnd();
        }

        // Paginação dos resultados
        $gerentes = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage = $this->pager->getCurrentPage();
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($gerentes);
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
            'rows'  => $gerentes, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage
        ];

        // Armazena os resultados no cache por 10 minutos (600 segundos)
        cache()->save($cacheKey, $data, getCacheExpirationTimeInSeconds(1));

        return $data;
    }
}
