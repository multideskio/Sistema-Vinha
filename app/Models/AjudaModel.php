<?php

namespace App\Models;

use CodeIgniter\Model;

class AjudaModel extends Model
{
    protected $table            = 'ajuda';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_admin', 'id_user', 'slug', 'titulo', 'conteudo', 'tags'
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


    public function lista($input = false, $limit = 10, $order = 'DESC')
    {

        // Define o termo de busca, se houver
        $search = $input['search'] ?? false;

        if($search){
            $this->groupStart()
            ->like('titulo', $search)
            ->orLike('id', $search)
            ->orLike('conteudo', $search)
            ->orLike('tags', $search)
            ->groupEnd();
        }

        // Paginação dos resultados
        $rows            = $this->paginate($limit);
        $totalResults    = $this->countAllResults();
        $currentPage     = $this->pager->getCurrentPage();
        $start           = ($currentPage - 1) * $limit + 1;
        $end             = min($currentPage * $limit, $totalResults);


        helper(['text', 'auxiliar']);

        $data = array();
        foreach($rows as $row){
            $data[] = [
                'id' => $row['id'],
                'titulo' => $row['titulo'],
                'conteudo' => character_limiter(strip_tags($row['conteudo']), 300, '...'),
                'data' => formatDate($row['created_at']),
                'tags' => $row['tags'],
                'slug' => site_url("ajuda/{$row['slug']}") 
            ];
        }

        // Lógica para definir a mensagem de resultados
        $resultCount = count($rows);
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
            'rows'           => $data, // Resultados paginados
            'pager'          => $this->pager->links('default', 'paginate'), // Links de paginação
            'num'            => $numMessage,
        ];
    }

    public function posts(){
        helper('auxiliar');
        
        $cache = \Config\Services::cache();

        if (!$cache->get('config_Cache')) {
            $builder = $this->first();
            $cache->save('config_Cache', $builder, getCacheExpirationTimeInSeconds(30));
        } else {
            $builder = $cache->get('config_Cache');
        }

        return $builder;
    }
}
