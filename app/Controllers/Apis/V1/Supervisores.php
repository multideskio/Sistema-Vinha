<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\UploadsLibraries;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Supervisores extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */

    protected $modelSupervisores;

    public function __construct()
    {
        $this->modelSupervisores = new \App\Models\SupervisoresModel();
        helper('auxiliar');
    }

    public function index()
    {
        //
        if ($this->request->getGet("search") == "false") {
            $data = $this->modelSupervisores->listSearch();
        } else {
            $data = $this->modelSupervisores->listSearch($this->request->getGet());
        }

        return $this->respond($data);
    }

    public function list()
    {
        $cache    = \Config\Services::cache();
        $cacheKey = "select_supervisores";

        // Check if the cache exists
        if ($cacheData = $cache->get($cacheKey)) {
            return $this->respond($cacheData);
            //echo "esta aqui";
        } else {
            $data = $this->modelSupervisores->findAll();
            $cache->save($cacheKey, $data, 3600);

            return $this->respond($data);
        }
    }

    /**
     * Return the properties of a resource object
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
        $search = $this->modelSupervisores
            ->select('supervisores.*')
            ->select('usuarios.email, usuarios.whatsapp AS sendWhatsapp, usuarios.id AS id_login')
            ->select('regioes.nome AS regiao')
            ->select('gerentes.nome AS gerente, gerentes.sobrenome AS sobregerente')
            ->join('usuarios', 'usuarios.id_perfil = supervisores.id')
            ->join('regioes', 'regioes.id = supervisores.id_regiao')
            ->join('gerentes', 'gerentes.id = supervisores.id_gerente')
            ->where('usuarios.tipo', 'supervisor')
            ->find($id);

        if ($search) {
            $data = [
                "id"           => $search['id'],
                "nome"         => $search['nome'],
                "id_login"     => $search['id_login'],
                "sobrenome"    => $search['sobrenome'],
                "idGerente"    => $search['id_gerente'],
                "idRegiao"     => $search['id_regiao'],
                "regiao"       => $search['regiao'],
                "gerente"      => $search['gerente'] . " " . $search['sobregerente'],
                "cpf"          => $search['cpf'],
                "foto"         => $search['foto'],
                "uf"           => $search['uf'],
                "cidade"       => $search['cidade'],
                "cep"          => $search['cep'],
                "complemento"  => $search['complemento'],
                "bairro"       => $search['bairro'],
                "data_dizimo"  => $search['data_dizimo'],
                "telefone"     => $search['telefone'],
                "celular"      => $search['celular'],
                "facebook"     => $search['facebook'],
                "instagram"    => $search['instagram'],
                "created_at"   => $search['created_at'],
                "website"      => $search['website'],
                "email"        => $search['email'],
                "sendWhatsapp" => $search['sendWhatsapp'],
            ];

            return $this->respond($data);
        } else {
            return $this->failNotFound();
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
        $modelUser = new UsuariosModel();

        $db = \Config\Database::connect();  // Conecta ao banco de dados

        // Inicia a transação
        $db->transBegin();
        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new Exception("Esse email já está cadastrado no sistema.", 1);
            }
            $celular = $input['cel'];
            $nome    = $input['nome'];
            $email   = $input['email'];
            $data    = [
                "id_adm"      => session('data')['idAdm'],
                "id_user"     => session('data')['id'],
                "id_regiao"   => $input['selectRegiao'],
                "id_gerente"  => $input['selectGerentes'],
                "nome"        => $nome,
                "sobrenome"   => $input['sobrenome'],
                "cpf"         => $input['cpf'],
                "uf"          => $input['uf'],
                "cidade"      => $input['cidade'],
                "cep"         => $input['cep'],
                "complemento" => $input['complemento'],
                "bairro"      => $input['bairro'],
                "data_dizimo" => $input['dia'],
                "telefone"    => $input['tel'],
                "celular"     => $celular,
            ];
            $id = $this->modelSupervisores->insert($data);

            if ($id === false) {
                // Se a inserção falhar, reverte a transação e retorna o erro
                $db->transRollback();

                return $this->fail($this->modelSupervisores->errors(), 400);
            }
            $dataUser = [
                'id_perfil' => $id,
                'email'     => $email,
                'password'  => (isset($input['password'])) ? $input['password'] : '123456',
                'id_adm'    => session('data')['id'],
            ];
            $modelUser->cadUser('supervisor', $dataUser);
            // Confirma a transação
            $db->transCommit();
            // Notificações
            $notification = new \App\Libraries\NotificationLibrary();

            //Verifica
            if ($celular) {
                $notification->sendWelcomeMessage($nome, $email, $celular);
            }
            $notification->sendVerificationEmail($email, $nome);

            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);
        } catch (\Exception $e) {
            // Reverte a transação em caso de qualquer exceção
            $db->transRollback();

            return $this->fail($e->getMessage());
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    public function links($id = null)
    {
        $input = $this->request->getRawInput();

        $data = [
            'facebook'  => $input['linkFacebook'],
            'instagram' => $input['linkInstagram'],
            'website'   => $input['linkWebsite'],
        ];

        $status = $this->modelSupervisores->update($id, $data);

        if ($status === false) {
            return $this->fail($this->modelSupervisores->errors());
        }

        return $this->respondUpdated(['msg' => lang("Sucesso.alterado"), 'id' => $id]);
    }

    public function foto($id = null)
    {
        $request = service('request');
        $file    = $request->getFile('foto'); // O nome do campo deve corresponder ao do frontend

        if (!$file || !$file->isValid()) {
            return $this->fail('Nenhum arquivo foi enviado ou o arquivo é inválido.');
        }

        try {
            $uploadLibraries = new UploadsLibraries();
            $path            = "supervisores/{$id}/" . $file->getRandomName();
            $uploadPath      = $uploadLibraries->upload($file, $path);

            $data = [
                'foto' => $uploadPath,
            ];

            if (!$this->modelSupervisores->update($id, $data)) {
                return $this->fail($this->modelSupervisores->errors());
            }

            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $uploadPath]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getRawInput();

            $data = [
                "id_gerente"  => $input['selectGerentes'],
                "id_regiao"   => $input['selectRegiao'],
                "nome"        => $input['nome'],
                "sobrenome"   => $input['sobrenome'],
                "cpf"         => preg_replace('/[^0-9]/', '', $input['cpf']),
                "uf"          => $input['uf'],
                "cidade"      => $input['cidade'],
                "cep"         => preg_replace('/[^0-9]/', '', $input['cep']),
                "complemento" => $input['complemento'],
                "bairro"      => $input['bairro'],
                "data_dizimo" => $input['dia'],
                "telefone"    => preg_replace('/[^0-9]/', '', $input['tel']),
                "celular"     => preg_replace('/[^0-9]/', '', $input['cel']),
            ];

            $status = $this->modelSupervisores->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelSupervisores->errors());
            }

            return $this->respondCreated(['msg' => lang("Sucesso.alterado"), 'id' => $id]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }
}