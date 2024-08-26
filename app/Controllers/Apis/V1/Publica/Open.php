<?php

namespace App\Controllers\Apis\V1\Publica ;

use App\Libraries\EmailsLibraries;
use App\Libraries\WhatsappLibraries;
use App\Models\ConfigMensagensModel;
use App\Models\IgrejasModel;
use App\Models\PastoresModel;
use App\Models\SupervisoresModel;
use App\Models\UsuariosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Security\Exceptions\SecurityException;
use Exception;

class Open extends ResourceController
{

    use ResponseTrait;

    protected $modelUser;
    protected $modelPastor;
    protected $modelIgreja;
    protected $modelSupervisores;
    protected $modelMessages;
    protected $request;
    protected $cache;

    public function __construct()
    {
        $this->modelUser         = new UsuariosModel();
        $this->modelPastor       = new PastoresModel();
        $this->modelIgreja       = new IgrejasModel();
        $this->modelSupervisores = new SupervisoresModel();
        $this->modelMessages     = new ConfigMensagensModel();
        $this->request           = service('request');
        $this->cache             = \Config\Services::cache();

        helper('auxiliar');
    }
    public function index()
    {
    }

    /**
     * The function `pastor` handles the registration process for a new pastor, including data
     * validation, database insertion, sending WhatsApp messages, and confirmation emails.
     * 
     * @return The function `pastor()` is returning a response with a success message
     * "Sucesso.cadastrado" if all the operations within the try block are successful. If an exception
     * of type `SecurityException` is caught during the process, it will rollback the transaction and
     * return a failure response with the error message from the exception.
     */
    public function pastor()
    {
        try {

            $this->modelPastor->transStart();
            $header = $this->request->headers();
            $input = $this->request->getPost();

            //VERIFICA ORIGEM
            if ($header['Origin']->getValue() != rtrim(site_url(), '/')) {
                throw new SecurityException('Origem de solicitação não permitida.');
            }

            //VERIFICA SE JA EXISTE UM EMAIL NO BANCO DE DADOS
            if ($this->modelUser->where('email', $input['email'])->countAllResults()) {
                throw new SecurityException("O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha.", 1);
            };

            $nome    = $input['nome'];
            $celular = $input['whatsapp'];
            $email   = $input['email'];

            //ARRAY CADASTRO DO PASTOR
            $data = [
                "id_adm"       => 1,
                //"id_user"      => session('data')['id'],
                'id_supervisor' => $input['selectSupervisor'],
                'nome' => $nome,
                'sobrenome' => $input['sobrenome'],
                'cpf' => $input['cpf'],
                'uf' => $input['uf'],
                'cidade' => $input['cidade'],
                'cep' => $input['cep'],
                'complemento' => $input['complemento'],
                'nascimento' => $input['nascimento'],
                'bairro' => $input['bairro'],
                'data_dizimo' => $input['dia'],
                'celular' => $celular
            ];

            //INSERE PASTOR
            $id = $this->modelPastor->insert($data);

            //VERIFICA SE HÁ ERROS
            if ($id  === false) {
                throw new SecurityException($this->modelPastor->errors()[]);
            }

            //ARRAY CADASTRO USUARIO PASTOR
            $dataUser = [
                'tipo'        => 'pastor',
                'id_perfil'   => $id,
                'id_admin'    => 1,
                'email'       => $email,
                'password'    => $input['password'],
                'nivel'       => '4'
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
            
            // Notificações
            $notification = new \App\Libraries\NotificationLibrary();
            
            //Verifica
            if ($celular) {
                $notification->sendWelcomeMessage($nome, $email, $celular); 
            }

            $notification->sendVerificationEmail($email, $nome);

            //RESPOSTA DE SUCESSO
            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado")]);
        } catch (SecurityException $e) {
            //FAZ O ROLLBACK DOS CADASTROS DE REALIZADOS ANTERIORMENTE
            $this->modelPastor->transRollback();
            $this->modelUser->transRollback();
            //MENSAGEM DE ERRO
            return $this->failUnauthorized($e->getMessage());
        }
    }
    public function igreja()
    {
        try {
            
            $this->modelIgreja->transStart();
            
            $header = $this->request->headers();
            $input  = $this->request->getPost();

            if ($header['Origin']->getValue() != rtrim(site_url(), '/')) {
                throw new SecurityException('Origem de solicitação não permitida.');
            }

            if ($this->modelUser->where('email', $input['email'])->countAllResults()) {
                throw new SecurityException("O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha.", 1);
            };

            $nome    = $input['nomeTesoureiro'];
            $email   = $input['email'];
            $celular = $input['whatsapp'];

            //ARRAY CADASTRO DO PASTOR
            $data = [
                "id_adm"       => 1,
                //"id_user"      => session('data')['id'],
                "id_supervisor" => $input['selectSupervisor'],
                "nome_tesoureiro" => $nome,
                "sobrenome_tesoureiro" => $input['sobreTesoureiro'],
                "cpf_tesoureiro" => $input['cpfTesoureiro'],
                "fundacao" => $input['dataFundacao'],
                "razao_social" => $input['razaosocial'],
                "fantasia" => $input['fantasia'],
                "cnpj" => $input['cnpj'],
                "uf" => $input['uf'],
                "cidade" => $input['cidade'],
                "cep" => $input['cep'],
                "complemento" => $input['complemento'],
                "bairro" => $input['bairro'],
                "data_dizimo" => $input['dia'],
                //"telefone" => $input['tel'],
                "celular" => $celular
            ];

            $id = $this->modelIgreja->insert($data);

            //VERIFICA SE HÁ ERROS
            if ($id  === false) {
                throw new SecurityException($this->modelIgreja->errors()[]);
            }

            //ARRAY CADASTRO USUARIO PASTOR
            $dataUser = [
                'tipo'        => 'igreja',
                'id_perfil'   => $id,
                'id_admin'    => 1,
                'email'       => $email,
                'password'    => $input['password'],
                'nivel'       => '4'
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

            // Notificações
            $notification = new \App\Libraries\NotificationLibrary();
            
            //Verifica
            if ($celular) {
                $notification->sendWelcomeMessage($nome, $email, $celular); 
            }

            $notification->sendVerificationEmail($email, $nome);

            //RESPOSTA DE SUCESSO
            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado")]);

        } catch (SecurityException $e) {
            $this->modelIgreja->transRollback();
            $this->modelUser->transRollback();
            return $this->failUnauthorized($e->getMessage());
        }
    }

    /**
     * The supervisor function checks if the request is AJAX, retrieves supervisor data, and returns a
     * response accordingly.
     * 
     * @return If the request is not an AJAX request, the function will return a "failUnauthorized"
     * response. If there is data retrieved from the model, it will return a "respond" response with
     * the data. If there is no data found, it will return a "failNotFound" response.
     */
    public function supervisor()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized();
        }

        $data = $this->modelSupervisores
            ->select('id, nome, sobrenome')
            ->findAll();

        if (count($data)) {
            if(!$this->cache->get('public_supervisores')){
                $this->cache->save('public_supervisores', $data, getCacheExpirationTimeInSeconds(30));
                return $this->respond($data);
            }else{
                return $this->respond($this->cache->get('public_supervisores'));
            }
        } else {
            return $this->failNotFound();
        }
    }

    public function newpass(){
        
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized();
        }

        $input   = $this->request->getPost();
        
        //VERIFICA SE EXISTE CADASTRO DO USUÁRIO
        $build   = $this->modelUser->where('token', $input['token'])->first();

        if ($build) {
            $data = [
                'password' => $input['senha']
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
        $input   = $this->request->getPost();
        //VERIFICA SE EXISTE CADASTRO DO USUÁRIO
        $build   = $this->modelUser->select('token')->where('email', $input['email'])->first();
        
        if ($build) {
            //Envio de e-mail
            $sendEmail = [
                'token' => $build['token']
            ];
            $email   = new EmailsLibraries;
            $message = view('emails/recupera', $sendEmail);
            $email->envioEmail($input['email'], 'Recuperação de conta', $message);
            
            return $this->respond($build);
        }

        return $this->failNotFound();
    }

    protected function enviaWhatsApp(){

    }
}
