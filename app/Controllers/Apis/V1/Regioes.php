<?php

namespace App\Controllers\Apis\V1;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Regioes extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */
    protected $modelRegioes;

    public function __construct()
    {
        $this->modelRegioes = new \App\Models\RegioesModel;
    }

    public function index()
    {
        $data = $this->modelRegioes->listSearch($this->request->getGet());
        return $this->respond($data);
    }

    /**
     * The list function returns cached data from the modelRegioes model in PHP.
     * 
     * @return The `list()` function is returning the response from the `cacheData()` method of the
     * `modelRegioes` model.
     */
    public function list()
    {
        return $this->respond($this->modelRegioes->cacheData());
    }

    /**
     * Return the properties of a resource object
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
        $regioes = $this->modelRegioes->find($id);
        return $this->respond($regioes);
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
        try {
            //$input = $this->request->getJSON();
            $input = $this->request->getVar();

            $data = [
                "id_adm"    => session('data')['idAdm'],
                "id_user"   =>  session('data')['id'],
                "nome"      => $input['regiao'],
                "descricao" => $input->descricao ?? ""
            ];

            $id = $this->modelRegioes->insert($data);
            if ($id === false) {
                return $this->fail($this->modelRegioes->errors());
            }
            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);
        } catch (\Exception $e) {

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
        try {
            $input = $this->request->getRawInput();
            $data = [
                //"id_adm"    => session('data')['idAdm'],
                "id_user"   => session('data')['id'],
                "nome"      => $input['regiaoUpdate'],
                "descricao" => $input['descUpdate'] ?? null
            ];
            $status = $this->modelRegioes->update($id, $data);
            if ($status === false) {
                return $this->fail($this->modelRegioes->errors());
            }
            return $this->respond(['msg' => lang("Sucesso.alterado"), 'id' => intval($id)]);
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
        if ($this->modelRegioes->delete($id)) {
            return $this->respondDeleted(['msg' => lang("Sucesso.excluir")]);
        } else {
            return $this->fail(lang("Errors.excluir"));
        }
    }
}
