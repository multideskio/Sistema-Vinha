<?php

namespace App\Models;

use CodeIgniter\Model;
use ErrorException;

class UsuariosModel extends Model
{
    protected $table            = "usuarios";
    protected $primaryKey       = "id";
    protected $useAutoIncrement = true;
    protected $returnType       = "array";
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tipo',
        'id_perfil',
        'email',
        'password',
        'token',
        'nivel',
        'confirmado',
        'id_adm',
        'whatsapp'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = "datetime";
    protected $createdField  = "created_at";
    protected $updatedField  = "updated_at";
    protected $deletedField  = "deleted_at";

    // Validation
    protected $validationRules = [
        "password" => "required|min_length[6]",
        "email"    => "required|valid_email|is_unique[usuarios.email,id,{id}]"
    ];

    protected $validationMessages = [
        "password" => [
            "required"   => "Senha obrigatória",
            "min_length" => "Uma senha de pelo menos 6 caracteres"
        ],
        "email" => [
            "is_unique" => "Já existe um usuário do sistema utilizando esse endereço de e-mail"
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ["antesCadastro"];
    protected $afterInsert    = ["updateCache"];
    protected $beforeUpdate   = ["antesCadastro"];
    protected $afterUpdate    = ["updateCache"];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ["updateCache"];

    public function updateUsuario($id, $usuarioData)
    {
        $this->validationRules['email'] = "required|valid_email|is_unique[usuarios.email,id,{$id}]";
        return $this->update($id, $usuarioData);
    }

    protected function antesCadastro(array $data)
    {
        helper("auxiliar");

        $data["data"]["token"] = gera_token();

        if (!empty($data["data"]["password"])) {
            $data["data"]["password"] = password_hash($data["data"]["password"], PASSWORD_BCRYPT);
        }

        return $data;
    }

    protected function updateCache()
    {
        $cache = service('cache');
        $cache->delete("user_cache");
        $cache->delete('listDashboard');

        $deletedItems = $cache->deleteMatching("userlist_" . '*');
    }

    // Caminho: app/Controllers/SeuController.php

    public function listGeral($input = false, $limit = 10, $order = 'DESC')
    {
        $search = $input['search'] ?? false;

        // Chave única para identificar o cache
        $cacheKey = "userlist_{$search}_{$limit}_{$order}";

        // Instância do serviço de cache
        $cache = \Config\Services::cache();

        // Verifica se o cache existe
        if ($data = $cache->get($cacheKey)) {
            // Retorna os dados do cache
            return $data;
        }

        // Se não estiver em cache, processa os dados normalmente
        $data = [];
        $this->orderBy('id', $order);
        $rows = $this->paginate($limit);

        foreach ($rows as $row) {
            $profileData = $this->getProfileData($row['nivel'], $row['id_perfil'], $row['tipo']);
            if ($profileData) {
                $data[] = array_merge(
                    ['id' => $row['id'], 'email' => $row['email']],
                    $profileData
                );
            }
        }

        // Dados processados para serem retornados
        $result = [
            'rows' => $data,
            'pager' => $this->pager->links('default', 'paginate'),
            'num'   => $this->countAllResults() . ' cadastrados encontrados'
        ];

        helper('auxiliar');
        // Armazena os dados processados no cache
        //$cache->save($cacheKey, $result, getCacheExpirationTimeInSeconds(7)); // 300 segundos (5 minutos) de cache

        return $result;
    }


    public function listSearch($input = false, $limit = 10, $order = 'DESC')
    {
        $search = $input['search'] ?? false;
        $usuarios = $search
            ? $this->like('id', $search)
            ->orLike('email', $search)
            ->orLike('tipo', $search)
            ->paginate($limit)
            : $this->paginate($limit);

        $dataUsers = [];
        foreach ($usuarios as $usuario) {
            $profileData = $this->getProfileData($usuario['nivel'], $usuario['id_perfil'], $usuario['tipo'], $search);
            if ($profileData) {
                $dataUsers[] = array_merge(
                    ['id' => $usuario['id'], 'email' => $usuario['email']],
                    $profileData
                );
            }
        }

        return [
            'rows' => $dataUsers,
            'pager' => $this->pager->links('default', 'paginate')
        ];
    }

    private function getProfileData($nivel, $idPerfil, $tipo, $search = null)
    {
        $modelMap = [
            1 => AdministradoresModel::class,
            2 => GerentesModel::class,
            3 => SupervisoresModel::class,
            4 => ($tipo == 'pastor') ? PastoresModel::class : IgrejasModel::class
        ];

        if (!isset($modelMap[$nivel])) {
            return null;
        }

        $model = new $modelMap[$nivel]();
        $profile = $model->find($idPerfil);

        if (!$profile) {
            return null;
        }

        if ($search) {
            $profile = $model->like('nome', $search)
                ->orLike('sobrenome', $search)
                ->orLike('cpf', $search)
                ->findAll();
        }

        return [
            'tipo' => ucfirst($tipo),
            'nome' => $profile['nome'] ?? $profile['razao_social'],
            'url' => site_url("admin/{$tipo}/{$idPerfil}")
        ];
    }

    public function cacheData()
    {
        helper("auxiliar");
        $cache = \Config\Services::cache();

        if (!$builder = $cache->get("user_cache")) {
            $builder = $this->select('usuarios.id as id_u, usuarios.*, perfis.*')
                ->join('perfis', 'usuarios.id = perfis.id_user', 'left')
                ->findAll();
            $cache->save("user_cache", $builder, getCacheExpirationTimeInSeconds(365));
        }

        return $builder;
    }

    public function login(string $email, string $senha)
    {
        $rowUser = $this->where("email", $email)->first();

        if (!$rowUser) {
            throw new ErrorException(lang("Errors.erroEmailInvalido"));
        }

        if (!password_verify($senha, $rowUser["password"])) {
            throw new ErrorException(lang("Errors.erroSenhaInvalida"));
        }

        $perfil = $this->getProfileByTipo($rowUser['tipo'], $rowUser['id_perfil']);

        $dataSession = [
            'isConnected'   => TRUE,
            'id_perfil'     => intval($rowUser['id_perfil']),
            'id'            => intval($rowUser['id']),
            'idAdm'         => intval($rowUser['id_adm']) ?? 1,
            'name'          => $perfil['nome'] ?? $perfil['nome_tesoureiro'] ?? '',
            'email'         => $rowUser['email'],
            'celular'       => $perfil['celular'] ?? '',
            'tel'           => $perfil['telefone'] ?? '',
            'nivel'         => intval($rowUser['nivel']),
            'tipo'          => $rowUser['tipo'],
            'foto'          => $perfil['foto'] ?? '',
            'id_supervisao' => $perfil['id_supervisao'] ?? false
        ];

        session()->set(['data' => $dataSession]);

        $request = service('request');
        $redirect = $request->getGet('redirect');

        return $redirect && filter_var($redirect, FILTER_VALIDATE_URL)
            ? redirect()->to($redirect)
            : redirect()->to(site_url('admin'));
    }

    public function google(array $data)
    {
        $rowUser = $this->where("email", $data["email"])->first();

        if (!$rowUser) {
            throw new ErrorException(lang("Errors.google.erroEmailInvalido"));
        }

        $dataSession = [
            'isConnected'   => TRUE,
            'id'            => intval($rowUser['id']),
            'idAdm'         => intval($rowUser['id_adm']),
            'name'          => $rowUser['nome'],
            'email'         => $rowUser['email'],
            'celular'       => $rowUser['celular'],
            'tel'           => $rowUser['telefone'],
            'nivel'         => intval($rowUser['nivel']),
            'foto'          => $data["image"]
        ];

        session()->set(['data' => $dataSession]);

        return redirect()->to(site_url('admin'));
    }

    public function cadUser(string $tipo, array $input): bool
    {
        if (!in_array($tipo, ['superadmin', 'gerente', 'supervisor', 'pastor', 'igreja'])) {
            throw new \InvalidArgumentException("Tipo de permissão não definido: $tipo");
        }

        $nivel = match ($tipo) {
            'superadmin' => 1,
            'gerente' => 2,
            'supervisor' => 3,
            default => 4,
        };

        $data = [
            'tipo'        => $tipo,
            'id_perfil'   => $input['id_perfil'],
            'email'       => $input['email'],
            'password'    => password_hash($input['password'], PASSWORD_BCRYPT),
            'nivel'       => $nivel,
            'id_adm'      => $input['id_adm']
        ];

        return $this->insert($data);
    }

    public function userData()
    {
        return $this->getProfileByTipo(session('data')['tipo'], session('data')['id_perfil']);
    }

    private function getProfileByTipo($tipo, $idPerfil)
    {
        $modelMap = [
            'superadmin' => AdministradoresModel::class,
            'gerente' => GerentesModel::class,
            'supervisor' => SupervisoresModel::class,
            'pastor' => PastoresModel::class,
            'igreja' => IgrejasModel::class
        ];

        if (!isset($modelMap[$tipo])) {
            throw new ErrorException("Tipo de permissão não definida");
        }

        $model = new $modelMap[$tipo]();
        return $model->find($idPerfil);
    }
}
