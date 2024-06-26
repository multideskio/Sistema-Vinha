<?php

namespace App\Libraries;

use App\Models\AdminModel;
use Psr\Log\LoggerInterface;
use CodeIgniter\Config\Services;
use Exception;

class EmailsLibraries
{
    protected $envio;
    protected $remetente;
    protected $nomeRemetente;
    protected $modelConfig;
    protected $email;
    protected $config;
    protected $logger;

    public function __construct()
    {
        $this->email = Services::email();
        $this->logger = Services::logger(); // Inicializa o logger diretamente

        $data = $this->data();

        if ($data['ativar_smtp']) {
            $config['protocol']   = 'smtp';
            $config['SMTPHost']   = $data['SMTPHost'];
            $config['SMTPUser']   = $data['SMTPUser'];
            $config['SMTPPass']   = $data['SMTPPass'];
            $config['SMTPPort']   = $data['SMTPPort'];
            $config['mailType']   = 'html';
            $this->email->initialize($config);
        }

        $this->remetente     = $data['e-remetente'];
        $this->nomeRemetente = $data['n-remetente'];
    }

    protected function data(): array
    {
        $modelAdmin = new AdminModel();
        $data = $modelAdmin->find(1);
        return [
            'SMTPHost' => $data['smtp_host'],
            'SMTPUser' => $data['smtp_user'],
            'SMTPPass' => $data['smtp_pass'],
            'SMTPPort' => $data['smtp_port'],
            'e-remetente' => $data['email_remetente'],
            'n-remetente' => $data['nome_remetente'],
            'ativar_smtp' => $data['ativar_smtp']
        ];
    }

    public function envioEmail(string $email, string $assunto, string $message)
    {
        $this->email->setFrom($this->remetente, $this->nomeRemetente);
        $this->email->setTo($email);
        $this->email->setSubject($assunto);
        $this->email->setMessage($message);
        $this->email->send();
    }

    public function testarEnvioEmail(string $email, string $assunto, string $message): bool
    {

        $this->email->setFrom($this->remetente, $this->nomeRemetente);
        $this->email->setTo($email);
        $this->email->setSubject($assunto);
        $this->email->setMessage($message);

        if (!$this->email->send()) {
            $error = $this->email->printDebugger(['headers']);
            $this->logger->error('Test email sending failed: ' . $error);
            throw new Exception('Falha ao enviar o email de teste. Detalhes: ' . $error);
        }

        return true;
    }
}
