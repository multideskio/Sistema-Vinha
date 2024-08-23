<?php

namespace App\Libraries;

use App\Models\AdminModel;
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

    public function __construct()
    {
        $this->email = Services::email();

        $data = $this->data();

        if ($data['ativar_smtp']) {
            log_message('info', '[Linha ' . __LINE__ . '] SMTP está ativado, inicializando...');
            $this->initializeSMTP($data);
        } else {
            log_message('warning', '[Linha ' . __LINE__ . '] SMTP não está ativado, e-mails serão enviados usando o PHP mail().');
        }

        $this->remetente     = $data['e-remetente'];
        $this->nomeRemetente = $data['n-remetente'];
    }

    protected function data(): array
    {
        log_message('info', '[Linha ' . __LINE__ . '] Obtendo configurações de e-mail...');
        $modelAdmin = new AdminModel();
        $data = $modelAdmin->find(1);
        return [
            'SMTPHost' => $data['smtp_host'],
            'SMTPUser' => $data['smtp_user'],
            'SMTPPass' => $data['smtp_pass'],
            'SMTPPort' => intval($data['smtp_port']),
            'SMTPCrypto' => $data['smtp_crypt'], // Adicionado o campo SMTPCrypto
            'e-remetente' => $data['email_remetente'],
            'n-remetente' => $data['nome_remetente'],
            'ativar_smtp' => $data['ativar_smtp']
        ];
    }

    protected function initializeSMTP(array $data)
    {
        log_message('info', '[Linha ' . __LINE__ . '] Inicializando configurações SMTP...');
        $config['protocol']   = 'smtp';
        $config['SMTPHost']   = $data['SMTPHost'];
        $config['SMTPUser']   = $data['SMTPUser'];
        $config['SMTPPass']   = $data['SMTPPass'];
        $config['SMTPPort']   = $data['SMTPPort'];
        $config['SMTPCrypto'] = $data['SMTPCrypto']; // Configura a criptografia SMTP
        $config['mailType']   = 'html';
        $this->email->initialize($config);
        log_message('info', '[Linha ' . __LINE__ . '] Configurações SMTP inicializadas com sucesso.');
    }

    public function envioEmail(string $email, string $assunto, string $message)
    {
        try {
            log_message('info', '[Linha ' . __LINE__ . "] Enviando e-mail para {$email} com assunto '{$assunto}'");
            $this->email->setFrom($this->remetente, $this->nomeRemetente);
            $this->email->setTo($email);
            $this->email->setSubject($assunto);
            $this->email->setMessage($message);

            if (!$this->email->send()) {
                log_message('error', '[Linha ' . __LINE__ . '] Falha ao enviar e-mail via SMTP, tentando com mail() interno...');
                $this->sendWithMail($email, $assunto, $message);
            } else {
                log_message('info', '[Linha ' . __LINE__ . '] E-mail enviado com sucesso via SMTP.');
            }
        } catch (Exception $e) {
            log_message('critical', '[Linha ' . __LINE__ . '] Erro crítico ao enviar o e-mail: ' . $e->getMessage());
            throw new Exception('Falha ao enviar o e-mail. Detalhes: ' . $e->getMessage());
        }
    }

    protected function sendWithMail(string $email, string $assunto, string $message)
    {
        $headers = "From: {$this->nomeRemetente} <{$this->remetente}>\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($email, $assunto, $message, $headers)) {
            log_message('info', '[Linha ' . __LINE__ . '] E-mail enviado com sucesso usando o PHP mail().');
        } else {
            log_message('critical', '[Linha ' . __LINE__ . '] Falha ao enviar e-mail usando o PHP mail().');
            throw new Exception('Falha ao enviar e-mail usando o PHP mail().');
        }
    }

    public function testarEnvioEmail(string $email, string $assunto, string $message): bool
    {
        try {
            log_message('info', '[Linha ' . __LINE__ . "] Testando envio de e-mail para {$email} com assunto '{$assunto}'");
            $this->email->setFrom($this->remetente, $this->nomeRemetente);
            $this->email->setTo($email);
            $this->email->setSubject($assunto);
            $this->email->setMessage($message);

            if (!$this->email->send()) {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', '[Linha ' . __LINE__ . '] Falha no envio de teste via SMTP: ' . $error);
                log_message('info', '[Linha ' . __LINE__ . '] Tentando envio de teste com PHP mail().');

                $this->sendWithMail($email, $assunto, $message);
            } else {
                log_message('info', '[Linha ' . __LINE__ . '] E-mail de teste enviado com sucesso via SMTP.');
            }

            return true;
        } catch (Exception $e) {
            log_message('error', '[Linha ' . __LINE__ . '] Erro ao testar envio de e-mail: ' . $e->getMessage());
            return false;
        }
    }
}