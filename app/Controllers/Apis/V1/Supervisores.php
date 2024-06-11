<?php

namespace App\Controllers\Apis\v1;

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
        if($this->request->getGet("search") == "false"){
            $data = $this->modelSupervisores->listSearch();
        }else{
            $data = $this->modelSupervisores->listSearch($this->request->getGet());
        }
        
        return $this->respond($data);
    }
    
    public function list(){
        
        $data = $this->modelSupervisores->cacheData(); 
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

        return $this->respond([]);
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

        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new Exception("Esse email já está cadastrado no sistema.", 1);
            };

            $data = [
                "id_adm"       => session('data')['idAdm'],
                "id_user"      => session('data')['id'],
                "id_regiao"    => $input['selectRegiao'],
                "id_gerente"   => $input['selectGerentes'],
                "nome"         => $input['nome'],
                "sobrenome"    => $input['sobrenome'],
                "cpf"          => $input['cpf'],
                "uf"           => $input['uf'],
                "cidade"       => $input['cidade'],
                "cep"          => $input['cep'],
                "complemento"  => $input['complemento'],
                "bairro"       => $input['bairro'],
                "data_dizimo"  => $input['dia'],
                "telefone"     => $input['tel'],
                "celular"      => $input['cel']
            ];

            $id = $this->modelSupervisores->insert($data);

            $dataUser = [
                'id_perfil' => $id,
                'email' => $input['email'],
                'password' => (isset($input['password'])) ? $input['password'] : '123456',
                'id_adm' => session('data')['id']
            ];

            $modelUser->cadUser('supervisor', $dataUser);

            if (!empty($input['filepond'])) {
                $librariesUpload = new UploadsLibraries();
                $upload = $librariesUpload->filePond('supervisor', $id, $input);
                if (file_exists($upload['file'])) {
                    $update = [
                        'foto' => $upload['newName']
                    ];
                    $status = $this->modelSupervisores->update($id, $update);
                    if ($status === false) {
                        return $this->fail($this->modelSupervisores->errors());
                    }
                } else {
                    // Ocorreu um erro ao salvar a imagem
                    throw new Exception('Erro ao salvar a imagem.');
                }
            }

            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);

            //return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);

        }catch(\Exception $e){
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

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
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
