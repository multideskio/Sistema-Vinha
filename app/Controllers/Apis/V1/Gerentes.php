<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\EmailsLibraries;
use App\Libraries\NotificationLibrary;
use App\Libraries\UploadsLibraries;
use App\Libraries\WhatsappLibraries;
use App\Models\ConfigMensagensModel;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Gerentes extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */

    protected $modelGerentes;

    public function __construct()
    {
        $this->modelGerentes = new \App\Models\GerentesModel;
        helper('auxiliar');
    }

    public function index()
    {
        $data = $this->modelGerentes->listSearch($this->request->getGet());
        return $this->respond($data);
    }

    public function list()
    {
        return $this->respond($this->modelGerentes->findAll());
    }

    /**
     * Return the properties of a resource object
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //


        $search = $this->modelGerentes
            ->select('gerentes.*')
            ->select('usuarios.email, usuarios.whatsapp AS sendWhatsapp, usuarios.id AS id_login')
            ->join('usuarios', 'usuarios.id_perfil = gerentes.id')
            ->where('usuarios.tipo', 'gerente')
            ->find($id);

        if ($search) {
            $data = [
                "id" => $search['id'],
                "id_login" => $search['id_login'],
                "nome" => $search['nome'],
                "sobrenome" => $search['sobrenome'],
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
        $db = \Config\Database::connect();  // Conecta ao banco de dados
        $modelUser = new UsuariosModel();

        // Inicia a transação
        $db->transBegin();

        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            // Validação do e-mail
            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new \Exception("Esse email já está cadastrado no sistema.", 1);
            }

            $celular = $input['cel'];
            $nome    = $input['nome'];
            $email   = $input['email'];

            // Dados a serem inseridos
            $data = [
                "id_adm"       => session('data')['idAdm'],
                "id_user"      => session('data')['id'],
                "nome"         => $nome,
                "sobrenome"    => $input['sobrenome'],
                "cpf"          => $input['cpf'],
                "uf"           => $input['uf'],
                "cidade"       => $input['cidade'],
                "cep"          => $input['cep'],
                "complemento"  => $input['complemento'],
                "bairro"       => $input['bairro'],
                "data_dizimo"  => $input['dia'],
                "telefone"     => $input['tel'],
                "celular"      => $celular
            ];

            // Inserção no banco de dados
            $id = $this->modelGerentes->insert($data);

            if ($id === false) {
                // Se a inserção falhar, reverte a transação e retorna o erro
                $db->transRollback();
                return $this->fail($this->modelGerentes->errors(), 400);
            }

            // Dados para a criação do usuário
            $dataUser = [
                'id_perfil' => $id,
                'email'     => $email,
                'password'  => $input['password'] ?? '123456', // Usando operador null coalescing
                'id_adm'    => session('data')['id']
            ];

            // Criação do usuário
            $user = $modelUser->cadUser('gerente', $dataUser);

            if (!$user) {
                // Se a criação do usuário falhar, reverte a transação e retorna o erro
                $db->transRollback();
                return $this->fail('Falha ao criar o usuário.', 400);
            }
            // Confirma a transação
            $db->transCommit();

            // Notificações
            $notification = new \App\Libraries\NotificationLibrary();

            //Verifica
            if ($celular) {
                //$notification->sendWelcomeMessage($nome, $email, $celular);
            }

            //$notification->sendVerificationEmail($email, $nome);

            // Retorno de sucesso
            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);
        } catch (\Exception $e) {
            // Reverte a transação em caso de qualquer exceção
            $db->transRollback();
            return $this->fail(['error' => $e->getMessage()], 400);
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

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return ResponseInterface
     */

    public function links($id = null)
    {
        $input = $this->request->getRawInput();

        $data = [
            'facebook'  => $input['linkFacebook'],
            'instagram' => $input['linkInstagram'],
            'website'   => $input['linkWebsite'],
        ];

        $status = $this->modelGerentes->update($id, $data);

        if ($status === false) {
            return $this->fail($this->modelGerentes->errors());
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
            $path = "gerentes/{$id}/" . $file->getRandomName();
            $uploadPath = $uploadLibraries->upload($file, $path);

            $data = [
                'foto' => $uploadPath
            ];

            if (!$this->modelGerentes->update($id, $data)) {
                return $this->fail($this->modelGerentes->errors());
            }

            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $uploadPath]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }





    public function update($id = null)
    {
        //

        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getRawInput();

            $data = [
                "nome" => $input['nome'],
                "sobrenome" => $input['sobrenome'],
                "cpf" => preg_replace('/[^0-9]/', '', $input['cpf']),
                "uf" => $input['uf'],
                "cidade" => $input['cidade'],
                "cep" => preg_replace('/[^0-9]/', '', $input['cep']),
                "complemento" => $input['complemento'],
                "bairro" => $input['bairro'],
                "data_dizimo" => $input['dia'],
                "telefone" => preg_replace('/[^0-9]/', '', $input['tel']),
                "celular" => preg_replace('/[^0-9]/', '', $input['cel'])
            ];

            $status = $this->modelGerentes->update($id, $data);
            if ($status === false) {
                return $this->fail($this->modelGerentes->errors());
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
        if ($this->modelGerentes->delete($id)) {
            $image_path = FCPATH . 'assets/img/gerentes/' . $id . '/';
            deleteFolder($image_path);
            return $this->respondDeleted(['msg' => lang("Sucesso.excluir")]);
        } else {
            return $this->fail(lang("Errors.excluir"));
        }
    }
}
