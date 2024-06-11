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
        "id_adm"
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
    protected $afterInsert    = ["updateCache"];
    protected $beforeUpdate   = ["filterHtml", "limpaStrings"];
    protected $afterUpdate    = ["updateCache"];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ["updateCache"];

    protected function updateCache()
    {
        $cache = \Config\Services::cache();
        $cache->delete("gerentes_Cache");
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
    public function listSearch($input = false, $limit = 10, $order = 'DESC'): array
    {
        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        // Configuração inicial da query
        $this->orderBy('gerentes.id', $order)
            ->select('gerentes.*')
            ->select('usuarios.email AS email')
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
        $gerentes     = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage  = $this->pager->getCurrentPage();
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


        return [
            'rows'  => $gerentes, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage
        ];
    }
    public function listSearch1($input = false, $qtd = 15)
    {
        // Inicializa o modelo de Usuários
        $modelUsuarios = new UsuariosModel();

        // Array que armazenará os dados dos gerentes
        $dataGerentes = [];

        // Extrai o termo de busca do input ou define como false se não houver
        $search = !empty($input['search']) ? $input['search'] : false;

        // Define a quantidade de resultados por página
        $numResult = $qtd;

        // Variável para armazenar os resultados da busca na tabela gerentes
        $gerentesSearch = false;

        /** Ações de busca na tabela gerentes */
        if ($search) {
            // Busca por id, nome, sobrenome ou cpf que correspondam ao termo de busca
            $gerentesSearch = $this->like('id', $search)
                ->orLike('nome', $search)
                ->orLike('sobrenome', $search)
                ->orLike('cpf', $search)
                ->paginate($numResult);

            $gerentes = $gerentesSearch;

            // Se não encontrar resultados, carrega os gerentes normalmente
            if (!count($gerentesSearch)) {
                $gerentes = $this->paginate($numResult);
            }
        } else {
            // Se não houver termo de busca, carrega os gerentes normalmente
            $gerentes = $this->paginate($numResult);
        }

        /** Lista dados da tabela gerentes */
        foreach ($gerentes as $gerente) {
            // Busca usuários com id_perfil igual ao id do gerente e tipo 'gerente'
            $searchUser = $modelUsuarios->where(['id_perfil' => $gerente['id'], 'tipo' => 'gerente'])->findAll();

            // Se houver resultados na busca de gerentes
            if ($gerentesSearch) {
                $dataGerentes[] = [
                    'id_gerente' => $gerente['id'],
                    'foto' => $gerente['foto'],
                    'nome' => $gerente['nome'],
                    'sobrenome' => $gerente['sobrenome'],
                    'cpf' => $gerente['cpf'],
                    'email' => $searchUser[0]['email'],
                    'celular' => $gerente['celular'],
                    'telefone' => $gerente['telefone'],
                    'id' => ''
                ];
            } else {
                // Se não houver resultados na busca de gerentes, busca por email se houver termo de busca
                if ($search) {
                    $searchUser = $modelUsuarios->like('email', $search)
                        ->where(['id_perfil' => $gerente['id'], 'tipo' => 'gerente'])
                        ->findAll();
                }

                // Adiciona dados dos gerentes e usuários encontrados ao array
                if (count($searchUser)) {
                    $dataGerentes[] = [
                        'id_gerente' => $gerente['id'],
                        'foto' => $gerente['foto'],
                        'nome' => $gerente['nome'],
                        'sobrenome' => $gerente['sobrenome'],
                        'cpf' => $gerente['cpf'],
                        'email' => $searchUser[0]['email'],
                        'celular' => $gerente['celular'],
                        'telefone' => $gerente['telefone'],
                        'id' => ''
                    ];
                }
            }
        }

        // Retorna os dados dos gerentes e os links de paginação
        return [
            'rows' => $dataGerentes, // Dados dos gerentes
            'pager' => $this->pager->links('default', 'paginate') // Links de paginação
        ];
    }
}
