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
        'key_api',
        'ativar_wa',
        'ativar_smtp',
        'site',
        'instagram',
        'facebook',
        'smtp_host',
        'smtp_user',
        'smtp_pass',
        'smtp_port',
        'smtp_crypt',
        's3_access_key_id',
        's3_secret_access_key',
        's3_region',
        's3_endpoint',
        's3_bucket_name',
        's3_cdn',
        'prazo_boleto',
        'instrucoes_boleto',
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

    public function cacheData(): array
    {
        return [];
    }

    protected function filterHtml(array $data): array|string
    {
        return esc($data);
    }


    protected function updateCache(): void
    {
        service('cache')->delete('searchCacheDataConfig');
    }

    protected function limpaStrings(array $data): array
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

    public function searchCacheData($id = 1): float|object|int|bool|array|string|null
    {
        $data = false ;
        helper('auxiliar');
        $cache = \Config\Services::cache();

        if (!$cache->get('searchCacheDataConfig')) {
            $data = $this->select('cnpj, empresa, logo, email, cep, uf, cidade, bairro, complemento, telefone, celular')->first();
            $cache->save('searchCacheDataConfig', $data, getCacheExpirationTimeInSeconds(30));
        } else {
            $data = $cache->get('searchCacheDataConfig');
        }

        return $data ;
    }
}