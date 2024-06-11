<?php

namespace App\Controllers\Apis\v1;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Emails extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */
    protected $modelEmails;

    public function __construct()
    {
        $this->modelEmails = new \App\Models\EmailsModel ;
    }
    public function index()
    {
        //
        $msgs = $this->modelEmails->cacheData();
        return $this->respond($msgs);

    }

    /**
     * Return the properties of a resource object
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
        $emails = $this->modelEmails->find($id);
        return $this->respond($emails);
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
            $input = $this->request->getJSON();

            $builder = $this->modelEmails->select('id')->where("tipo", $input->tipo)->findAll();

            if(count($builder)){
                $data = [
                    "id"        => $builder[0]['id'], 
                    "tipo"      => $input->tipo,
                    "mensagem"  => $input->mensagem,
                    "id_user"   => $input->id_user,
                    "id_adm"    => $input->id_adm
                ];
            }else{
                $data = [
                    "tipo"      => $input->tipo,
                    "mensagem"  => $input->mensagem,
                    "id_user"   => $input->id_user,
                    "id_adm"    => $input->id_adm
                ];
            }

            $id = $this->modelEmails->save($data);
            
            
            if ($id === false) {
                return $this->fail($this->modelEmails->errors());

                
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
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
        if ($this->modelEmails->delete($id)) {
            return $this->respondDeleted(['msg' => lang("Sucesso.excluir")]);
        } else {
            return $this->fail(lang("Errors.excluir"));
        }
    }
}
