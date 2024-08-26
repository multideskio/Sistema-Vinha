<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\UploadsLibraries;
use App\Models\AdministradoresModel;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Administradores extends ResourceController
{
    use ResponseTrait;
    /**
     * 
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    protected $modelAdmin;

    public function __construct()
    {
        $this->modelAdmin = new AdministradoresModel();
    }

    public function index()
    {
        //

        $data = $this->modelAdmin->listSearch($this->request->getGet());
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
        $search = $this->modelAdmin
            ->select('administradores.*')
            ->select('usuarios.email, usuarios.whatsapp AS sendWhatsapp, usuarios.confirmado')
            ->join('usuarios', 'usuarios.id_perfil = administradores.id')
            ->where('usuarios.tipo', 'superadmin')
            ->find($id);

        if ($search) {
            $data = [
                "id" => intval($search['id']),
                "nome" => $search['nome'],
                "sobrenome" => $search['sobrenome'],
                "cpf" => $search['cpf'],
                "foto" => $search['foto'],
                "uf" => $search['uf'],
                "cidade" => $search['cidade'],
                "cep" => $search['cep'],
                "complemento" => $search['complemento'],
                "bairro" => $search['bairro'],
                "telefone" => $search['telefone'],
                "celular" => $search['celular'],
                "facebook" => $search['facebook'],
                "instagram" => $search['instagram'],
                "created_at" => $search['created_at'],
                "website" => $search['website'],
                "email" => $search['email'],
                "sendWhatsapp" => intval($search['sendWhatsapp']),
                "confirmado" => intval($search['confirmado'])
            ];
            return $this->respond($data);
        } else {
            return $this->failNotFound();
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
        $modelUser = new UsuariosModel();

        try {
            $this->modelAdmin->transStart(); // Iniciar transação

            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new Exception("Esse email já está cadastrado no sistema.", 1);
            };

            // Validação do e-mail
            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new \Exception("Esse email já está cadastrado no sistema.", 1);
            }

            $celular = $input['cel'];
            $nome    = $input['nome'];
            $email   = $input['email'];


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
                //"telefone"     => $input['tel'],
                "celular"      => $celular
            ];

            $id = $this->modelAdmin->insert($data);

            if ($id === false) {
                return $this->fail($this->modelAdmin->errors());
            }

            $dataUser = [
                'id_perfil' => $id,
                'email'     => $email,
                'password'  => (isset($input['password'])) ? $input['password'] : '123456',
                'id_adm'    => session('data')['id']
            ];

            $user = $modelUser->cadUser('superadmin', $dataUser);

            if ($user === false) {
                return $modelUser->fail($this->modelAdmin->errors());
            }

            $this->modelAdmin->transComplete();

            // Notificações
            $notification = new \App\Libraries\NotificationLibrary();
            
            //Verifica
            if ($celular) {
                $notification->sendWelcomeMessage($nome, $email, $celular); 
            }

            $notification->sendVerificationEmail($email, $nome);

            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);
        } catch (\Exception $e) {

            $this->modelAdmin->transRollback();

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
                "telefone" => preg_replace('/[^0-9]/', '', $input['tel']),
                "celular" => preg_replace('/[^0-9]/', '', $input['cel'])
            ];

            $status = $this->modelAdmin->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelAdmin->errors());
            }

            return $this->respondCreated(['msg' => lang("Sucesso.alterado"), 'id' => $id]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function links($id = null)
    {
        $input = $this->request->getRawInput();

        $data = [
            'facebook'  => $input['linkFacebook'],
            'instagram' => $input['linkInstagram'],
            'website'   => $input['linkWebsite'],
        ];

        $status = $this->modelAdmin->update($id, $data);

        if ($status === false) {
            return $this->fail($this->modelAdmin->errors());
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
            $fileUploader = new UploadsLibraries();
            $path = "admin/{$id}/" . $file->getRandomName();
            $uploadPath = $fileUploader->upload($file, $path);

            $data = [
                'foto' => $uploadPath
            ];

            if (!$this->modelAdmin->update($id, $data)) {
                return $this->fail($this->modelAdmin->errors());
            }

            $session = session();
            $sessionData = $session->get('data');

            if (is_array($sessionData)) {
                $sessionData['foto'] = $uploadPath;
            } else {
                $sessionData = ['foto' => $uploadPath];
            }

            $session->set('data', $sessionData);

            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $uploadPath]);
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
}
