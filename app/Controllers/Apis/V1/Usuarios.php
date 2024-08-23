<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\UploadsLibraries;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Google as ConfigGoogle;
use ErrorException;
use Exception;

class Usuarios extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */
    protected $modelUsuarios;
    protected $modelPerfil;
    protected $LibrariesUpload;

    public function __construct()
    {
        $this->modelUsuarios = new \App\Models\UsuariosModel;
        $this->LibrariesUpload = new \App\Libraries\UploadsLibraries;
    }
    public function index()
    {
        //
        if ($this->request->getGet("search") == "false") {
            $data = $this->modelUsuarios->listGeral();
        } else {
            $data = $this->modelUsuarios->listGeral($this->request->getGet());
        }
        return $this->respond($data);
    }


    public function show($id = null)
    {
        //
        $list = $this->modelUsuarios
            ->select('usuarios.*, perfis.*')
            ->join('perfis', 'usuarios.id = perfis.id_user', 'left')
            ->find($id);
        return $this->respond($list);
    }

    /*
    public function new()
    {
        //
    }

    /**public function create()
    {

        $input = $this->request->getVar();
        $dataUser = [
            "nome"          => ($input['nome'])          ? $input['nome']          : NULL,
            "sobrenome"     => ($input['sobrenome'])     ? $input['sobrenome']     : NULL,
            "email"         => ($input['email'])         ? $input['email']         : NULL,
            "password"      => (isset($input['senha']))  ? $input['senha']      : "mudar123",
            "telefone"      => ($input['cel'])           ? preg_replace('/[^0-9]/', '', $input['cel'])      : NULL,
            "nivel"         => ($input['grupoPermissao']  == 1 || $input['grupoPermissao']  == 2)   ? $input['grupoPermissao']         : 2,
            "id_adm"        => session('data')['idAdm'],
            "id_user"       => session('data')['id'],
            "id_supervisao" => ($input['selectSupervisor']) ? $input['selectSupervisor']       : NULL,
        ];
        $idUser = $this->modelUsuarios->insert($dataUser);
        if ($idUser === false) {
            return $this->fail($this->modelUsuarios->errors());
        }
        $dataPerfil = [
            "id_user" => $idUser,
            "diaDizimo" => ($input['dia'])          ?  $input['dia']         : NULL,
            "telFixo" => ($input['tel'])          ? preg_replace('/[^0-9]/', '', $input['tel'])          : NULL,
            "cpf" => ($input['cpf'])          ? preg_replace('/[^0-9]/', '', $input['cpf'])          : NULL,
            "cep" => ($input['cep'])          ? preg_replace('/[^0-9]/', '', $input['cep'])         : NULL,
            "uf" => ($input['uf'])          ? $input['uf']          : NULL,
            "cidade" => ($input['cidade'])          ? $input['cidade']          : NULL,
            "bairro" => ($input['bairro'])          ? $input['bairro']          : NULL,
            "complemento" => ($input['complemento'])          ? $input['complemento']          : NULL,
            "dt_nascimento" => ($input['dtNascimento'])          ? $input['dtNascimento']          : NULL
        ];
    }

    public function edit($id = null)
    {
        //
    }
     * Add or update a model resource, from "posted" properties
     *
     * @return ResponseInterface
     */
    /**public function update($id = null)
    {
        //$usuario = array();
        //Garantir que os dados que foram enviados são os mesmos ou alterados no banco de dados

        try {
            $input = $this->request->getJSON();
            $usuario = [
                "nome"          => $input->nome          ?? null,
                "sobrenome"     => $input->sobrenome     ?? null,
                "email"         => $input->email         ?? null,
                "telefone"      => $input->telefone      ?? null,
                "celular"       => $input->celular       ?? null,
                "gerente"       => $input->gerente       ?? null,
                "nivel"         => $input->nivel         ?? null,
                "id_adm"        => $input->id_adm        ?? null,
                "id_user"       => $input->id_user       ?? null,
                "id_supervisao" => $input->id_supervisao ?? null,
                "id_igreja"     => $input->id_igreja     ?? null
            ];

            // Altera senha
            if ($input->password) {
                $usuario["password"] = $input->password;
            }

            // Chama o método personalizado que desativa a validação temporariamente
            $this->modelUsuarios->disableEmailValidation();

            $idUser = $this->modelUsuarios->updateUsuario($id, $usuario);

            // Restaura a validação do e-mail
            $this->modelUsuarios->enableEmailValidation();

            // Se houver erros
            if ($idUser === false) {
                return $this->fail($this->modelUsuarios->errors());
            }

            $liUpload = new UploadsLibraries;
            $upload = $this->request->getFile('foto');

            $perfil = [
                "cpf" => $input->cpf ?? null,
                "cep" => $input->cep ?? null,
                "uf" => $input->uf ?? null,
                "cidade" => $input->cidade ?? null,
                "bairro" => $input->bairro ?? null,
                "complemento" => $input->complemento ?? null,
                "dt_nascimento" => $input->dt_nascimento ?? null,
                "facebook" => $input->facebook ?? null,
                "instagram" => $input->instagram ?? null,
                "id_google" => $input->google ?? null
            ];
            
            /*if ($upload) {
                $name = $liUpload->perfil($upload);
                $perfil['foto'] = $name;
            }

            $this->modelPerfil->update(['id_user' => $id], $perfil);
            return $this->respond(['msg' => lang("Sucesso.alterado")]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }*/

    /*
     * public function delete($id = null)
    {
        //
        if ($id > 1) {
            $this->modelPerfil->where('id_user', $id)->delete();
            $this->modelUsuarios->delete($id);
            return $this->respondDeleted(['msg' => lang("Sucesso.excluir")]);
        } else {
            return $this->fail(lang("Errors.excluir"));
        }
    }*/

    
    /**Login com senha */
    public function authenticate()
    {
        try {
            $input = $this->request->getPost();
            return $this->modelUsuarios->login($input['email'], $input['senha']);
        } catch (\Exception $e) {
            session()->setFlashdata("error", $e->getMessage());
            return redirect()->to(site_url());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url());
    }

    function userData(){
        $id = session('data')['id'];
        $data = $this->modelUsuarios->select('email, tipo, nivel')->find($id);
        return $this->respond(['perfil' => $this->modelUsuarios->userData(), 'user' => $data ]);
    }
}
