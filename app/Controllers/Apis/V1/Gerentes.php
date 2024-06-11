<?php

namespace App\Controllers\Apis\v1;

use App\Libraries\UploadsLibraries;
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
        if ($this->request->getGet("search") == "false") {
            $data = $this->modelGerentes->listSearch();
        } else {
            $data = $this->modelGerentes->listSearch($this->request->getGet());
        }

        return $this->respond($data);
    }

    public function list(){
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

            $id = $this->modelGerentes->insert($data);

            if ($id === false) {
                return $this->fail($this->modelGerentes->errors());
            }

            $dataUser = [
                'id_perfil' => $id,
                'email' => $input['email'],
                'password' => (isset($input['password'])) ? $input['password'] : '123456',
                'id_adm' => session('data')['id']
            ];

            $modelUser->cadUser('gerente', $dataUser);

            


            if (!empty($input['filepond'])) {
                $librariesUpload = new UploadsLibraries();
                $upload = $librariesUpload->filePond('gerentes', $id, $input);
                if (file_exists($upload['file'])) {
                    $update = [
                        'foto' => $upload['newName']
                    ];
                    $status = $this->modelGerentes->update($id, $update);
                    if ($status === false) {
                        return $this->fail($this->modelGerentes->errors());
                    }
                } else {
                    // Ocorreu um erro ao salvar a imagem
                    throw new Exception('Erro ao salvar a imagem.');
                }
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
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getRawInput();

            $data = [
                "id_adm"  => session('data')['idAdm'],
                "id_user" =>  session('data')['id'],
                "nome" => $input['nome'],
                "sobrenome" => $input['sobrenome'],
                "cpf" => preg_replace('/[^0-9]/', '', $input['cpf']),
                "email" => $input['email'],
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

            if (isset($input['filepond'])) {
                // Decodifica os dados JSON enviados pelo FilePond
                $file = json_decode($input['filepond']);

                // Verifica se os dados foram decodificados corretamente e se contêm uma imagem
                if ($file && isset($file->data)) {
                    // Decodifica a string base64 da imagem de volta para os dados binários da imagem
                    $image_data = base64_decode($file->data);

                    // Define o caminho onde você deseja salvar a imagem
                    $image_path = FCPATH . 'assets/img/gerentes/' . $id . '/';

                    // Cria o diretório se ele não existir
                    if (!is_dir($image_path)) {
                        mkdir($image_path, 0777, true);
                    }

                    // Gera um nome de arquivo único para a imagem
                    $image_name = uniqid();

                    // Salva a imagem no servidor
                    $file_path = $image_path . $image_name . '.png';
                    file_put_contents($file_path, $image_data);

                    $image = \Config\Services::image();

                    $image->withFile($file_path)
                        ->resize(50, 50, true, 'height')
                        ->convert(IMAGETYPE_WEBP)
                        ->save(FCPATH . 'assets/img/gerentes/' . $id . '/' . $image_name . '.webp');

                    if (file_exists($file_path)) {
                        $update = [
                            'foto' => '/assets/img/gerentes/' . $id . '/' . $image_name . '.webp'
                        ];
                        $status = $this->modelGerentes->update($id, $update);
                        if ($status === false) {
                            return $this->fail($this->modelGerentes->errors());
                        }
                    } else {
                        // Ocorreu um erro ao salvar a imagem
                        throw new Exception('Erro ao salvar a imagem.');
                    }
                }
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
