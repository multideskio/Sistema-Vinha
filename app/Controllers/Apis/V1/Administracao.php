<?php

namespace App\Controllers\Apis\v1;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Administracao extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */

    protected $modelAdmin;

    public function __construct()
    {
        $this->modelAdmin = new \App\Models\AdminModel;
    }
    public function index()
    {
        //

        $result = $this->modelAdmin->cacheData();

        return $this->respond($result);
    }

    /**
     * Return the properties of a resource object
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //

        return $this->failNotFound();
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
        return $this->failNotFound();
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
        return $this->failNotFound();
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
        return $this->failNotFound();
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
            if (!$id) {
                //TRADUZIR
                throw new Exception('ID não informado');
            }

            $request = service('request');

            $input = $request->getVar();

            $data = [
                'id' => $id,
                'cnpj' => ($input->cnpj) ? $input->cnpj : null,
                'empresa' => ($input->empresa) ? $input->empresa : null,
                'logo' => ($input->logo) ? $input->logo : null,
                'email' => ($input->email) ? $input->email : null,
                'email_remetente' => ($input->email_remetente) ? $input->email_remetente : null,
                'nome_remetente' => ($input->nome_remetente) ? $input->nome_remetente : null,
                'cep' => ($input->cep) ? $input->cep : null,
                'uf' => ($input->uf) ? $input->uf : null,
                'cidade' => ($input->cidade) ? $input->cidade : null,
                'bairro' => ($input->bairro )? $input->bairro : null,
                'complemento' => ($input->complemento) ? $input->complemento : null,
                'telefone' => ($input->telefone) ? $input->telefone : null,
                'celular' => ($input->celular) ? $input->celular : null
            ];

            $this->modelAdmin->save($data);

            //TRADUZIR
            return $this->respond(['msg' => 'Atualizado com sucesso!']);
        } catch (\Exception $e) {
            return $this->fail(['error' => $e->getMessage()]);
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
        return $this->failNotFound();
    }
}
