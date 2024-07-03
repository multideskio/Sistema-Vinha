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

    }

    public function supervisor()
    {
        //$cache = \Config\Services::cache();
        //$cacheKey = "select_supervisores";
        //if ($cacheData = $cache->get($cacheKey)) {
        //    return $this->respond($cacheData);
        //} else {
        $data = $this->modelSupervisores->findAll();
        //$cache->save($cacheKey, $data, 3600);
        return $this->respond($data);
        //}
    }
}
