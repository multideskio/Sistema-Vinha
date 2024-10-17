<?php

namespace App\Libraries;

use App\Models\ConfigMensagensModel;
use App\Models\UsuariosModel;

class NotificationLibrary
{
    protected WhatsappLibraries $whatsapp;
    protected EmailsLibraries $emailLibrary;

    public function __construct()
    {
        $this->whatsapp     = new WhatsappLibraries();
        $this->emailLibrary = new EmailsLibraries();
    }

    /**
     * @throws \Exception
     */
    public function sendWelcomeMessage($nome, $email, $celular): void
    {
        $modelMessages = new ConfigMensagensModel();
        $messages      = $modelMessages->where('tipo', 'novo_usuario')->first();

        if ($messages['status']) {
            $valores = [
                '{NOME}'  => $nome,
                '{EMAIL}' => $email,
                '{TEL}'   => $celular,
            ];
            $novaString     = strtr($messages['mensagem'], $valores);
            $msg['message'] = $novaString;
            $this->whatsapp->sendMessageText($msg, $celular) ?? false;
        }
    }

    public function sendVerificationEmail($email, $nome): void
    {
        $modelUser = new UsuariosModel();
        $rowUser   = $modelUser->where('email', $email)->first();

        //log_message('info', '[Linha ' . __LINE__ . '] Dados do usuário: '. json_encode($rowUser));

        if ($rowUser) {

            $sendEmail = [
                'nome'  => $nome,
                'token' => $rowUser['token'],
            ];

            $message = view('emails/primeiro-acesso', $sendEmail);
            $this->emailLibrary->envioEmail($email, 'Confirme seu e-mail', $message);
        } else {
            log_message('info', '[Linha ' . __LINE__ . '] O cadastro do usuário não foi encontrado.');
        }
    }
}
