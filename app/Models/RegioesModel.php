<?php

namespace App\Models;

use CodeIgniter\Model;

class RegioesModel extends Model
{
    protected $table            = 'regioes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
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
    protected $validationRules      = [];

    protected $validationMessages   = [];

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
        $cache = service('cache');
        $cache->delete('regioes');
    }


    protected function filterHtml(array $data)
    {
        return esc($data);
    }


    //Cache dados de config
    /*public function cacheData()
    {
        helper('auxiliar');
        $cache = \Config\Services::cache();
        if (!$cache->get('regioes')) {
            $builder = $this->select('id, nome, descricao')->findAll();
            // Save into the cache for 365 days
            $cache->save('regioes', $builder, getCacheExpirationTimeInSeconds(365));
        } else {
            $builder = $cache->get('regioes');
        }
        return $this->select('id, nome, descricao')->findAll();
    }*/


    public function listSearch($input = false)
    {

        $search = $input['search'] ?? false;

        // Construção da consulta
        $this->where('id_adm', session('data')['idAdm']);

        //Se tiver alguma busca
        if ($search) {
            $this->like('nome', $input['search'])
                ->orLike('descricao', $input['search'])
                ->orLike('id', $input['search']);

            // Executa a consulta e paginação
            $rows  = $this->paginate(5);
            $pager = $this->pager->links('default', 'paginate');

            // Prepara os dados para retorno
            $data = [
                'rows' => $rows,
                'pager' => $pager
            ];
        } else {
            helper('auxiliar');
            $cache = \Config\Services::cache();
            
            if (!$cache->get('regioes')) {
                // Executa a consulta e paginação
                $rows  = $this->paginate(30);
                $pager = $this->pager->links('default', 'paginate');

                // Prepara os dados para retorno
                $data = [
                    'rows' => $rows,
                    'pager' => $pager
                ];

                // Save into the cache for 365 days
                $cache->save('regioes', $data, getCacheExpirationTimeInSeconds(365));
            } else {

                $data = $cache->get('regioes');

            }
        }
        return $data;
    }
}
