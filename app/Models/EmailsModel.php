<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailsModel extends Model
{
    protected $table            = 'emails';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'tipo',
        'mensagem',
        'id_user',
        'id_adm',
        'email_remetente',
        'nome_remetente'
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

    protected function updateCache(){
        $cache = \Config\Services::cache();
        $cache->delete('emails_Cache');
    }

    //Cache dados de config
    public function cacheData()
    {
        helper('auxiliar');
        $cache = \Config\Services::cache();
        if (!$cache->get('emails_Cache')) {
            $builder = $this->select('id, tipo, mensagem')->findAll();
            // Save into the cache for 365 days
            $cache->save('emails_Cache', $builder, getCacheExpirationTimeInSeconds(365));
        }else{
            $builder = $cache->get('emails_Cache');
        }
        return $builder;
    }
}
