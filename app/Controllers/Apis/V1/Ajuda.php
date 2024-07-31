<?php

namespace App\Controllers\Apis\V1;

use App\Models\AjudaModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Ajuda extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    protected $modelAjuda;

    public function __construct()
    {
        $this->modelAjuda = new AjudaModel();
    }
    public function index()
    {
        //

        return $this->respond($this->modelAjuda->lista($this->request->getGet()));
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
        helper('auxiliar');

        //
        $title     = $this->request->getPost('titulo');
        $conteudo  = $this->request->getPost('conteudo');
        $tags      = $this->request->getPost('tags');

        $data = [
            "id_admin"  => session('data')['idAdm'],
            "id_user"   => session('data')['id'],
            "slug"      => createSlug($title . "-" . time(), '-', true),
            "titulo"    => $title,
            "conteudo"  => $conteudo,
            "tags"      => $tags
        ];

        $status = $this->modelAjuda->insert($data);

        if ($status === false) {
            return $this->fail($this->modelAjuda->errors());
        }

        return $this->respondCreated(['msg' => 'Post criado com sucesso.', 'id' => $status]);
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
        if ($id) {
            $title     = $this->request->getPost('titulo');
            $conteudo  = $this->request->getPost('conteudo');
            $tags      = $this->request->getPost('tags');

            $data = [
                "titulo"    => $title,
                "conteudo"  => $conteudo,
                "tags"      => $tags
            ];

            $status = $this->modelAjuda->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelAjuda->errors());
            }

            return $this->respondCreated(['msg' => 'Post atualizado com sucesso.', 'id' => $status]);
        } else {
            return $this->fail('Erro ID');
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
        if ($id) {
            $row = $this->modelAjuda->select('slug')->find($id);
            if ($row) {
                $cache = service('cache');
                $cache->delete($row['slug']);
                $this->modelAjuda->delete($id);
                return $this->respond(['msg' => 'Conteúdo excluído com sucesso.']);
            }else{
                return $this->fail('[002] Error ID');
            }
        } else {
            return $this->fail('[001] Error ID');
        }
    }
}
