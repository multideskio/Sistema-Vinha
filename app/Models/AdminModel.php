<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'administracao';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'cnpj',
        'empresa',
        'logo',
        'email',
        'cep',
        'uf',
        'cidade',
        'bairro',
        'complemento',
        'telefone',
        'celular',
        'email_remetente',
        'nome_remetente',
        'url_api',
        'instance_api',
        'key_api'
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
    protected $afterInsert    = ["updateCache"];
    protected $beforeUpdate   = ["limpaStrings", "filterHtml"];
    protected $afterUpdate    = ["updateCache"];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ["updateCache"];

    protected function filterHtml(array $data)
    {
        return esc($data);
    }
    protected function updateCache(){
        $cache = \Config\Services::cache();
        $cache->delete('config_Cache');

        /*$dbutil = \Config\Database::utils();
        $dbutil->optimizeTable('administracao');*/
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
        
        return $data;
    }

    //Cache dados de config
    public function cacheData()
    {
        helper('auxiliar');
        $cache = \Config\Services::cache();
        if (!$cache->get('config_Cache')) {
            $builder = $this->select('id, cnpj, empresa, logo, email, cep, uf, cidade, bairro, complemento, telefone, celular')->find(1);
            // Save into the cache for 365 days
            $cache->save('config_Cache', $builder, getCacheExpirationTimeInSeconds(365));
        }else{
            $builder = $cache->get('config_Cache');
        }
        return $builder;
    }
}
