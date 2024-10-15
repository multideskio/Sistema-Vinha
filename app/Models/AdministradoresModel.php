<?php

namespace App\Models;

use CodeIgniter\Model;

class AdministradoresModel extends Model
{
    protected $table            = 'administradores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_adm',
        'id_user',
        'id_supervisao',
        'nome',
        'sobrenome',
        'cpf',
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

    protected bool $allowEmptyInserts = true;

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
    protected $afterInsert    = [];
    protected $beforeUpdate   = ["limpaStrings", "filterHtml"];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

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

    public function listSearch($input = false, $limit = 10, $order = 'DESC'): array
    {
        $data = [];

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        //Logica de associação usuário com admin
        $this->orderBy('administradores.id', $order)
            ->select('administradores.*')
            ->select('usuarios.email AS email')
            ->where('usuarios.tipo', 'superadmin')
            ->join('usuarios', 'usuarios.id_perfil = administradores.id');

        // Adiciona condições de busca se o termo estiver presente
        if ($search) {
            $this->groupStart()
                ->like('administradores.id', $search)
                ->orLike('administradores.nome', $search)
                ->orLike('administradores.sobrenome', $search)
                ->orLike('administradores.cpf', $search)
                ->orLike('usuarios.email', $search)
                ->groupEnd();
        }

        // Paginação dos resultados
        $admins       = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage  = $this->pager->getCurrentPage();
        $start        = ($currentPage - 1) * $limit + 1;
        $end          = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($admins);

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
            'rows'  => $admins, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage,
        ];

        return $data;
    }
}
