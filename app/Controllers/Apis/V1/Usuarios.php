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
        //$this->modelPerfil   = new \App\Models\PerfisModel;
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

    /**
     * Return the properties of a resource object
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
        $list = $this->modelUsuarios
            ->select('usuarios.*, perfis.*')
            ->join('perfis', 'usuarios.id = perfis.id_user', 'left')
            ->find($id);

        //$list = $this->modelUsuarios->find($id);
        return $this->respond($list);
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

        $input = $this->request->getVar();
        $dataUser = [
            "nome"          => ($input['nome'])          ? $input['nome']          : NULL,
            "sobrenome"     => ($input['sobrenome'])     ? $input['sobrenome']     : NULL,
            "email"         => ($input['email'])         ? $input['email']         : NULL,
            "password"      => (isset($input['senha']))  ? $input['senha']      : "mudar123",
            "telefone"      => ($input['cel'])           ? preg_replace('/[^0-9]/', '', $input['cel'])      : NULL,
            //"gerente"       => ($input['selectSupervisor']) ? $input['selectSupervisor']       : NULL,
            "nivel"         => ($input['grupoPermissao']  == 1 || $input['grupoPermissao']  == 2)   ? $input['grupoPermissao']         : 2,
            "id_adm"        => session('data')['idAdm'],
            "id_user"       => session('data')['id'],
            "id_supervisao" => ($input['selectSupervisor']) ? $input['selectSupervisor']       : NULL,
            //"id_igreja"     => ($input['id_igreja'])     ? $input['id_igreja']     : NULL
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

        /*$idPerfil = $this->modelPerfil->updateInsert($idUser, $dataPerfil);
        $upload = $this->LibrariesUpload->filePond('usuarios', $idUser, $input);
        if (file_exists($upload['file'])) {
            $update = [
                'foto' => $upload['newName']
            ];
            $status = $this->modelPerfil->update($idPerfil, $update);
            if ($status === false) {
                return $this->fail($this->modelPerfil->errors());
            }
        } else {
            // Ocorreu um erro ao salvar a imagem
            throw new Exception('Erro ao salvar a imagem.');
        }*/
        /*return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);*/
    }

    public function create0()
    {
        //
        try {
            /**RECUPERA DADOS DO JSON */
            $input = $this->request->getJSON();
            $data = [
                "nome"          => ($input->nome)          ? $input->nome          : NULL,
                "sobrenome"     => ($input->sobrenome)     ? $input->sobrenome     : NULL,
                "email"         => ($input->email)         ? $input->email         : NULL,
                "password"      => ($input->password)      ? $input->password      : "mudar123",
                "telefone"      => ($input->telefone)      ? $input->telefone      : NULL,
                "gerente"       => ($input->gerente)       ? $input->gerente       : NULL,
                "nivel"         => ($input->nivel)         ? $input->nivel         : NULL,
                "id_adm"        => ($input->id_adm)        ? $input->id_adm        : NULL,
                "id_user"       => ($input->id_user)       ? $input->id_user       : NULL,
                "id_supervisao" => ($input->id_supervisao) ? $input->id_supervisao : NULL,
                "id_igreja"     => ($input->id_igreja)     ? $input->id_igreja     : NULL
            ];

            /**Insertre usuário */
            $id = $this->modelUsuarios->insert($data);

            /**Se houver erros */
            if ($id === false) {
                return $this->fail($this->modelUsuarios->errors());
            }

            /**Grava id do perfil */
            $this->modelPerfil->insert([
                'id_user' => $id
            ]);

            /**Retorna mensagem de sucesso */
            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado"), 'id' => $id]);
        } catch (\Exception $e) {
            /**Retorna mensagem de erro */
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
            if ($upload) {
                $name = $liUpload->perfil($upload);
                $perfil['foto'] = $name;
            }
            $this->modelPerfil->update(['id_user' => $id], $perfil);
            return $this->respond(['msg' => lang("Sucesso.alterado")]);
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
        if ($id > 1) {
            $this->modelPerfil->where('id_user', $id)->delete();
            $this->modelUsuarios->delete($id);
            return $this->respondDeleted(['msg' => lang("Sucesso.excluir")]);
        } else {
            return $this->fail(lang("Errors.excluir"));
        }
    }


    /**LOGINS*/
    /**Login com o google*/
    public function google()
    {
        $code = $this->request->getVar("code");
        //Se não tem o code na url, retorna o pedido de autorização para acessar pelo google
        try {
            //Busca config
            $config = new ConfigGoogle();
            //Intancia criação de sessão no google
            $client = $config->createGoogleClient();
            //define url de retorno, a mesma configurada no google
            $client->setRedirectUri(site_url("api/v1/google"));
            if (!$code) {
                // Obter a URL de autorização
                $authUrl = $client->createAuthUrl();
                // Redirecionar o usuário para a página de autenticação do Google
                return redirect()->to($authUrl);
            } else {
                #  Quando retorna o code na url
                // Troque o código de autorização por tokens de acesso
                $accessToken = $client->fetchAccessTokenWithAuthCode($code);
                if (!isset($accessToken["error"])) {
                    // Os tokens de acesso foram obtidos com sucesso
                    // Configure o cliente com os tokens de acesso
                    $client->setAccessToken($accessToken);
                    // Verifique se os tokens ainda são válidos
                    if ($client->isAccessTokenExpired()) {
                        // Se os tokens expiraram, você pode tentar renová-los
                        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    }
                    // Crie um serviço para a API do Google Oauth2
                    //$oauth2Service = new \Google\Service\Oauth2($client);
                    // Obtenha as informações do perfil do usuário
                    //$userInfo = $oauth2Service->userinfo->get();
                    /*$data = [
                        "nome"  => $userInfo->name,
                        "email" => $userInfo->email,
                        "image" => $userInfo->picture,
                        "id"    => $userInfo->id
                    ];*/

                    //return $this->modelUsuarios->google($data);
                } else {
                    // Houve um erro ao obter os tokens de acesso
                    // Trate o erro conforme necessário
                    throw new ErrorException(lang("Errors.erroTokenGoogle", ["erro" => $accessToken["error"]]));
                }
            }
        } catch (\Exception $e) {
            session()->setFlashdata("error", $e->getMessage());
            return redirect()->to(site_url());
            //return $this->failUnauthorized($e->getMessage());
        }
    }

    /**Login com senha */
    public function authenticate()
    {
        /* The above PHP code snippet is attempting to handle a login request. It first retrieves the
        POST data from the request, specifically the 'email' and 'senha' (password) fields. It then
        calls a method named 'login' on the 'modelUsuarios' object, passing in the email and
        password as arguments. If an exception is thrown during this process, it catches the
        exception, sets a flash message with the error message, and redirects the user back to the
        previous page. */
        try {
            $input = $this->request->getPost();
            return $this->modelUsuarios->login($input['email'], $input['senha']);
        } catch (\Exception $e) {
            session()->setFlashdata("error", $e->getMessage());
            return redirect()->to(site_url());
        }
    }

    /**Sai do sistema*/
    public function logout()
    {
        /* The above code is written in PHP and it is attempting to destroy the current session using
        `session()->destroy()` and then redirecting the user to the site's homepage using
        `redirect()->to(site_url())`. */
        session()->destroy();
        return redirect()->to(site_url());
    }

    function userData(){
        $id = session('data')['id'];
        $data = $this->modelUsuarios->select('email, tipo, nivel')->find($id);
        
        return $this->respond(['perfil' => $this->modelUsuarios->userData(), 'user' => $data ]);
    }
}
