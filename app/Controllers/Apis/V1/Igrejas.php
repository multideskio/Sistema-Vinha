<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\NotificationLibrary;
use App\Libraries\UploadsLibraries;
use App\Models\IgrejasModel;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use Exception;
use ReflectionException;
use RuntimeException;

class Igrejas extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */
    protected IgrejasModel $modelIgrejas;

    public function __construct()
    {
        $this->modelIgrejas = new IgrejasModel();
    }

    public function index()
    {
        //
        if ($this->request->getGet("search") === "false") {
            $data = $this->modelIgrejas->listSearch();
        } else {
            $data = $this->modelIgrejas->listSearch($this->request->getGet());
        }

        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     * @param null $id
     * @return ResponseInterface
     */
    public function show($id = null): ResponseInterface
    {
        //

        $this->modelIgrejas
            ->select('igrejas.*')
            ->select('usuarios.email, usuarios.whatsapp AS sendWhatsapp, usuarios.id AS id_login')
            ->join('usuarios', 'usuarios.id_perfil = igrejas.id')
            ->join('supervisores', 'supervisores.id = igrejas.id_supervisor')
            ->where('usuarios.tipo', 'igreja');

        if (session('data')['tipo'] === 'igreja') {
            $search = $this->modelIgrejas->find(session('data')['id_perfil']);
        } elseif (session('data')['tipo'] === 'pastor') {
            $search = $this->modelIgrejas->find(session('data')['id_perfil']);
        } else {
            $search = $this->modelIgrejas->find($id);
        }

        if ($search) {
            $data = [
                "id"                  => $search['id'],
                "razaoSocial"         => $search['razao_social'],
                "nomeFantazia"        => $search['fantasia'],
                "id_login"            => $search['id_login'],
                "idSupervisor"        => $search['id_supervisor'],
                "nomeTesoureiro"      => $search['nome_tesoureiro'],
                "sobrenomeTesoureiro" => $search['sobrenome_tesoureiro'],
                "cpfTesoureiro"       => $search['cpf_tesoureiro'],
                "cnpj"                => $search['cnpj'],
                "fundacao"            => $search['fundacao'],
                "foto"                => $search['foto'],
                "uf"                  => $search['uf'],
                "numero"              => $search['numero'],
                "rua"                 => $search["rua"],
                "pais"                => $search["pais"],
                "cidade"              => $search['cidade'],
                "cep"                 => $search['cep'],
                "complemento"         => $search['complemento'],
                "bairro"              => $search['bairro'],
                "data_dizimo"         => $search['data_dizimo'],
                "telefone"            => $search['telefone'],
                "celular"             => $search['celular'],
                "facebook"            => $search['facebook'],
                "instagram"           => $search['instagram'],
                "created_at"          => $search['created_at'],
                "website"             => $search['website'],
                "email"               => $search['email'],
                "sendWhatsapp"        => $search['sendWhatsapp'],
            ];

            return $this->respond($data);
        }

        return $this->failNotFound();
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return ResponseInterface|null
     */
    public function new(): ?ResponseInterface
    {
        //
        return null;
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return ResponseInterface
     */
    public function create(): ResponseInterface
    {
        //
        $db        = Database::connect();  // Conecta ao banco de dados
        $modelUser = new UsuariosModel();

        // Inicia a transação
        $db->transBegin();

        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getVar();

            if ($modelUser->where('email', $input['email'])->countAllResults()) {
                throw new RuntimeException("Esse email já está cadastrado no sistema.", 1);
            }

            $celular = $input['cel'];
            $nome    = $input['nome_tesoureiro'];
            $email   = $input['email'];

            $data = [
                "id_adm"               => session('data')['idAdm'],
                "id_user"              => session('data')['id'],
                "id_supervisor"        => $input['selectSupervisor'],
                "nome_tesoureiro"      => $nome,
                "sobrenome_tesoureiro" => $input['sobrenome_tesoureiro'],
                "cpf_tesoureiro"       => $input['cpf'],
                "fundacao"             => $input['fundacao'],
                "razao_social"         => $input['razaosocial'],
                "fantasia"             => $input['fantasia'],
                "cnpj"                 => $input['cnpj'],
                "uf"                   => $input['uf'],
                "cidade"               => $input['cidade'],
                "cep"                  => $input['cep'],
                "complemento"          => $input['complemento'],
                "bairro"               => $input['bairro'],
                "data_dizimo"          => $input['dia'],
                "telefone"             => $input['tel'],
                "celular"              => $celular,
            ];

            $id = $this->modelIgrejas->insert($data);

            if ($id === false) {
                // Se a inserção falhar, reverte a transação e retorna o erro
                $db->transRollback();

                return $this->fail($this->modelIgrejas->errors());
            }

            $dataUser = [
                'id_perfil' => $id,
                'email'     => $input['email'],
                'password'  => $input['password'] ?? '123456',
                'id_adm'    => session('data')['id'],
            ];

            $idUser = $modelUser->cadUser('igreja', $dataUser);

            if ($idUser === false) {
                // Se a inserção falhar, reverte a transação e retorna o erro
                $db->transRollback();

                return $this->fail($modelUser->errors());
            }

            // Confirma a transação
            $db->transCommit();
            // Notificações
            $notification = new NotificationLibrary();

            //Verifica
            if ($celular) {
                $notification->sendWelcomeMessage($nome, $email, $celular);
            }
            $notification->sendVerificationEmail($email, $nome);

            return $this->respondCreated(['msg' => 'Cadastro realizado com sucesso', 'id' => $id]);
        } catch (Exception $e) {
            $this->modelIgrejas->transRollback(); // Rollback em caso de exceção

            return $this->fail($e->getMessage());
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @param null $id
     * @return ResponseInterface|null
     */
    public function edit($id = null): ?ResponseInterface
    {
        //
        return null;
    }

    public function links($idIn = null): ResponseInterface
    {
        if (session('data')['tipo'] === 'igreja') {
            $id = session('data')['id_perfil'];
        } elseif (session('data')['tipo'] === 'pastor') {
            $id = session('data')['id_perfil'];
        } else {
            $id = $idIn;
        }

        $input = $this->request->getRawInput();
        $data  = [
            'facebook'  => $input['linkFacebook'],
            'instagram' => $input['linkInstagram'],
            'website'   => $input['linkWebsite'],
        ];
        try {
            $this->modelIgrejas->update($id, $data);
        } catch (ReflectionException $e) {
            log_message('error', $e->getMessage());

            return $this->fail($this->modelIgrejas->errors());
        }

        return $this->respondUpdated(['msg' => "Cadastro alterado com sucesso", 'id' => $id]);
    }

    public function foto($idIn = null): ResponseInterface
    {
        $file = $this->request->getFile('foto'); // O nome do campo deve corresponder ao do frontend

        if (!$file || !$file->isValid()) {
            return $this->fail('Nenhum arquivo foi enviado ou o arquivo é inválido.');
        }

        try {

            if(session('data')['tipo'] === 'igreja') {
                $id = session('data')['id_perfil'];
            } elseif (session('data')['tipo'] === 'pastor') {
                $id = session('data')['id_perfil'];
            } else {
                $id = $idIn;
            }

            $uploadLibraries = new UploadsLibraries();
            $path            = "igrejas/$id/" . $file->getRandomName();
            $uploadPath      = $uploadLibraries->upload($file, $path);

            $data = [
                'foto' => $uploadPath,
            ];

            if (!$this->modelIgrejas->update($id, $data)) {
                return $this->fail($this->modelIgrejas->errors());
            }

            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $uploadPath]);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @param null $idIn
     * @return ResponseInterface
     */
    public function update($idIn = null): ResponseInterface
    {
        try {
            // Obtém os dados do FilePond do corpo da solicitação
            $input = $this->request->getRawInput();

            // Define o ID com base no tipo de perfil da sessão
            if (session('data')['tipo'] === 'igreja' || session('data')['tipo'] === 'pastor') {
                $id = session('data')['id_perfil'];
            } else {
                $id = $idIn;
            }

            // Inicializa o array $data
            $data = [
                "nome_tesoureiro"      => $input['nome'],
                "sobrenome_tesoureiro" => $input['sobrenome'],
                "cpf_tesoureiro"       => $input['cpf'],
                "fundacao"             => $input['fundacao'],
                "razao_social"         => $input['razaosocial'],
                "fantasia"             => $input['fantasia'],
                "cnpj"                 => $input['cnpj'],
                "uf"                   => $input['uf'],
                "cidade"               => $input['cidade'],
                "cep"                  => $input['cep'],
                "numero"               => $input["numero"],
                "rua"                  => $input['rua'],
                "pais"                 => $input['pais'],
                "complemento"          => $input['complemento'],
                "bairro"               => $input['bairro'],
                "data_dizimo"          => $input['dia'],
                "telefone"             => $input['tel'],
                "celular"              => $input['cel'],
            ];

            // Adiciona id_supervisor ao array $data se existir no input
            if (array_key_exists('selectSupervisor', $input)) {
                $data['id_supervisor'] = $input['selectSupervisor'];
            }

            // Atualiza os dados no banco de dados
            $status = $this->modelIgrejas->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelIgrejas->errors());
            }

            return $this->respondCreated(['msg' => 'Alteração realizada com sucesso', 'id' => $id]);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @param null $id
     * @return ResponseInterface|null
     */
    public function delete($id = null): ?ResponseInterface
    {
        //
        return null;
    }
}