<?php

namespace App\Libraries;

class EmailsLibraries
{
    protected $envio;
    protected $remetente;
    protected $nomeRemetente;
    protected $modelConfig;


    public function __construct()
    {
        $email = \Config\Services::email();

        $config['protocol']   = 'smtp';
        $config['SMTPHost']   = getenv("SMTP.Host");
        $config['SMTPUser']   = getenv("SMTP.User");
        $config['SMTPPass']   = getenv("SMTP.Pass");
        $config['SMTPPort']   = getenv("SMTP.Port");
        $config['mailType']   = 'html';
        # $config['SMTPCrypto'] = getenv("SMTP.Crypt");

        $this->envio = $email->initialize($config);

        $this->modelConfig = new \App\Models\AdminModel;

        $configs = $this->modelConfig->find(1);

        $this->remetente     = $configs['email_remetente'];
        $this->nomeRemetente = $configs['nome_remetente'];
    }

    protected function data(): array{

        

        return [];
    }

    public function envioTeste(string $email, string $assunto, string $message)
    {
        try {

            $this->envio->setFrom($this->remetente, $this->nomeRemetente);
            $this->envio->setTo($email);
            $this->envio->setSubject($assunto);
            $this->envio->setMessage($message);
            $this->envio->send(true);

            return $this->envio->printDebugger(['headers']);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
