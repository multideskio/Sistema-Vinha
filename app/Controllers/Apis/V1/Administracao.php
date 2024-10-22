<?php

namespace App\Controllers\Apis\V1;

use App\Libraries\UploadsLibraries;
use App\Libraries\WhatsappLibraries;
use App\Models\AdminModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Exception;
use RuntimeException;

class Administracao extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return ResponseInterface
     */

    protected AdminModel $modelAdmin;
    protected $request;

    public function __construct()
    {
        $this->modelAdmin = new AdminModel();
        $this->request = Services::request();
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
     * @param null $id
     * @return ResponseInterface
     */
    public function show($id = null): ResponseInterface
    {
        //
        $data = $this->modelAdmin->searchCacheData($id);

        return $this->respond($data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return ResponseInterface
     */
    public function new(): ResponseInterface
    {
        //
        return $this->failNotFound();
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return ResponseInterface
     */
    public function create(): ResponseInterface
    {
        //
        return $this->failNotFound();
    }

    /**
     * Return the editable properties of a resource object
     *
     * @param null $id
     * @return ResponseInterface
     */
    public function edit($id = null): ResponseInterface
    {
        //
        return $this->failNotFound();
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @param null $id
     * @return ResponseInterface
     */
    public function foto($id = null): ResponseInterface
    {
        $file = null;
        if (!empty($this->request)) {
            $file    = $this->request->getFile('foto');
        } // O nome do campo deve corresponder ao do frontend

        if (!$file || !$file->isValid()) {
            return $this->fail('Nenhum arquivo foi enviado ou o arquivo é inválido.');
        }

        try {
            $uploadLibraries = new UploadsLibraries();
            $path            = "admin_geral/$id/" . $file->getRandomName();
            $uploadPath      = $uploadLibraries->upload($file, $path);

            $data = [
                'logo' => $uploadPath,
            ];

            if (!$this->modelAdmin->update($id, $data)) {
                return $this->fail($this->modelAdmin->errors());
            }

            return $this->respond(['message' => 'Imagem enviada com sucesso!', 'file' => $uploadPath]);
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function links($id = null): ResponseInterface
    {
        $input = $this->request->getRawInput();

        $data = [
            'facebook'  => $input['linkFacebook'],
            'instagram' => $input['linkInstagram'],
            'site'      => $input['linkWebsite'],
        ];

        $status = $this->modelAdmin->update($id, $data);

        if ($status === false) {
            return $this->fail($this->modelAdmin->errors());
        }

        return $this->respondUpdated(['msg' => "Alteração realizada", 'id' => $id]);
    }

    public function updateInfo($id = null): ResponseInterface
    {
        try {
            if (!$id) {
                throw new RuntimeException('ID não informado');
            }
            $input   = $this->request->getRawInput();
            $data    = [
                'cnpj'        => $input['cnpj'],
                'empresa'     => $input['empresa'],
                'email'       => $input['email'],
                'cep'         => $input['cep'],
                'uf'          => $input['uf'],
                'cidade'      => $input['cidade'],
                'bairro'      => $input['bairro'],
                'complemento' => $input['complemento'],
                'telefone'    => $input['fixo'],
                'celular'     => $input['celular'],
            ];
            $this->modelAdmin->update($id, $data);

            return $this->respond(['msg' => 'Atualizado com sucesso!']);
        } catch (Exception $e) {
            return $this->fail(['error' => $e->getMessage()]);
        }
    }

    public function updateSmtp($id = null): ResponseInterface
    {
        try {

            if (!$id) {
                throw new RuntimeException('ID não informado');
            }

            $input   = $this->request->getRawInput();

            $data = [
                'email_remetente' => $input['emailRemetente'],
                'nome_remetente'  => $input['nomeRemetente'],
                'smtp_host'       => $input['smtpHOST'],
                'smtp_user'       => $input['smtpLOGIN'],
                'smtp_pass'       => $input['smtpPASS'],
                'smtp_port'       => $input['smtpPORT'],
                'ativar_smtp'     => ($input['ativarSMTP']) ?? 0,
                'smtp_crypt'      => $input['smtpCRYPT']    ?? 'SSL',
            ];

            $status = $this->modelAdmin->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelAdmin->errors());
            }

            return $this->respond(['msg' => $data]);
        } catch (Exception $e) {

            return $this->fail(['error' => $e->getMessage()]);
        }
    }

    public function updateWa($id = null): ResponseInterface
    {
        try {

            if (!$id) {
                throw new RuntimeException('ID não informado');
            }

            $input   = $this->request->getRawInput();

            $data = [
                'url_api'      => $input['urlAPI'],
                'instance_api' => $input['instanceAPI'],
                'key_api'      => $input['keyAPI'],
                'ativar_wa'    => ($input['ativawa']) ?? 0,
            ];

            $status = $this->modelAdmin->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelAdmin->errors());
            }

            return $this->respond(['msg' => $data]);
        } catch (Exception $e) {

            return $this->fail(['error' => $e->getMessage()]);
        }
    }

    public function updateS3($id = null): ResponseInterface
    {
        try {

            if (!$id) {
                throw new RuntimeException('ID não informado');
            }

            $input   = $this->request->getRawInput();

            $data = [
                's3_access_key_id'     => $input['s3Id'],
                's3_secret_access_key' => $input['s3Key'],
                's3_region'            => $input['s3Regiao'],
                //'s3_endpoint' => $input[''],
                's3_bucket_name' => $input['s3Bucket'],
                's3_cdn'         => $input['s3Cdn'],
            ];

            $status = $this->modelAdmin->update($id, $data);

            if ($status === false) {
                return $this->fail($this->modelAdmin->errors());
            }

            return $this->respond(['msg' => $data]);
        } catch (Exception $e) {

            return $this->fail(['error' => $e->getMessage()]);
        }
    }

    public function testeS3(): ResponseInterface
    {
        try {
            $uploadsLibraries = new UploadsLibraries();
            $result           = $uploadsLibraries->testConnection();

            return $this->respond($result);
        } catch (Exception $e) {
            log_message('error', 'Erro ao testar a conexão com o S3: ' . $e->getMessage());

            return $this->fail('Erro ao testar a conexão com o S3: ' . $e->getMessage());
        }
    }

    public function testWhatsApp(): ResponseInterface
    {
        $whatsapp = new WhatsappLibraries();

        $input = $this->request->getRawInput();

        $message['message'] = $input['message'];
        $number             = $input['numberSend'];

        $send = $whatsapp->verifyNumber($message, $number);

        if ($send) {
            return $this->respond(['msg' => 'Executado com sucesso. Verifique se recebeu a mensagem.']);
        }

        return $this->fail('Houve um erro na requisição. Tente novamente caso o erro persista, verifique com suporte se a instância está conectada.');

        //return $this->respond($input);
    }

    public function update($id = null)
    {
        return $this->failNotFound();
    }

    /**
     * Delete the designated resource object from the model
     *
     * @param null $id
     * @return ResponseInterface
     */
    public function delete($id = null): ResponseInterface
    {
        //
        return $this->failNotFound();
    }
}
