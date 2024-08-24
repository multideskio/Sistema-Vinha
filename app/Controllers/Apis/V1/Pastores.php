<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\UploadsLibraries;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Pastores extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    protected $modelPastores;

    public function __construct()
    {
        $this->modelPastores = new \App\Models\PastoresModel;
    }

    public function index()
    {
        //
        $data = $this->modelPastores->listSearch($this->request->getGet());
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
        try {
            $search = $this->modelPastores
                ->select('pastores.*')
                ->select('usuarios.email, usuarios.whatsapp AS sendWhatsapp, usuarios.id AS id_login')
                ->join('usuarios', 'usuarios.id_perfil = pastores.id')
                ->join('supervisores', 'supervisores.id = pastores.id_supervisor')
                ->where('usuarios.tipo', 'pastor')
                ->find($id);

            if ($search) {
                $data = [
                    "id" => $search['id'],
                    "id_login" => $search['id_login'],
                    "nome" => $search['nome'],
                    "sobrenome" => $search['sobrenome'],
                    "idSupervisor" => $search['id_supervisor'],
                    "cpf" => $search['cpf'],
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
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
        $db = \Config\Database::connect();  // Conecta ao banco de dados
        $modelUser = new UsuariosModel();
        $db->transBegin();

        try {

            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new Exception("Esse email já está cadastrado no sistema.", 1);
            };

            $celular = $input['cel'];
            $nome    = $input['nome'];
            $email   = $input['email'];

            $data = [
                "id_adm"       => session('data')['idAdm'],
                "id_user"      => session('data')['id'],
                'id_supervisor' => $input['selectSupervisor'],
                'nome' => $nome,
                'sobrenome' => $input['sobrenome'],
                'cpf' => $input['cpf'],
                'uf' => $input['uf'],
                'cidade' => $input['cidade'],
                'cep' => $input['cep'],
                'complemento' => $input['complemento'],
                'nascimento' => $input['nascimento'],
                'bairro' => $input['bairro'],
                'data_dizimo' => $input['dia'],
                'telefone' => $input['tel'],
                'celular' => $celular
            ];

            $id = $this->modelPastores->insert($data);

            if ($id === false) {
                // Se a inserção falhar, reverte a transação e retorna o erro
                $db->transRollback();
                return $this->fail($this->modelPastores->errors(), 400);
            }

            $dataUser = [
                'id_perfil' => $id,
                'email' => $email,
                'password' => (isset($input['password'])) ? $input['password'] : '123456',
                'id_adm' => session('data')['id']
            ];

            $user = $modelUser->cadUser('pastor', $dataUser);

            if ($user === false) {
                $db->transRollback();
                return $this->fail($modelUser->errors());
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

            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id, 'user' => $user]);
        } catch (\Exception $e) {

            $this->modelPastores->transRollback(); // Rollback em caso de exceção

            return $this->fail($e->getMessage());
        }
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
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

        $status = $this->modelPastores->update($id, $data);

        if ($status === false) {
            return $this->fail($this->modelPastores->errors());
        }

        return $this->respondUpdated(['msg' => lang("Sucesso.alterado"), 'id' => $id]);
    }

    public function foto($id = null)
    {
        $request = service('request');
        $file = $request->getFile('foto');

        if (!$file || !$file->isValid()) {
            log_message('error', 'Nenhum arquivo foi enviado ou o arquivo é inválido.');
            return $this->fail('Nenhum arquivo foi enviado ou o arquivo é inválido.');
        }

        $fileUploader = new UploadsLibraries();

        try {
            $this->modelPastores->db->transBegin();

            // Realiza o upload, seja para S3 ou local
            $path = "pastores/{$id}/" . $file->getRandomName();
            $uploadPath = $fileUploader->upload($file, $path);

            $data = [
                'foto' => $uploadPath
            ];

            if (!$this->modelPastores->update($id, $data)) {
                $this->modelPastores->db->transRollback();
                log_message('error', 'Erro ao atualizar o registro no banco de dados: ' . implode(', ', $this->modelPastores->errors()));
                return $this->fail($this->modelPastores->errors());
            }

            $this->modelPastores->db->transCommit();
            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $uploadPath]);
        } catch (\Exception $e) {
            if ($this->modelPastores->db->transStatus() === false) {
                $this->modelPastores->db->transRollback();
            }

            log_message('error', 'Erro ao enviar a imagem: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }



    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
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
                'id_supervisor' => $input['selectSupervisor'],
                'nome' => $input['nome'],
                'sobrenome' => $input['sobrenome'],
                'cpf' => $input['cpf'],
                'uf' => $input['uf'],
                'cidade' => $input['cidade'],
                'cep' => $input['cep'],
                'complemento' => $input['complemento'],
                //'nascimento' => $input['nascimento'],
                'bairro' => $input['bairro'],
                'data_dizimo' => $input['dia'],
                'telefone' => $input['tel'],
                'celular' => $input['cel']
            ];
            $status = $this->modelPastores->update($id, $data);
            if ($status === false) {
                return $this->fail($this->modelPastores->errors());
            }
            return $this->respondCreated(['msg' => lang("Sucesso.alterado"), 'id' => $id]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }

    public function dashboard() {}
}
