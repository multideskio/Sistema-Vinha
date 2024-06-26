<?php

namespace App\Controllers\Apis\v1;

use App\Libraries\UploadsLibraries;
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

        $data = $this->modelAdmin->find($id);


        return $this->respond($data);
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

    public function foto($id = null)
    {


        $request = service('request');
        $file = $request->getFile('foto'); // O nome do campo deve corresponder ao do frontend
        try {
            $uploadLibraries = new UploadsLibraries;
            $upload = $uploadLibraries->uploadCI($file, $id, 'admin_geral');
            $data = [
                'logo' => $upload['foto']
            ];
            $status = $this->modelAdmin->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelAdmin->errors());
            }
            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $upload]);
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
            'site'   => $input['linkWebsite'],
        ];

        $status = $this->modelAdmin->update($id, $data);

        if ($status === false) {
            return $this->fail($this->modelAdmin->errors());
        }

        return $this->respondUpdated(['msg' => lang("Sucesso.alterado"), 'id' => $id]);
    }

    public function updateInfo($id = null)
    {
        try {
            if (!$id) {
                throw new Exception('ID não informado');
            }
            $request = service('request');
            $input = $request->getRawInput();
            $data = [
                'cnpj'        => $input['cnpj'],
                'empresa'     => $input['empresa'],
                'email'       => $input['email'],
                'cep'         => $input['cep'],
                'uf'          => $input['uf'],
                'cidade'      => $input['cidade'],
                'bairro'      => $input['bairro'],
                'complemento' => $input['complemento'],
                'telefone'    => $input['fixo'],
                'celular'     => $input['celular']
            ];
            $this->modelAdmin->update($id, $data);
            return $this->respond(['msg' => 'Atualizado com sucesso!']);
        } catch (\Exception $e) {
            return $this->fail(['error' => $e->getMessage()]);
        }
    }

    public function updateSmtp($id = null)
    {
        try {
            
            if (!$id) {
                throw new Exception('ID não informado');
            }

            $request = service('request');
            $input = $request->getRawInput();

            $data = [
                'email_remetente' => $input['emailRemetente'],
                'nome_remetente'  => $input['nomeRemetente'],
                'smtp_host'       => $input['smtpHOST'],
                'smtp_user'       => $input['smtpLOGIN'],
                'smtp_pass'       => $input['smtpPASS'],
                'smtp_port'       => $input['smtpPORT'],
                'ativar_smtp'     => ($input['ativarSMTP']) ?? 0,
                //'smtp_crypt'      => $input[''],
            ];
            
            $status = $this->modelAdmin->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelAdmin->errors());
            }
            return $this->respond(['msg' => $data]);
        
        } catch (\Exception $e) {
        
            return $this->fail(['error' => $e->getMessage()]);
        
        }
    }

    public function updateWa($id = null)
    {
    }

    public function update($id = null)
    {
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
