<?php

namespace App\Libraries;

use App\Models\ConfigMensagensModel;
use App\Models\UsuariosModel;

class NotificationLibrary
{
    protected $whatsapp;
    protected $emailLibrary;

    public function __construct()
    {
        $this->whatsapp     = new WhatsappLibraries();
        $this->emailLibrary = new EmailsLibraries();
    }

    public function sendWelcomeMessage($nome, $email, $celular)
    {
        $modelMessages = new ConfigMensagensModel();
        $messages = $modelMessages->where('tipo', 'novo_usuario')->first();

        if ($messages['status']) {
            $valores = [
                '{NOME}'  => $nome,
                '{EMAIL}' => $email,
                '{TEL}'   => $celular
            ];
            $novaString = strtr($messages['mensagem'], $valores);
            $msg['message'] = $novaString;
            $this->whatsapp->sendMessageText($msg, $celular);
        }
    }

    public function sendVerificationEmail($email, $nome)
    {
        $modelUser = new UsuariosModel();
        $rowUser = $modelUser->where('email', $email)->first();

        //log_message('info', '[Linha ' . __LINE__ . '] Dados do usuário: '. json_encode($rowUser));

        if ($rowUser) {

            $sendEmail = [
                'nome' => $nome,
                'token' => $rowUser['token']
            ];

            $message = view('emails/primeiro-acesso', $sendEmail);
            $this->emailLibrary->envioEmail($email, 'Confirme seu e-mail', $message);
        }else{
            log_message('info', '[Linha ' . __LINE__ . '] O cadastro do usuário não foi encontrado.');
        }
    }
}