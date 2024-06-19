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
                ->select('usuarios.email, usuarios.whatsapp AS sendWhatsapp')
                ->join('usuarios', 'usuarios.id_perfil = pastores.id')
                ->join('supervisores', 'supervisores.id = pastores.id_supervisor')
                ->where('usuarios.tipo', 'pastor')
                ->find($id);

            if ($search) {
                $data = [
                    "id" => $search['id'],
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
        $modelUser = new UsuariosModel();

        try {

            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            $this->modelPastores->transStart(); // Iniciar transação

            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new Exception("Esse email já está cadastrado no sistema.", 1);
            };

            $data = [
                "id_adm"       => session('data')['idAdm'],
                "id_user"      => session('data')['id'],
                'id_supervisor' => $input['selectSupervisor'],
                'nome' => $input['nome'],
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
                'celular' => $input['cel']
            ];

            $id = $this->modelPastores->insert($data);

            $dataUser = [
                'id_perfil' => $id,
                'email' => $input['email'],
                'password' => (isset($input['password'])) ? $input['password'] : '123456',
                'id_adm' => session('data')['id']
            ];


            $user = $modelUser->cadUser('pastor', $dataUser);

            if ($user === false) {
                return $this->fail($modelUser->errors());
            }

            if (!empty($input['filepond'])) {
                $librariesUpload = new UploadsLibraries();
                $upload = $librariesUpload->filePond('pastor', $id, $input);
                if (file_exists($upload['file'])) {
                    $update = [
                        'foto' => $upload['newName']
                    ];
                    $status = $this->modelPastores->update($id, $update);
                    if ($status === false) {
                        return $this->fail($this->modelPastores->errors());
                    }
                } else {
                    // Ocorreu um erro ao salvar a imagem
                    throw new Exception('Erro ao salvar a imagem.');
                }
            }

            $this->modelPastores->transComplete();

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
        $file    = $request->getFile('foto'); // O nome do campo deve corresponder ao do frontend
        try {
            $uploadLibraries = new UploadsLibraries;
            $upload = $uploadLibraries->uploadCI($file, $id, 'pastores');
            $data = [
                'foto' => $upload['foto']
            ];
            $status = $this->modelPastores->update($id, $data);
            if ($status === false) {
                return $this->fail($this->modelPastores->errors());
            }
            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $upload]);
        } catch (\Exception $e) {
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
}
