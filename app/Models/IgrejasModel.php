<?php

namespace App\Models;

use CodeIgniter\Model;

class IgrejasModel extends Model
{
    protected $table            = "igrejas";
    protected $primaryKey       = "id";
    protected $useAutoIncrement = true;
    protected $returnType       = "array";
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "id_adm", "id_user", "id_supervisor", "nome_tesoureiro", "sobrenome_tesoureiro", "cpf_tesoureiro", "fundacao", "razao_social", "fantasia", "cnpj", "foto", "uf", "cidade", "cep", "complemento", "bairro", "data_dizimo", "telefone", "celular", "facebook", "instagram"
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = "datetime";
    protected $createdField  = "created_at";
    protected $updatedField  = "updated_at";
    protected $deletedField  = "deleted_at";

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ["filterHtml", "limpaStrings"];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ["filterHtml", "limpaStrings"];
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

        if (array_key_exists('cnpj', $data['data'])) {
            $data['data']['cnpj'] = limparString($data['data']['cnpj']);
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

        if (array_key_exists('cpf_tesoureiro', $data['data'])) {
            $data['data']['cpf_tesoureiro'] = limparString($data['data']['cpf_tesoureiro']);
        }

        return $data;
    }

    public function listSearch($input = false, $limit = 10, $order = 'DESC'): array
    {
        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        $this->orderBy('igrejas.id', $order)
            ->where('usuarios.tipo', 'igreja')
            ->select('igrejas.*')
            ->select('supervisores.nome AS nome_supervisor, supervisores.sobrenome AS sobre_supervisor')
            ->select('gerentes.nome AS nome_gerente, gerentes.sobrenome AS sobre_gerente')
            ->select('regioes.nome AS regiao')
            ->select('usuarios.email AS email')
            ->join('usuarios', 'usuarios.id_perfil = igrejas.id')
            ->join('supervisores', 'supervisores.id = igrejas.id_supervisor')
            ->join('gerentes', 'gerentes.id = supervisores.id_gerente')
            ->join('regioes', 'regioes.id = supervisores.id_regiao');

        // Adiciona condições de busca se o termo estiver presente
        if ($search) {
            $this->groupStart()
                ->like('igrejas.razao_social', $search)
                ->orLike('igrejas.id', $search)
                ->orLike('igrejas.fantasia', $search)
                ->orLike('igrejas.cnpj', $search)
                ->orLike('igrejas.cidade', $search)
                ->orLike('gerentes.nome', $search)
                ->orLike('gerentes.sobrenome', $search)
                ->orLike('supervisores.nome', $search)
                ->orLike('supervisores.sobrenome', $search)
                ->orLike('usuarios.email', $search)
                ->orLike('regioes.nome', $search)
                ->groupEnd();
        }

        // Paginação dos resultados
        $igrejas = $this->paginate($limit);
        $totalResults = $this->countAllResults();
        $currentPage = $this->pager->getCurrentPage();
        $start = ($currentPage - 1) * $limit + 1;
        $end = min($currentPage * $limit, $totalResults);

        // Lógica para definir a mensagem de resultados
        $resultCount = count($igrejas);
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
            'rows'  => $igrejas, // Resultados paginados
            'pager' => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'   => $numMessage
        ];
    }
}
