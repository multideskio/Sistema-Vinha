<?php

namespace App\Controllers\Apis\V1;

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

    public function __construct()
    {
        $this->modelUser         = new UsuariosModel();
        $this->modelPastor       = new PastoresModel();
        $this->modelIgreja       = new IgrejasModel();
        $this->modelSupervisores = new SupervisoresModel();
        $this->modelMessages     = new ConfigMensagensModel();
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
        $request = service('request');
        try {

            $this->modelPastor->transStart();

            $header = $request->headers();
            $input = $request->getPost();

            if ($header['Origin']->getValue() != rtrim(site_url(), '/')) {
                throw new SecurityException('Origem de solicitação não permitida.');
            }

            if ($this->modelUser->where('email', $input['email'])->countAllResults()) {
                throw new SecurityException("O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha.", 1);
            };

            $data = [
                "id_adm"       => 1,
                //"id_user"      => session('data')['id'],
                'id_supervisor' => $input['selectSupervisor'],
                'nome' => $input['nome'],
                'sobrenome' => $input['sobrenome'],
                'cpf' => $input['cpf'],
                'uf' => $input['uf'],
                'cidade' => $input['cidade'],
                'cep' => $input['cep'],
                'complemento' => $input['complemento'],
                'nascimento' => $input['nascimento'],
                'bairro' => $input['bairro'],
                'data_dizimo' => $input['dia'],
                'celular' => $input['whatsapp']
            ];

            $id = $this->modelPastor->insert($data);

            if ($id  === false) {
                throw new SecurityException($this->modelPastor->errors()[]);
            }

            $dataUser = [
                'tipo'        => 'pastor',
                'id_perfil'   => $id,
                'email'       => $input['email'],
                'password'    => $input['password'],
                'nivel'       => '4'
            ];

            $user = $this->modelUser->insert($dataUser);

            if ($user === false) {
                throw new SecurityException($this->modelUser->errors()[]);
            }

            $this->modelPastor->transComplete();

            $whatsapp = new WhatsappLibraries();
            $messages = $this->modelMessages->where('tipo', 'novo_usuario')->first();

            if ($messages['status']) {
                // Valores que irão substituir as tags
                $valores = [
                    '{NOME}'  => $input['nome'],
                    '{EMAIL}' => $input['email'],
                    '{TEL}'   => $input['whatsapp']
                ];

                // Substituir as tags com os valores
                $novaString = strtr($messages['mensagem'], $valores);
                $msg['message'] = $novaString;

                $whatsapp->sendMessageText($msg, $input['whatsapp']);
            }

            $newEmail = new EmailsLibraries;


            $rowUser = $this->modelUser->find($user);

            $sendEmail = [
                'nome' => $input['nome'],
                'token' => $rowUser['token']
            ];

            $message = view('emails/confirma-email', $sendEmail);

            $newEmail->envioEmail($rowUser['email'], 'Confirme seu e-mail', $message);

            return $this->respondCreated(['msg' => lang("Sucesso.cadastrado")]);
        } catch (SecurityException $e) {
            $this->modelPastor->transRollback();
            return $this->failUnauthorized($e->getMessage());
        }
    }
    public function igreja()
    {
        $request = service('request');
        try {

            $this->modelPastor->transStart();

            $header = $request->headers();
            $input  = $request->getPost();

            if ($header['Origin']->getValue() != rtrim(site_url(), '/')) {
                throw new SecurityException('Origem de solicitação não permitida.');
            }

            if ($this->modelUser->where('email', $input['email'])->countAllResults()) {
                throw new SecurityException("O endereço de e-mail informado já está cadastrado no sistema, clique em recuperar conta para redefinir sua senha.", 1);
            };

            

        } catch (SecurityException $e) {
            $this->modelPastor->transRollback();
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
        $request = service('request');;
        if (!$request->isAJAX()) {
            return $this->failUnauthorized();
        }
        $data = $this->modelSupervisores
            ->select('id, nome, sobrenome')
            ->findAll();

        if (count($data)) {
            return $this->respond($data);
        } else {
            return $this->failNotFound();
        }
    }
}
