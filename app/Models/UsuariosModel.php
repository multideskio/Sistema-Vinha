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
    /*protected $validationRules = [
        "nome"     => "required",
        "password" => "required|min_length[6]",
        "telefone" => "required",
        "email"    => "required|valid_email|is_unique[usuarios.email]"
    ];*/

    protected $validationMessages   = [
        "nome" => [
            "required" => "Um nome é obrigatório"
        ],
        "password" => [
            "required" => "Senha obrigatória",
            "min_length" => "Uma senha de pelo menos 6 caracteres"
        ],
        "Telefone" => [
            "required" => "O telefone é necessário"
        ],
        "email" => [
            "is_unique" => "Já existe um usuário do sistema utilizando esse endereço de e-mail"
        ]
    ];

    protected $skipValidation       = false;
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


    public function disableEmailValidation()
    {
        // Remova temporariamente a regra de validação para o e-mail
        unset($this->validationRules['email']);
    }

    public function enableEmailValidation()
    {
        // Restaure a regra de validação para o e-mail
        $this->validationRules['email'] = "required|valid_email|is_unique[usuarios.email]";
    }

    public function updateUsuario($id, $usuarioData)
    {
        // Desativa temporariamente a validação para o e-mail
        $this->disableEmailValidation();

        // Realiza a atualização
        $result = $this->update($id, $usuarioData);

        // Restaura a validação para o e-mail
        $this->enableEmailValidation();

        return $result;
    }


    protected function antesCadastro(array $data)
    {
        //Busca functions auxiliar
        helper("auxiliar");

        //Gera novo token
        $data["data"]["token"] = gera_token();

        //Verifica se password está preenchido
        if (array_key_exists("password", $data["data"])) {
            $data["data"]["password"] = password_hash($data["data"]["password"], PASSWORD_BCRYPT);
        }

        //Verificar de telefone está preenchido
        if (array_key_exists("telefone", $data["data"])) {
            $data["data"]["telefone"] = limparString($data["data"]["telefone"]);
        }

        //Verificar de telefone está preenchido
        if (array_key_exists("celular", $data["data"])) {
            $data["data"]["celular"] = limparString($data["data"]["celular"]);
        }

        //retorna dados
        return $data;
    }

    //apaga chache ao ralizar alguma ação importante no banco de dados
    protected function updateCache()
    {
        $cache = \Config\Services::cache();
        $cache->delete("user_cache");
        $cache->delete('listDashboard');

        /*$dbutil = \Config\Database::utils();
        $dbutil->optimizeTable("usuarios");*/
    }

    public function listGeral($input = false, $limit = 10, $order = 'DESC')
    {
        $data = array();

        $this->orderBy('id', $order);
        $rows = $this->paginate($limit);

        $modelAdmin   = new AdministradoresModel();
        $modelGerente = new GerentesModel();
        $modelSuper   = new SupervisoresModel();
        $modelPastor  = new PastoresModel();
        $modelIgrejas = new IgrejasModel();

        $cache = service('cache');

        /*if($cache->get('listDashboard_')){
            return $cache->get('listDashboard');
        }*/

        foreach ($rows as $row) {
            if ($row['nivel'] == 1) {
                $rowResult = $modelAdmin->find($row['id_perfil']);
                $data[] = [
                    'id'    => $row['id'],
                    'tipo'  => 'Administrador',
                    'nome'  => $rowResult['nome'],
                    'email' => $row['email']
                ];
            }

            if ($row['nivel'] == 2) {
                $rowResult = $modelGerente->find($row['id_perfil']);
                $data[] = [
                    'id'    => $row['id'],
                    'tipo'  => 'Gerente',
                    'nome'  => $rowResult['nome'],
                    'email' => $row['email']
                ];
            }

            if ($row['nivel'] == 3) {
                $rowResult = $modelSuper->find($row['id_perfil']);

                $data[] = [
                    'id'    => $row['id'],
                    'tipo'  => 'Supervisor',
                    'nome'  => $rowResult['nome'],
                    'email' => $row['email']
                ];
            }

            if ($row['nivel'] == 4) {
                if ($row['tipo'] == 'pastor') {
                    $rowResult = $modelPastor->find($row['id_perfil']);
                    if ($rowResult) {
                        $data[] = [
                            'id'    => $row['id'],
                            'tipo'  => 'Pastor',
                            'nome'  => $rowResult['nome'],
                            'email' => $row['email']
                        ];
                    }
                } else {
                    $rowResult = $modelIgrejas->find($row['id_perfil']);
                    $data[] = [
                        'id'    => $row['id'],
                        'tipo'  => 'Igreja',
                        'nome'  => $rowResult['razao_social'],
                        'email' => $row['email']
                    ];
                }
            }
        }

        $dados = [
            'rows' => $data,
            'pager' => $this->pager->links('default', 'paginate')
        ];

        /*helper("auxiliar");
        $cache->save("listDashboard", $dados, getCacheExpirationTimeInSeconds(365));
        */
        return $dados;
    }

    public function listSearch($input = false, $limit = 10, $order = 'DESC')
    {
        $modelAdmin   = new AdministradoresModel();
        $modelSuper   = new SupervisoresModel();
        $modelRegiao  = new RegioesModel();
        $modelGerente = new GerentesModel();
        $modelPastor  = new PastoresModel();
        $modelIgrejas = new IgrejasModel();

        // Extrai o termo de busca do input ou define como false se não houver
        $search = !empty($input['search']) ? $input['search'] : false;

        // Variável para armazenar os resultados da busca na tabela gerentes
        $usuariosSearch = array();

        // Array que armazenará os dados dos usuarios
        $dataUsers = array();

        $this->orderBy('id', $order);
        /** Ações de busca na tabela usuarios */
        if ($search) {
            // Busca por id, nome, sobrenome ou cpf que correspondam ao termo de busca
            $usuariosSearch = $this->like('id', $search)
                ->orLike('email', $search)
                ->orLike('tipo', $search)
                ->paginate($limit);

            $usuarios = $usuariosSearch;

            // Se não encontrar resultados, carrega os gerentes normalmente
            if (!count($usuariosSearch)) {
                $usuarios = $this->paginate($limit);
            }
        } else {
            // Se não houver termo de busca, carrega os gerentes normalmente
            $usuarios = $this->paginate($limit);
        }

        foreach ($usuarios as $usuario) {
            if ($usuario['nivel'] == 1) {
                $searchUser = $modelAdmin->where('id', $usuario['id_perfil'])->findAll();
                // Se houver resultados na busca de usuarios
                if (count($usuariosSearch)) {
                    foreach ($searchUser as  $row) {
                        if ($usuario['id_perfil'] == $row['id']) {
                            $dataUsers[] = [
                                'id' => $usuario['id'],
                                'email' => $usuario['email'],
                                'perfil_id' => $usuario['id_perfil'],
                                'nome' => $row['nome'] . ' ' . $row['sobrenome'],
                                'doc' => $row['cpf'],
                                'foto' => $row['foto'],
                                'regiao' => 'Não aplicável',
                                'gerente' => 'Não aplicável',
                                'supervisao' => 'Não aplicável',
                                'perfil_tipo' => 'administrador',
                                'uf' => $row['uf'],
                                'cidade' => $row['cidade'],
                                'celular' => $row['celular'],
                                'telefone' => $row['telefone']
                            ];
                        }
                    }
                } else {
                    // Se não houver resultados na busca de gerentes, busca por email se houver termo de busca
                    if ($search) {
                        $searchUser = $modelAdmin->where('id', $usuario['id_perfil'])
                            ->like('nome', $search)
                            ->orLike('sobrenome', $search)
                            ->orLike('cpf', $search)
                            ->findAll();
                    }
                    // Adiciona dados dos gerentes e usuários encontrados ao array
                    if (count($searchUser)) {
                        foreach ($searchUser as  $row) {
                            if ($usuario['id_perfil'] == $row['id']) {
                                $dataUsers[] = [
                                    'id' => $usuario['id'],
                                    'email' => $usuario['email'],
                                    'perfil_id' => $usuario['id_perfil'],
                                    'nome' => $row['nome'] . ' ' . $row['sobrenome'],
                                    'doc' => $row['cpf'],
                                    'foto' => $row['foto'],
                                    'regiao' => 'Não aplicável',
                                    'gerente' => 'Não aplicável',
                                    'supervisao' => 'Não aplicável',
                                    'perfil_tipo' => 'administrador',
                                    'uf' => $row['uf'],
                                    'cidade' => $row['cidade'],
                                    'celular' => $row['celular'],
                                    'telefone' => $row['telefone']
                                ];
                            }
                        }
                    }
                }
            }


            if ($usuario['nivel'] == 2) {
                $searchUser = $modelGerente->where('id', $usuario['id_perfil'])->findAll();
                // Se houver resultados na busca de usuarios
                if (count($usuariosSearch)) {
                    foreach ($searchUser as  $row) {
                        if ($usuario['id_perfil'] == $row['id']) {
                            $dataUsers[] = [
                                'id' => $usuario['id'],
                                'email' => $usuario['email'],
                                'perfil_id' => $usuario['id_perfil'],
                                'nome' => $row['nome'] . ' ' . $row['sobrenome'],
                                'doc' => $row['cpf'],
                                'foto' => $row['foto'],
                                'regiao' => 'Não aplicável',
                                'gerente' => 'Não aplicável',
                                'supervisao' => 'Não aplicável',
                                'perfil_tipo' => 'gerente',
                                'uf' => $row['uf'],
                                'cidade' => $row['cidade'],
                                'celular' => $row['celular'],
                                'telefone' => $row['telefone']
                            ];
                        }
                    }
                } else {
                    // Se não houver resultados na busca de gerentes, busca por email se houver termo de busca
                    if ($search) {
                        $searchUser = $modelGerente->where('id', $usuario['id_perfil'])
                            ->like('nome', $search)
                            ->orLike('sobrenome', $search)
                            ->orLike('cpf', $search)
                            ->findAll();
                    }
                    // Adiciona dados dos gerentes e usuários encontrados ao array
                    if (count($searchUser)) {
                        foreach ($searchUser as  $row) {
                            if ($usuario['id_perfil'] == $row['id']) {
                                $dataUsers[] = [
                                    'id' => $usuario['id'],
                                    'email' => $usuario['email'],
                                    'perfil_id' => $usuario['id_perfil'],
                                    'nome' => $row['nome'] . ' ' . $row['sobrenome'],
                                    'doc' => $row['cpf'],
                                    'foto' => $row['foto'],
                                    'regiao' => 'Não aplicável',
                                    'gerente' => 'Não aplicável',
                                    'supervisao' => 'Não aplicável',
                                    'perfil_tipo' => 'gerente',
                                    'uf' => $row['uf'],
                                    'cidade' => $row['cidade'],
                                    'celular' => $row['celular'],
                                    'telefone' => $row['telefone']
                                ];
                            }
                        }
                    }
                }
            }


            if ($usuario['nivel'] == 3) {
                $searchUser = $modelSuper->where('id', $usuario['id_perfil'])->findAll();
                // Se houver resultados na busca de usuarios
                if (count($usuariosSearch)) {
                    foreach ($searchUser as  $row) {
                        if ($usuario['id_perfil'] == $row['id']) {
                            $regiao = $modelRegiao->find($row['id_regiao']);
                            $gerente = $modelGerente->find($row['id_gerente']);
                            $dataUsers[] = [
                                'id' => $usuario['id'],
                                'email' => $usuario['email'],
                                'perfil_id' => $usuario['id_perfil'],
                                'nome' => $row['nome'] . ' ' . $row['sobrenome'],
                                'doc' => $row['cpf'],
                                'foto' => $row['foto'],
                                'regiao' => $regiao['nome'],
                                'gerente' => $gerente['id'] . ' - ' . $gerente['nome'] . ' ' . $gerente['sobrenome'],
                                'supervisao' => 'Não aplicável',
                                'perfil_tipo' => 'supervisor',
                                'uf' => $row['uf'],
                                'cidade' => $row['cidade'],
                                'celular' => $row['celular'],
                                'telefone' => $row['telefone']
                            ];
                        }
                    }
                } else {
                    // Se não houver resultados na busca de gerentes, busca por email se houver termo de busca
                    if ($search) {
                        $searchUser = $modelSuper->where('id', $usuario['id_perfil'])
                            ->like('nome', $search)
                            ->orLike('sobrenome', $search)
                            ->orLike('cpf', $search)
                            ->findAll();
                    }
                    // Adiciona dados dos gerentes e usuários encontrados ao array
                    if (count($searchUser)) {
                        foreach ($searchUser as $row) {
                            if ($usuario['id_perfil'] == $row['id']) {
                                $regiao  = $modelRegiao->find($row['id_regiao']);
                                $gerente = $modelGerente->find($row['id_gerente']);

                                $dataUsers[] = [
                                    'id' => $usuario['id'],
                                    'email' => $usuario['email'],
                                    'perfil_id' => $usuario['id_perfil'],
                                    'nome' => $row['nome'] . ' ' . $row['sobrenome'],
                                    'doc' => $row['cpf'],
                                    'foto' => $row['foto'],
                                    'regiao' => $regiao['nome'],
                                    'gerente' => $gerente['id'] . ' - ' . $gerente['nome'] . ' ' . $gerente['sobrenome'],
                                    'supervisao' => 'Não aplicável',
                                    'perfil_tipo' => 'supervisor',
                                    'uf' => $row['uf'],
                                    'cidade' => $row['cidade'],
                                    'celular' => $row['celular'],
                                    'telefone' => $row['telefone']
                                ];
                            }
                        }
                    }
                }
            }
        }


        return [
            'rows' => $dataUsers, // Dados dos gerentes
            'pager' => $this->pager->links('default', 'paginate') // Links de paginação
        ];
    }

    public function listSearch1($input = false)
    {
        $modelAdmin   = new AdministradoresModel();
        $modelSuper   = new SupervisoresModel();
        $modelRegiao  = new RegioesModel();
        $modelGerente = new GerentesModel();
        $modelPastor  = new PastoresModel();
        $modelIgrejas = new IgrejasModel();

        $dataUsers = array();

        if (isset($input['search'])) {
            $search = $input['search'];
            $buscaUsers = $this->like('email', $search)
                ->orLike('id', $search)
                ->orLike('id_perfil', $search)
                ->orLike('tipo', $search)
                ->paginate(15);

            if (count($buscaUsers)) {
                $usuarios = $buscaUsers;
            } else {
                $usuarios = $this->paginate(15);
            }
        } else {
            $usuarios = $this->paginate(15);
        }

        $pager = $this->pager->links('default', 'paginate');

        foreach ($usuarios as $usuario) {
            if ($usuario['nivel'] == 1) {
                if (isset($input['search'])) {
                    $search = $input['search'];
                    $adminUsers = $modelAdmin->like('nome', $search)
                        ->orLike('id', $search)
                        ->orLike('cpf', $search)
                        ->findAll();
                    foreach ($adminUsers as $adminUser) {

                        $dataUsers[] = [
                            'id' => $usuario['id'],
                            'email' => $usuario['email'],
                            'perfil_id' => $usuario['id_perfil'],
                            'nome' => $adminUser['nome'] . ' ' . $adminUser['sobrenome'],
                            'doc' => $adminUser['cpf'],
                            'foto' => $adminUser['foto'],
                            'regiao' => 'Não aplicável',
                            'gerente' => 'Não aplicável',
                            'supervisao' => 'Não aplicável',
                            'perfil_tipo' => 'administrador',
                            'uf' => $adminUser['uf'],
                            'cidade' => $adminUser['cidade'],
                            'celular' => $adminUser['celular'],
                            'telefone' => $adminUser['telefone']
                        ];
                    }
                } else {
                    $adminUser = $modelAdmin->find($usuario['id_perfil']);
                    $dataUsers[] = [
                        'id' => $usuario['id'],
                        'email' => $usuario['email'],
                        'perfil_id' => $usuario['id_perfil'],
                        'nome' => $adminUser['nome'] . ' ' . $adminUser['sobrenome'],
                        'doc' => $adminUser['cpf'],
                        'foto' => $adminUser['foto'],
                        'regiao' => 'Não aplicável',
                        'gerente' => 'Não aplicável',
                        'supervisao' => 'Não aplicável',
                        'perfil_tipo' => 'administrador',
                        'uf' => $adminUser['uf'],
                        'cidade' => $adminUser['cidade'],
                        'celular' => $adminUser['celular'],
                        'telefone' => $adminUser['telefone']
                    ];
                }
            }

            if ($usuario['nivel'] == 2) {

                if (isset($input['search'])) {
                    $search = $input['search'];
                    $adminUsers = $modelGerente->like('nome', $search)
                        ->orLike('id', $search)
                        ->orLike('cpf', $search)
                        ->findAll();

                    foreach ($adminUsers as $adminUser) {
                        if ($usuario['id_perfil'] == $adminUser['id']) {
                            $dataUsers[] = [
                                'id' => $usuario['id'],
                                'email' => $usuario['email'],
                                'perfil_id' => $usuario['id_perfil'],
                                'nome' => $adminUser['nome'] . ' ' . $adminUser['sobrenome'],
                                'doc' => $adminUser['cpf'],
                                'foto' => $adminUser['foto'],
                                'regiao' => 'Não aplicável',
                                'gerente' => 'Não aplicável',
                                'supervisao' => 'Não aplicável',
                                'perfil_tipo' => 'gerente',
                                'uf' => $adminUser['uf'],
                                'cidade' => $adminUser['cidade'],
                                'celular' => $adminUser['celular'],
                                'telefone' => $adminUser['telefone']
                            ];
                        }
                    }
                } else {
                    $adminUser = $modelGerente->find($usuario['id_perfil']);
                    $dataUsers[] = [
                        'id' => $usuario['id'],
                        'email' => $usuario['email'],
                        'perfil_id' => $usuario['id_perfil'],
                        'nome' => $adminUser['nome'] . ' ' . $adminUser['sobrenome'],
                        'doc' => $adminUser['cpf'],
                        'foto' => $adminUser['foto'],
                        'regiao' => 'Não aplicável',
                        'gerente' => 'Não aplicável',
                        'supervisao' => 'Não aplicável',
                        'perfil_tipo' => 'gerente',
                        'uf' => $adminUser['uf'],
                        'cidade' => $adminUser['cidade'],
                        'celular' => $adminUser['celular'],
                        'telefone' => $adminUser['telefone']
                    ];
                }
            }

            if ($usuario['nivel'] == 3) {



                if (isset($input['search'])) {
                    $search = $input['search'];
                    $adminSupervisor = $modelSuper->like('nome', $search)
                        ->orLike('cpf', $search)
                        ->findAll();

                    foreach ($adminSupervisor as $adminUser) {
                        if ($usuario['id_perfil'] == $adminUser['id']) {
                            $gerente = $modelGerente->find($adminUser['id_gerente']);
                            $regiao  = $modelRegiao->find($adminUser['id_regiao']);
                            $dataUsers[] = [
                                'id' => $usuario['id'],
                                'email' => $usuario['email'],
                                'perfil_id' => $usuario['id_perfil'],
                                'nome' => $adminUser['nome'] . ' ' . $adminUser['sobrenome'],
                                'doc' => $adminUser['cpf'],
                                'foto' => $adminUser['foto'],
                                'regiao' => $regiao['nome'],
                                'gerente' => $gerente['nome'] . ' ' . $gerente['sobrenome'],
                                'supervisao' => 'Não aplicável',
                                'perfil_tipo' => 'supervisor',
                                'uf' => $adminUser['uf'],
                                'cidade' => $adminUser['cidade'],
                                'celular' => $adminUser['celular'],
                                'telefone' => $adminUser['telefone']
                            ];
                        }
                    }
                } else {
                    $adminUser = $modelSuper->find($usuario['id_perfil']);

                    $gerente = $modelGerente->find($adminUser['id_gerente']);
                    $regiao  = $modelRegiao->find($adminUser['id_regiao']);

                    $dataUsers[] = [
                        'id' => $usuario['id'],
                        'email' => $usuario['email'],
                        'perfil_id' => $usuario['id_perfil'],
                        'nome' => $adminUser['nome'] . ' ' . $adminUser['sobrenome'],
                        'doc' => $adminUser['cpf'],
                        'foto' => $adminUser['foto'],
                        'regiao' => $regiao['nome'],
                        'gerente' => $gerente['nome'] . ' ' . $gerente['sobrenome'],
                        'supervisao' => 'Não aplicável',
                        'perfil_tipo' => 'supervisor',
                        'uf' => $adminUser['uf'],
                        'cidade' => $adminUser['cidade'],
                        'celular' => $adminUser['celular'],
                        'telefone' => $adminUser['telefone']
                    ];
                }
            }
        };

        $data = [
            'rows' => $dataUsers,
            'pager' => $pager
        ];
        return $data;
    }

    public function listSearch0($input = false)
    {
        // Inicia o serviço de temporização
        $benchmark = \Config\Services::timer();

        // Começa a contagem do tempo
        $benchmark->start('query_timer');

        $this->select('usuarios.id, usuarios.email, usuarios.nivel')

            ->select('administradores.id AS adm_id, administradores.nome AS adm_nome, administradores.cpf as adm_cpf, administradores.foto AS adm_foto, administradores.uf as adm_uf, administradores.cidade as adm_cidade, administradores.telefone as adm_telefone, administradores.celular as adm_celular')

            ->select('gerentes.id AS ger_id, gerentes.nome AS ger_nome, gerentes.cpf as ger_cpf, gerentes.foto AS ger_foto, gerentes.uf as ger_uf, gerentes.cidade as ger_cidade, gerentes.telefone as ger_telefone, gerentes.celular as ger_celular')

            ->select('supervisores.id AS sup_id, supervisores.nome AS sup_nome, supervisores.cpf as sup_cpf, supervisores.foto AS sup_foto, supervisores.uf as sup_uf, supervisores.cidade as sup_cidade, supervisores.telefone as sup_telefone, supervisores.celular as sup_celular, supervisores.id_regiao as sup_regiao, supervisores.id_gerente as sup_gerente')

            ->select('pastores.id AS pas_id, pastores.nome AS pas_nome, pastores.cpf as pas_cpf')

            ->select('igrejas.id AS igr_id, igrejas.fantasia AS igr_fantasia, igrejas.cnpj as igr_cnpj')

            ->join('administradores', 'usuarios.id_perfil = administradores.id', 'left outer')
            ->join('gerentes', 'usuarios.id_perfil = gerentes.id', 'left outer')
            ->join('supervisores', 'usuarios.id_perfil = supervisores.id', 'left outer')
            ->join('pastores', 'usuarios.id_perfil = pastores.id', 'left outer')
            ->join('igrejas', 'usuarios.id_perfil = igrejas.id', 'left outer');

        // Adiciona cláusula de grupo para evitar duplicação de resultados

        if (isset($input['search'])) {


            $search = $input['search'];

            $this->groupStart();

            $this->like('usuarios.email', $search)
                ->orLike('usuarios.id', $search)
                ->orLike('administradores.cpf', $search)
                ->orLike('gerentes.cpf', $search)
                ->orLike('supervisores.cpf', $search)
                ->orLike('supervisores.nome', $search);

            /*->orLike('supervisores.cpf', $search)
                ->orLike('pastores.cpf', $search)
                ->orLike('igrejas.cnpj', $search);*/

            //->orLike('administradores.nome', $search)
            //->orLike('gerentes.nome', $search)
            /* ->orLike('supervisores.nome', $search)
                //->orLike('pastores.nome', $search)
                //->orLike('igrejas.fantasia', $search);
            ;*/
            $this->groupEnd();
        } else {
            $this->groupBy('usuarios.id');
        }

        $usuarios = $this->paginate(15);

        $pager = $this->pager->links('default', 'paginate');


        $usuariosFiltrados = array_map(function ($usuario) {
            // Filtra e retorna apenas os dados relevantes
            if ($usuario['nivel'] == 1) {
                return [
                    'id' => $usuario['id'],
                    'email' => $usuario['email'],
                    'perfil_id' => $usuario['adm_id'],
                    'nome' => $usuario['adm_nome'],
                    'doc' => $usuario['adm_cpf'],
                    'foto' => $usuario['adm_foto'],
                    'regiao' => 'Não aplicável',
                    'gerente' => 'Não aplicável',
                    'supervisao' => 'Não aplicável',
                    'perfil_tipo' => 'administrador',
                    'uf' => $usuario['adm_uf'],
                    'cidade' => $usuario['adm_cidade'],
                    'celular' => $usuario['adm_celular'],
                    'telefone' => $usuario['adm_telefone']
                ];
            } elseif ($usuario['nivel'] == 2) {
                return [
                    'id' => $usuario['id'],
                    'email' => $usuario['email'],
                    'perfil_id' => $usuario['adm_id'],
                    'nome' => $usuario['ger_nome'],
                    'doc' => $usuario['ger_cpf'],
                    'foto' => $usuario['ger_foto'],
                    'regiao' => 'Não aplicável',
                    'gerente' => 'Não aplicável',
                    'supervisao' => 'Não aplicável',
                    'perfil_tipo' => 'gerente',
                    'uf' => $usuario['ger_uf'],
                    'cidade' => $usuario['ger_cidade'],
                    'celular' => $usuario['ger_celular'],
                    'telefone' => $usuario['ger_telefone']
                ];
            } elseif ($usuario['nivel'] == 3) {
                $modelRegiao = new RegioesModel();
                $rowRegiao = $modelRegiao->select('nome')->find($usuario['sup_regiao']);
                $nomeRegiao = $rowRegiao ? $rowRegiao['nome'] : 'Não informado';

                $modelGerente = new GerentesModel();
                $rowGerente = $modelGerente->select('nome, sobrenome')->find($usuario['sup_gerente']);
                $nomeGerente = $rowGerente ? $rowGerente['nome'] . ' ' . $rowGerente['sobrenome'] : 'Não informado';

                return [
                    'id' => $usuario['id'],
                    'email' => $usuario['email'],
                    'perfil_id' => $usuario['adm_id'],
                    'nome' => $usuario['sup_nome'],
                    'doc' => $usuario['sup_cpf'],
                    'foto' => $usuario['sup_foto'],
                    'regiao' => $nomeRegiao,
                    'gerente' => $nomeGerente,
                    'supervisao' => 'Não aplicável',
                    'perfil_tipo' => 'supervisor',
                    'uf' => $usuario['sup_uf'],
                    'cidade' => $usuario['sup_cidade'],
                    'celular' => $usuario['sup_celular'],
                    'telefone' => $usuario['sup_telefone']
                ];
            } elseif ($usuario['nivel'] == 4) {
                /*return [
                    'id' => $usuario['id'],
                    'email' => $usuario['email'],
                    'perfil_id' => $usuario['adm_id'],
                    'nome' => $usuario['pas_nome'],
                    'doc' => $usuario['pas_cpf'],
                    'foto' => 'Não disponível',
                    'regiao' => 'Não aplicável',
                    'gerente' => 'Não aplicável',
                    'supervisao' => 'Não aplicável',
                    'perfil_tipo' => 'pastor',
                    'uf' => 'Não disponível',
                    'cidade' => 'Não disponível',
                    'celular' => 'Não disponível',
                    'telefone' => 'Não disponível'
                ];*/
            } elseif ($usuario['nivel'] == 5) {
                /*return [
                    'id' => $usuario['id'],
                    'email' => $usuario['email'],
                    'perfil_id' => $usuario['adm_id'],
                    'nome' => $usuario['igr_fantasia'],
                    'doc' => $usuario['igr_cnpj'],
                    'foto' => 'Não disponível',
                    'regiao' => 'Não aplicável',
                    'gerente' => 'Não aplicável',
                    'supervisao' => 'Não aplicável',
                    'perfil_tipo' => 'igreja',
                    'uf' => 'Não disponível',
                    'cidade' => 'Não disponível',
                    'celular' => 'Não disponível',
                    'telefone' => 'Não disponível'
                ];*/
            }
            return null;
        }, $usuarios);

        $usuariosFiltrados = array_filter($usuariosFiltrados); // Remove entradas nulas

        // Para a contagem do tempo
        $benchmark->stop('query_timer');

        // Obtém o tempo de execução
        $executionTime = number_format($benchmark->getElapsedTime('query_timer') * 1000, 3) . ' ms';

        $data = [
            'rows' => $usuariosFiltrados,
            'pager' => $pager,
            'execution_time' => $executionTime
        ];

        return $data;
    }

    /*public function listSearch0($input = false)
    {

        foreach ($this->findAll() as $row) {
            if ($row['tipo'] == 'superadmin') {
                $this->select('usuarios.*')
                    ->select('administradores.*')
                    ->join('administradores', 'usuarios.id_perfil = administradores.id', 'left');
            }
            if ($row['tipo'] == 'gerente') {
                $this->select('usuarios.*')
                    ->select('gerentes.*')
                    ->join('gerentes', 'usuarios.id_perfil = gerentes.id', 'left');
            }

            if ($row['tipo'] == 'supervisor') {
                $this->select('usuarios.*')
                    ->select('supervisores.*')
                    ->join('supervisores', 'usuarios.id_perfil = supervisores.id', 'left');
            }

            if ($row['tipo'] == 'pastor') {
                $this->select('usuarios.*')
                    ->select('pastores.*')
                    ->join('pastores', 'usuarios.id_perfil = pastores.id', 'left');
            }

            if ($row['tipo'] == 'igreja') {
                $this->select('usuarios.*')
                    ->select('igrejas.*')
                    ->join('igrejas', 'usuarios.id_perfil = igrejas.id', 'left');
            }
        }


        if (isset($input['search'])) {
            $this->like('administradores.nome', $input['search'])
                ->orLike('gerentes.nome', $input['search'])
                ->orLike('supervisores.nome', $input['search'])
                ->orLike('pastores.nome', $input['search'])
                ->orLike('igrejas.fantasia', $input['search'])
                ->orLike('usuarios.email', $input['search'])
                ->orLike('usuarios.id', $input['search']);
        }

        $data = [
            'rows' => $this->paginate(15),
            'pager' => $this->pager->links('default', 'paginate')
        ];

        return $data;
    }*/

    //Lista de usuários em cache
    public function cacheData()
    {
        /* This code snippet is responsible for caching user data to improve performance by reducing
        the number of database queries. Here's a breakdown of what it does: */
        helper("auxiliar");
        $cache = \Config\Services::cache();
        if (!$cache->get("user_cache")) {
            $builder = $this->select('usuarios.id as id_u, usuarios.*, perfis.*')
                ->join('perfis', 'usuarios.id = perfis.id_user', 'left')
                ->findAll();
            // Save into the cache for 365 days
            $cache->save("user_cache", $builder, getCacheExpirationTimeInSeconds(365));
        } else {
            $builder = $cache->get("user_cache");
        }
        return $builder;
    }

    public function login(string $email, string $senha)
    {
        //busca o email
        $search = $this->where("email", $email);

        //verfica se tem o email
        if (!$search->countAllResults()) {
            //erro, email não encontrado
            throw new ErrorException(lang("Errors.erroEmailInvalido"));
        };

        //continua
        //Busca dados do usuário
        $rowUser = $this->where("email", $email)->find();

        //Compada hash de senha
        if (!password_verify($senha, $rowUser[0]["password"])) {
            //erro, senha incorreta
            throw new ErrorException(lang("Errors.erroSenhaInvalida"));
        }

        /* The above PHP code is checking the value of the "tipo" key in the  array and based
        on the value, it instantiates a specific model class (AdministradoresModel, GerentesModel,
        SupervisoresModel, PastoresModel, or IgrejasModel) to search for a profile using the
        id_perfil value from the  array. If the "tipo" value does not match any of the
        specified cases, it throws an ErrorException with the message "Tipo de permissão não
        definida" (Permission type not defined). */
        if ($rowUser[0]["tipo"] == 'superadmin') {
            $buscaPerfil = new AdministradoresModel();
            $perfil = $buscaPerfil->find($rowUser[0]["id_perfil"]);
        } elseif ($rowUser[0]["tipo"] == 'gerente') {
            $buscaPerfil = new GerentesModel();
            $perfil = $buscaPerfil->find($rowUser[0]["id_perfil"]);
        } elseif ($rowUser[0]["tipo"] == 'supervisor') {
            $buscaPerfil = new SupervisoresModel();
            $perfil = $buscaPerfil->find($rowUser[0]["id_perfil"]);
        } elseif ($rowUser[0]["tipo"] == 'pastor') {
            $buscaPerfil = new PastoresModel();
            $perfil = $buscaPerfil->find($rowUser[0]["id_perfil"]);
        } elseif ($rowUser[0]["tipo"] == 'igreja') {
            $buscaPerfil = new IgrejasModel();
            $perfil = $buscaPerfil->find($rowUser[0]["id_perfil"]);
        } else {
            throw new ErrorException("Tipo de permissão não definida");
        }

        $dataSession = [
            'isConnected'   => TRUE,
            'id_perfil'     => intval($rowUser[0]['id_perfil']),
            'id'            => intval($rowUser[0]['id']),
            'idAdm'         => intval($rowUser[0]['id_adm']),
            'name'          => ($perfil['nome']) ?? ($perfil['nome_tesoureiro']),
            'email'         => $rowUser[0]['email'],
            'celular'       => $perfil['celular'],
            'tel'           => $perfil['telefone'],
            'nivel'         => intval($rowUser[0]['nivel']),
            'tipo'          => $rowUser[0]['tipo'],
            'foto'          => $perfil['foto'],
            //'id_user'       => $rowUser[0]['id_user'],
            'id_supervisao' => (isset($perfil['id_supervisao'])) ? $perfil['id_supervisao'] : false
        ];

        session()->set(['data' => $dataSession]);

        // Após o login ser bem-sucedido, verifique se há um parâmetro de consulta 'redirect'
        $request = service('request');
        $redirect = $request->getGet('redirect');

        // Se houver uma URL de redirecionamento válida, redirecione o usuário para lá
        if ($redirect && filter_var($redirect, FILTER_VALIDATE_URL)) {
            return redirect()->to($redirect);
        } else {
            // Se não houver uma URL de redirecionamento válida, redirecione o usuário para uma página padrão
            return redirect()->to(site_url('admin'));
        }
    }

    public function google(array $data)
    {
        $email = $data["email"];

        //busca o email
        $search = $this->where("email", $email);

        //verfica se tem o email
        if (!$search->countAllResults()) {
            //erro, email não encontrado
            throw new ErrorException(lang("Errors.google.erroEmailInvalido"));
        };

        //continua
        //Busca dados do usuário
        $rowUser = $this->where("email", $email)->find();

        //continua
        //busca dados do perfil
        //$buscaPerfil = new PerfisModel();
        /*$perfil     = $buscaPerfil->where("id_user", $rowUser[0]["id"])->find();

        //Verifica se perfil já foi preenchido
        if ($perfil) {
            if (!$perfil[0]['foto']) {
                $buscaPerfil->save(
                    [
                        'id'   => $perfil[0]['id'],
                        'foto' => $data["image"]
                    ]
                );
            }
        } else {
            $buscaPerfil->insert([
                "id_user"   => $rowUser[0]["id"],
                "foto"      => $data["image"],
                "id_google" => $data["id"]
            ]);
        }*/

        $dataSession = [
            'isConnected'   => TRUE,
            'id'            => intval($rowUser[0]['id']),
            'idAdm'         => intval($rowUser[0]['id_adm']),
            'name'          => $rowUser[0]['nome'],
            'email'         => $rowUser[0]['email'],
            'celular'       => $rowUser[0]['celular'],
            'tel'           => $rowUser[0]['telefone'],
            'nivel'         => intval($rowUser[0]['nivel']),
            'foto'          => $data["image"], //$perfil[0]['foto'],
            'id_user'       => intval($rowUser[0]['id_user']),
            'id_supervisao' => intval($rowUser[0]['id_supervisao']),
            'id_igreja'     => intval($rowUser[0]['id_igreja'])
        ];

        session()->set(['data' => $dataSession]);


        return redirect()->to(site_url('admin'));
    }

    public function cadUser(string $tipo, array $input): bool
    {
        try {
            if (in_array($tipo, ['superadmin', 'gerente', 'supervisor', 'pastor', 'igreja'])) {
                $nivel = ($tipo == 'superadmin') ? 1 : (($tipo == 'gerente') ? 2 : (($tipo == 'supervisor') ? 3 : 4));
                $data = [
                    'tipo'        => $tipo,
                    'id_perfil'   => $input['id_perfil'],
                    'email'       => $input['email'],
                    'password'    => $input['password'],
                    'nivel'       => $nivel,
                    'id_adm'      => $input['id_adm']
                ];
            } else {
                // Se o tipo não for nenhum dos tipos conhecidos ('admin', 'gerente', 'supervisor', 'pastor', 'igreja')
                throw new \InvalidArgumentException("Tipo de permissão não definido: $tipo");
            }

            // Inserir dados no banco de dados
            return $this->insert($data);
        } catch (\Exception $e) {
            // Capturar qualquer exceção lançada
            log_message('error', 'Erro no método cadUser: ' . $e->getMessage());

            if ($e->getCode() == 1062) { // Verificar o código de erro específico para duplicação de chave única (pode variar dependendo do banco de dados)
                return "E-mail duplicado. Por favor, escolha outro e-mail.";
            } else {
                return $e->getMessage();
            }
        }
    }

    /**
     * MELHORAR RETORNO DE DADOS
     */

    function userData()
    {

        if (session('data')['tipo'] == 'superadmin') {
            $buscaPerfil = new AdministradoresModel();
            $perfil = $buscaPerfil->find(session('data')['id_perfil']);
        } elseif (session('data')['tipo'] == 'gerente') {
            $buscaPerfil = new GerentesModel();
            $perfil = $buscaPerfil->find(session('data')['id_perfil']);
        } elseif (session('data')['tipo'] == 'supervisor') {
            $buscaPerfil = new SupervisoresModel();
            $perfil = $buscaPerfil->find(session('data')['id_perfil']);
        } elseif (session('data')['tipo'] == 'pastor') {
            $buscaPerfil = new PastoresModel();
            $perfil = $buscaPerfil->find(session('data')['id_perfil']);
        } elseif (session('data')['tipo'] == 'igreja') {
            $buscaPerfil = new IgrejasModel();
            $perfil = $buscaPerfil->find(session('data')['id_perfil']);
        } else {
            throw new ErrorException("Tipo de permissão não definida");
        }

        return $perfil;
    }
}
