<?php

namespace App\Controllers\Apis\V1\Publica ;

use App\Controllers\Apis\V1\BaseController;
use App\Libraries\EmailsLibraries;
use App\Libraries\NotificationLibrary;
use App\Models\ConfigMensagensModel;
use App\Models\IgrejasModel;
use App\Models\PastoresModel;
use App\Models\SupervisoresModel;
use App\Models\UsuariosModel;
use CodeIgniter\Security\Exceptions\SecurityException;

class Open extends BaseController
{
    protected UsuariosModel        $modelUser;
    protected PastoresModel        $modelPastor;
    protected IgrejasModel         $modelIgreja;
    protected SupervisoresModel    $modelSupervisores;
    protected ConfigMensagensModel $modelMessages;
    protected NotificationLibrary  $notifications;
    protected EmailsLibraries      $emailsLibrary;

    public function __construct()
    {
        parent::__construct();

        $this->modelUser         = new UsuariosModel();
        $this->modelPastor       = new PastoresModel();
        $this->modelIgreja       = new IgrejasModel();
        $this->modelSupervisores = new SupervisoresModel();
        $this->modelMessages     = new ConfigMensagensModel();
        $this->notifications     = new NotificationLibrary();
        $this->emailsLibrary     = new EmailsLibraries();
    }

    //Cadastro de pastor
    public function pastor()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized();
        }

        try {
            $this->modelPastor->transStart();
            $header = $this->request->headers();
            $input  = $this->request->getPost();

            //VERIFICA ORIGEM
            if ($header['Origin']->getValue() != rtrim(site_url(), '/')) {
                throw new SecurityException('Origem de solicitação não permitida.');
            }

            //VERIFICA SE JA EXISTE UM EMAIL NO BANCO DE DADOS
            if ($this->modelUser->where('email', $input['email'])->countAllResults()) {
                throw new SecurityException("O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha.", 1);
            }
            $nome    = $input['nome'];
            $celular = $input['full_phone'];
            $email   = $input['email'];
            //ARRAY CADASTRO DO PASTOR
            $data = [
                "id_adm" => 1,
                //"id_user"      => session('data')['id'],
                'id_supervisor' => $input['selectSupervisor'],
                'nome'          => $nome,
                'sobrenome'     => $input['sobrenome'],
                'cpf'           => $input['cpf'],
                'uf'            => $input['uf'],
                'cidade'        => $input['cidade'],
                'cep'           => $input['cep'],
                'complemento'   => $input['complementoPastor'],
                'nascimento'    => $input['nascimento'],
                'bairro'        => $input['bairro'],
                "pais"          => $input['paisPastor'],
                "numero"        => $input['numeroPastor'],
                "rua"           => $input['ruaPastor'],
                'data_dizimo'   => $input['dia'],
                'celular'       => $celular,
            ];
            //INSERE PASTOR
            $id = $this->modelPastor->insert($data);

            //VERIFICA SE HÁ ERROS
            if ($id === false) {
                throw new SecurityException($this->modelPastor->errors()[]);
            }
            //ARRAY CADASTRO USUARIO PASTOR
            $dataUser = [
                'tipo'      => 'pastor',
                'id_perfil' => $id,
                'id_admin'  => 1,
                'email'     => $email,
                'password'  => $input['password'],
                'nivel'     => '4',
            ];
            $this->modelUser->transStart();
            //INSERE USUÁRIO
            $user = $this->modelUser->insert($dataUser);

            //VERIFICA SE HÁ ERROS
            if ($user === false) {
                throw new SecurityException($this->modelUser->errors()[]);
            }
            $this->modelPastor->transComplete();
            $this->modelUser->transComplete();

            //Verifica
            if ($celular) {
                $this->notifications->sendWelcomeMessage($nome, $email, $celular);
            }

            $this->notifications->sendVerificationEmail($email, $nome);

            //RESPOSTA DE SUCESSO
            return $this->respondCreated(['msg' => "Cadastro realizado com sucesso"]);
        } catch (SecurityException $e) {
            //FAZ O ROLLBACK DOS CADASTROS DE REALIZADOS ANTERIORMENTE
            $this->modelPastor->transRollback();
            $this->modelUser->transRollback();

            //MENSAGEM DE ERRO
            return $this->failUnauthorized($e->getMessage());
        }
    }

    //cadastro de igreja
    public function igreja()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized();
        }

        try {

            $this->modelIgreja->transStart();

            $header = $this->request->headers();
            $input  = $this->request->getPost();

            //VERIFICA ORIGEM
            if ($header['Origin']->getValue() != rtrim(site_url(), '/')) {
                throw new SecurityException('Origem de solicitação não permitida.');
            }

            if ($this->modelUser->where('email', $input['useremailIgreja'])->countAllResults()) {
                throw new SecurityException("O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha.", 1);
            }

            $nome    = $input['nomeTesoureiro'];
            $email   = $input['useremailIgreja'];
            $celular = $input['full_phone'];

            //ARRAY CADASTRO DO PASTOR
            $data = [
                "id_adm" => 1,
                //"id_user"      => session('data')['id'],
                "id_supervisor"        => $input['selectSupervisor'],
                "nome_tesoureiro"      => $nome,
                "sobrenome_tesoureiro" => $input['sobreTesoureiro'],
                "cpf_tesoureiro"       => $input['cpfTesoureiro'],
                "fundacao"             => $input['dataFundacao'],
                "razao_social"         => $input['razaosocial'],
                "fantasia"             => $input['fantasia'],
                "cnpj"                 => $input['cnpj'],
                "uf"                   => $input['uf'],
                "cidade"               => $input['cidade'],
                "pais"                 => $input['paisIgreja'],
                "numero"               => $input['numeroIgreja'],
                "cep"                  => $input['cep'],
                "rua"                  => $input['ruaIgreja'],
                "complemento"          => $input['complementoIgreja'],
                "bairro"               => $input['bairro'],
                "data_dizimo"          => $input['dia'],
                //"telefone" => $input['tel'],
                "celular" => $celular,
            ];

            $id = $this->modelIgreja->insert($data);

            //VERIFICA SE HÁ ERROS
            if ($id === false) {
                throw new SecurityException($this->modelIgreja->errors()[]);
            }

            //ARRAY CADASTRO USUARIO PASTOR
            $dataUser = [
                'tipo'      => 'igreja',
                'id_perfil' => $id,
                'id_admin'  => 1,
                'email'     => $email,
                'password'  => $input['password'],
                'nivel'     => '4',
            ];

            $this->modelUser->transStart();

            //INSERE USUÁRIO
            $user = $this->modelUser->insert($dataUser);

            //VERIFICA SE HÁ ERROS
            if ($user === false) {
                throw new SecurityException($this->modelUser->errors()[]);
            }

            $this->modelUser->transComplete();
            $this->modelIgreja->transComplete();

            //Verifica
            if ($celular) {
                $this->notifications->sendWelcomeMessage($nome, $email, $celular);
            }

            $this->notifications->sendVerificationEmail($email, $nome);

            //RESPOSTA DE SUCESSO
            return $this->respondCreated(['msg' => 'Cadastrado com sucesso']);

        } catch (SecurityException $e) {
            $this->modelIgreja->transRollback();
            $this->modelUser->transRollback();

            return $this->failUnauthorized($e->getMessage());
        }
    }

    public function supervisor()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized();
        }

        $data = $this->modelSupervisores
            ->select('id, nome, sobrenome')
            ->findAll();

        if (count($data)) {
            if(!$this->cache->get('public_supervisores')) {
                $this->cache->save('public_supervisores', $data, getCacheExpirationTimeInSeconds(30));

                return $this->respond($data);
            } else {
                return $this->respond($this->cache->get('public_supervisores'));
            }
        } else {
            return $this->failNotFound();
        }
    }

    public function newpass()
    {

        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized();
        }

        $input = $this->request->getPost();

        //VERIFICA SE EXISTE CADASTRO DO USUÁRIO
        $build = $this->modelUser->where('token', $input['token'])->first();

        if ($build) {
            $data = [
                'password' => $input['senha'],
            ];
            $update = $this->modelUser->update($build['id'], $data);

            return $this->respondUpdated($update);
        }

        return $this->failNotFound();
    }

    public function recover()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized();
        }

        //
        $input = $this->request->getPost();
        //VERIFICA SE EXISTE CADASTRO DO USUÁRIO
        $build = $this->modelUser->select('token')->where('email', $input['email'])->first();

        if ($build) {
            //Envio de e-mail
            $sendEmail = [
                'token' => $build['token'],
            ];

            $message = view('emails/recupera', $sendEmail);
            $this->emailsLibrary->envioEmail($input['email'], 'Recuperação de conta', $message);

            return $this->respond($build);
        }

        return $this->failNotFound();
    }

    public function searchEmail()
    {
        $input = $this->request->getGET();

        if(empty($input['email'])) {
            return $this->fail(['msg' => 'Parametro faltando! ' . $input]);
        }

        $build = $this->modelUser->where('email', esc($input['email']))->countAllResults();

        if($build) {
            return $this->respond(['is' => 'not']);
        } else {
            return $this->respond(['is' => 'ok']);
        }
    }
}