<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\UploadsLibraries;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Igrejas extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */
    protected $modelIgrejas;

    public function __construct()
    {
        $this->modelIgrejas = new \App\Models\IgrejasModel;
    }
    public function index()
    {
        //

        if ($this->request->getGet("search") == "false") {
            $data = $this->modelIgrejas->listSearch();
        } else {
            $data = $this->modelIgrejas->listSearch($this->request->getGet());
        }

        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
        $search = $this->modelIgrejas
            ->select('igrejas.*')
            ->select('usuarios.email, usuarios.whatsapp AS sendWhatsapp, usuarios.id AS id_login')
            ->join('usuarios', 'usuarios.id_perfil = igrejas.id')
            ->join('supervisores', 'supervisores.id = igrejas.id_supervisor')
            ->where('usuarios.tipo', 'igreja')
            ->find($id);

        if ($search) {
            $data = [
                "id" => $search['id'],
                "razaoSocial"  => $search['razao_social'],
                "nomeFantazia" => $search['fantasia'],
                "id_login" => $search['id_login'],
                "idSupervisor" => $search['id_supervisor'],
                "nomeTesoureiro" => $search['nome_tesoureiro'],
                "sobrenomeTesoureiro" => $search['sobrenome_tesoureiro'],
                "cpfTesoureiro" => $search['cpf_tesoureiro'],
                "cnpj" => $search['cnpj'],
                "fundacao" => $search['fundacao'],
                "foto" => $search['foto'],
                "uf" => $search['uf'],
                "cidade" => $search['cidade'],
                "cep" => $search['cep'],
                "complemento" => $search['complemento'],
                "bairro" => $search['bairro'],
                "data_dizimo" => $search['data_dizimo'],
                "telefone" => $search['telefone'],
                "celular" => $search['celular'],
                "facebook" => $search['facebook'],
                "instagram" => $search['instagram'],
                "created_at" => $search['created_at'],
                "website" => $search['website'],
                "email" => $search['email'],
                "sendWhatsapp" => $search['sendWhatsapp']
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
        $db = \Config\Database::connect();  // Conecta ao banco de dados
        $modelUser = new UsuariosModel();

        // Inicia a transação
        $db->transBegin();

        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new Exception("Esse email já está cadastrado no sistema.", 1);
            };

            $celular = $input['cel'];
            $nome    = $input['nome_tesoureiro'];
            $email   = $input['email'];


            $data = [
                "id_adm"       => session('data')['idAdm'],
                "id_user"      => session('data')['id'],
                "id_supervisor" => $input['selectSupervisor'],
                "nome_tesoureiro" => $nome,
                "sobrenome_tesoureiro" => $input['sobrenome_tesoureiro'],
                "cpf_tesoureiro" => $input['cpf'],
                "fundacao" => $input['fundacao'],
                "razao_social" => $input['razaosocial'],
                "fantasia" => $input['fantasia'],
                "cnpj" => $input['cnpj'],
                "uf" => $input['uf'],
                "cidade" => $input['cidade'],
                "cep" => $input['cep'],
                "complemento" => $input['complemento'],
                "bairro" => $input['bairro'],
                "data_dizimo" => $input['dia'],
                "telefone" => $input['tel'],
                "celular" => $celular
            ];

            $id = $this->modelIgrejas->insert($data);

            if ($id === false) {
                // Se a inserção falhar, reverte a transação e retorna o erro
                $db->transRollback();
                return $this->fail($this->modelIgrejas->errors(), 400);
            }

            $dataUser = [
                'id_perfil' => $id,
                'email' => $input['email'],
                'password' => (isset($input['password'])) ? $input['password'] : '123456',
                'id_adm' => session('data')['id']
            ];

            $idUser = $modelUser->cadUser('igreja', $dataUser);

            if ($idUser === false) {
                // Se a inserção falhar, reverte a transação e retorna o erro
                $db->transRollback();
                return $this->fail($modelUser->errors(), 400);
            }

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
            $this->modelIgrejas->transRollback(); // Rollback em caso de exceção
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
        $status = $this->modelIgrejas->update($id, $data);
        if ($status === false) {
            return $this->fail($this->modelIgrejas->errors());
        }
        return $this->respondUpdated(['msg' => lang("Sucesso.alterado"), 'id' => $id]);
    }

    public function foto($id = null)
    {
        $request = service('request');
        $file = $request->getFile('foto'); // O nome do campo deve corresponder ao do frontend

        if (!$file || !$file->isValid()) {
            return $this->fail('Nenhum arquivo foi enviado ou o arquivo é inválido.');
        }

        try {
            $uploadLibraries = new UploadsLibraries();
            $path = "igrejas/{$id}/" . $file->getRandomName();
            $uploadPath = $uploadLibraries->upload($file, $path);

            $data = [
                'foto' => $uploadPath
            ];

            if (!$this->modelIgrejas->update($id, $data)) {
                return $this->fail($this->modelIgrejas->errors());
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
                "id_supervisor" => $input['selectSupervisor'],
                "nome_tesoureiro" => $input['nome'],
                "sobrenome_tesoureiro" => $input['sobrenome'],
                "cpf_tesoureiro" => $input['cpf'],
                "fundacao" => $input['fundacao'],
                "razao_social" => $input['razaosocial'],
                "fantasia" => $input['fantasia'],
                "cnpj" => $input['cnpj'],
                "uf" => $input['uf'],
                "cidade" => $input['cidade'],
                "cep" => $input['cep'],
                "complemento" => $input['complemento'],
                "bairro" => $input['bairro'],
                "data_dizimo" => $input['dia'],
                "telefone" => $input['tel'],
                "celular" => $input['cel']
            ];
            $status = $this->modelIgrejas->update($id, $data);
            if ($status === false) {
                return $this->fail($this->modelIgrejas->errors());
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
